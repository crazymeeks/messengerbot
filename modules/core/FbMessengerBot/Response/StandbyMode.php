<?php

namespace FbMessengerBot\Response;

use Illuminate\Http\Request;
use FbMessengerBot\HttpClient\Server;
use FbMessengerBot\Response\AbstractMode;

class StandbyMode extends AbstractMode
{

    /**
     * @inheritDoc
     */
    public function send(Server $server, Request $request)
    {
        try{
            $id = $this->getRealSenderId($this->parameters['standby'][0]);
    
            if (isset($this->parameters['standby'][0]['message']['text'])) {
                $payload = null;
                if (isset($this->parameters['standby'][0]['message']['quick_reply']['payload'])) {
                    $payload = json_decode($this->parameters['standby'][0]['message']['quick_reply']['payload']);
                } else if (strtolower($this->parameters['standby'][0]['message']['text']) == 'get started') {
                    $payload = json_decode(json_encode([
                        'action' => 'get started',
                        'user_reply' => $this->parameters['standby'][0]['message']['text'],
                        'title' => null
                    ]));
                }
                if ($payload) {
                    // reply back to messenger
                    $response = $server->setPageId($this->page_id)
                                    ->setUserFacebookId($id)
                                    ->setRecipientId($id)
                                    ->setPostBackPayload($payload)
                                    ->setRequest($request)
                                    ->createFlow($payload);
                    
                    return response()->json($response, 200);
                }
            }
        }catch(\Exception $e){
            \Log::error($e->getMessage() . '. Line: ' . $e->getLine());
        }

        return response('EVENT_RECEIVED', 200);
    }
}