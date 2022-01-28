<?php

namespace FbMessengerBot\Response;

use MDB\Client;
use App\Models\Chatter;
use Illuminate\Http\Request;
use App\Models\ConversationReply;
use FbMessengerBot\HttpClient\Server;
use FbMessengerBot\Response\AbstractMode;

class MessagingMode extends AbstractMode
{

    /**
     * @inheritDoc
     */
    public function send(Server $server, Request $request)
    {
        try{
            
            $id = $this->getRealSenderId($this->parameters['messaging'][0]);
            
            $payload = null;
            if (isset($this->parameters['messaging'][0]['postback']['payload'])) {
                $payload = json_decode($this->parameters['messaging'][0]['postback']['payload']);
                $payload->fb_id = $id;
                $payload->page_id = $this->page_id;
                $payload->text = $this->parameters['messaging'][0]['postback']['title'];
                
            } elseif (isset($this->parameters['messaging'][0]['message']['text'])) {
                // is this a quick_reply?
                if (isset($this->parameters['messaging'][0]['message']['quick_reply']['payload'])) {
                    $payload = json_decode($this->parameters['messaging'][0]['message']['quick_reply']['payload']);
                    $payload->text = $this->parameters['messaging'][0]['message']['text'];
                } else {
                    $action = strtolower($this->parameters['messaging'][0]['message']['text']) == 'get started' ? \FbMessengerBot\Flow\Contracts\FlowInterface::GET_STARTED : 'undefined';
                    $payload = json_decode(json_encode([
                        'action' => $action,
                        'user_reply' => $this->parameters['messaging'][0]['message']['text'],
                        'title' => null,
                        'text' => $action == 'undefined' ? $this->parameters['messaging'][0]['message']['text'] : $action,
                    ]));
                }
            }
            
            if ($payload) {
                $payload->fb_id = $id;
                $payload->page_id = $this->page_id;

                $server->setPostBackPayload($payload);

                if ($payload->action == 'get_started') {
                    $server->resetSpecifics();
                }
                // reply back to messenger
                $response = $server->setPageId($this->page_id)
                             ->setUserFacebookId($id)
                             ->setRecipientId($id)
                             ->setRequest($request)
                             ->createFlow($payload);
                            //  ->execute();
                
                $this->saveConversation($payload, $server);
                
                return response()->json($response, 200);
            }
        }catch(\Exception $e){
            
            \Log::error($e->getMessage() . '. Line: ' . $e->getLine());
        }
        
        return response('EVENT_RECEIVED', 200);
    }

    /**
     * Save every conversation to our DB so admin can reply
     * directly from the backend
     *
     * @param \stdClass $payload
     * @param \FbMessengerBot\HttpClient\Server $server
     * 
     * @return void
     */
    private function saveConversation(\stdClass $payload, Server $server)
    {
        
        $fb_id = $server->getUserFacebookId();
        
        $chatter_model = new Chatter();
        $chatter = $chatter_model->findOne(['fb_id' => $fb_id]);

        if ($chatter) {
            $data = [
                'chatter_id' => $chatter->_id,
                'admin_user_id' => null,
                'answered_by_admin' => '0',
                'reply' => $payload->text,
                'time' => time(),
            ];
            
        } else {
            
            $profile = $server->getFacebookProfile();
            
            $chatter = new Chatter();
            
            $chatter = $chatter->insertOne([
                'page_id' => $server->getPageId(),
                'fb_id' => $fb_id,
                'fullname' => $profile->getFirstName() . ' ' . $profile->getLastName(),
                'read' => '0'
            ]);

            $data = [
                'chatter_id' => $chatter->getInsertedId(),
                'admin_user_id' => null,
                'answered_by_admin' => '0',
                'reply' => $payload->text,
                'time' => time(),
            ];
        }
        $reply = new ConversationReply();
        $reply->insertOne($data);
        
    }
}