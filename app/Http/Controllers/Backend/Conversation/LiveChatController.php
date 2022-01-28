<?php

namespace App\Http\Controllers\Backend\Conversation;

use App\Models\Chatter;
use MongoDB\BSON\ObjectId;
use Ixudra\Curl\CurlService;
use Illuminate\Http\Request;
use App\Models\ConversationReply;
use App\Http\Controllers\Controller;
use App\Models\Data\LiveChat as LiveChatData;
use App\Models\Repositories\LiveChatRepository;
use App\Models\Repositories\NextFlowRepository;

class LiveChatController extends Controller
{
    
    private $curl;

    public function __construct(CurlService $curl)
    {
        $this->curl = $curl;
    }

    public function messages()
    {
        $view_data = [
            'page_title' => 'Conversation::Messenger'
        ];
        return view('backend.pages.conversation.messenger.messages', $view_data);
    }

    public function getConversationDataTable(Request $request, LiveChatRepository $liveChatRepository)
    {
        
        $convos = $liveChatRepository->setDataTableLimit($request->length)
                                      ->setDataTableOffset($request->start)
                                      ->setDataTableOrder($request->columns[$request->order[0]['column']]['data'], $request->order[0]['dir'])
                                      ->setDataTableSearch($request->search['value'])
                                      ->setRequest($request)
                                      ->getDataTableData();
        return $convos;
    }

    /**
     * View conversation for specific user
     *
     * @param string $id
     * 
     * @return \ILluminate\View\View
     */
    public function viewConversation(string $id)
    {
        
        $chatter = new Chatter();
        $chat = $chatter->findOne(['_id' => new ObjectId($id)]);

        $reply = new ConversationReply();
        $convos = $reply->find(['chatter_id' => new ObjectId($id)])->toArray();
        
        $this->markConvoAsRead($id);
        

        $view_data = [
            'page_title' => 'Messages',
            'convos' => json_encode($convos),
            'chatter' => $chat

        ];
        
        return view('backend.pages.conversation.messenger.reply-form', $view_data);
    }

    /**
     * Check user if initiated live conversation
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Repositories\NextFlowRepository $nextFlowRepository
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIfLiveChatMode(Request $request, NextFlowRepository $nextFlowRepository)
    {
        $request->validate([
            'recipient_id' => 'required'
        ]);

        $status_code = $nextFlowRepository->checkIfLiveChatMode($request->recipient_id) ? 200 : 400;

        return response()->json('Validated mode of current user', $status_code);

    }

    /**
     * Send direct reply to messenger
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Repositories\LiveChatRepository $liveChatRepository
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postReply(Request $request, LiveChatRepository $liveChatRepository)
    {
        try{

            $liveChatRepository->save($this->extractData($request));
            $this->sendReplyToMessenger($request);
            return response()->json('Reply submitted.', 200);
        }catch(\App\Exceptions\LiveChatReplyExceptions $e){
            return response()->json($e->getMessage(), 400);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json('System error!', 400);
        }
    }

    /**
     * End live chat
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Repositories\LiveChatRepository $liveChatRepository
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function endLiveChat(Request $request, LiveChatRepository $liveChatRepository)
    {
        $request->merge([
            'message' => 'CSR ended live chat'
        ]);

        try{
            $this->sendReplyToMessenger($request);
            $liveChatRepository->save($this->extractData($request));
            $nextFlow = new \App\Models\NextFlow();
            $nextFlow->deleteOne(['bot_user_id' => $request->recipient_id]);
            return response()->json('Live Conversation ended');
        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json('Error while attempting to end live conversation with customer', 400);
        }
    }

    /**
     * Get real time chat and display in backend
     *
     * @param integer $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLiveFeed(string $id)
    {
        
        $reply = new ConversationReply();
        $convos = $reply->find(['chatter_id' => new ObjectId($id), 'answered_by_admin' => '0'])->toArray();

        if (count($convos) > 0) {
            
            $this->markConvoAsRead($id);

        }
        return response()->json(['data' => $convos]);
    }

    private function markConvoAsRead(string $id)
    {
        
        $reply = new ConversationReply();

        $reply->updateMany(['chatter_id' => new ObjectId($id)], [
            '$set' => [
                'answered_by_admin' => LiveChatData::ADMIN_REPLY
            ]
        ]);
    }

    private function extractData(Request $request)
    {
        $data = new LiveChatData();

        $data->setPageId($request->page_id)
             ->setRecipientId($request->recipient_id)
             ->setMessage($request->message)
             ->setRequest($request);

        return $data;
    }

    private function sendReplyToMessenger(Request $request)
    {
        $response = $this->curl->to('https://featbetabot.nuworks.ph/api/v1/messenger/livechat')
                               ->withData([
                                   'page_id' => $request->page_id,
                                   'recipient_id' => $request->recipient_id,
                                   'message' => $request->message
                               ])
                               ->withResponseHeaders()
                               ->returnResponseObject()
                               ->post();
        if (in_array($response->status, [200, 201])) {
            return true;
        }
        throw new \App\Exceptions\LiveChatReplyExceptions("Error while sending reply. Please try again.");
    }

}
