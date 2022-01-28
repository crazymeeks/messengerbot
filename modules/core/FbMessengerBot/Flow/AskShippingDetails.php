<?php

/**
 * Add to Cart
 */
namespace FbMessengerBot\Flow;

use App\Models\Catalog;
use App\Models\NextFlow;
use App\Models\Data\Cart;
use App\Models\ShippingDetails;
use App\Models\Data\NextFlow as NextFlowData;
use App\Models\Repositories\NextFlowRepository;

class AskShippingDetails implements \Chatbot\Api\FlowInterface
{

    public function getResponse(\FbMessengerBot\HttpClient\Server $server): array
    {

        $payload = $server->getPostBackPayload();
        
        try{
            
            $shipping_details = new ShippingDetails();
            $result = $shipping_details->findOne(['user_id' => $payload->fb_id]);

            $nextFlowRepository = app(NextFlowRepository::class);
            $nextFlowRepository->save($this->getNextFlow($payload));

            $return = [];
            
            if (!$result) {
                $shipping_details->insertOne([
                    'user_id' => $payload->fb_id,
                    'name' => $payload->user_reply,
                    'email' => null,
                    'mobile_number' => null,
                    'shipping_address' => null,
                ]);
                $return = [
                    'text' => "Please enter your email :)"
                ];
                
            } elseif (!$result->email) {
                $shipping_details->updateOne(['user_id' => $payload->fb_id], [
                    '$set' => [
                        'email' => $payload->user_reply
                    ]
                ]);
                $return = [
                    'text' => 'Please enter your mobile number :)'
                ];
            } elseif (!$result->mobile_number) {
                $shipping_details->updateOne(['user_id' => $payload->fb_id], [
                    '$set' => [
                        'mobile_number' => $payload->user_reply
                    ]
                ]);
                $return = [
                    'text' => 'Please enter your shipping address :)'
                ];
            } elseif (!$result->shipping_address) {
                $shipping_details->updateOne(['user_id' => $payload->fb_id], [
                    '$set' => [
                        'shipping_address' => $payload->user_reply
                    ]
                ]);
                
                $nextFlow = new NextFlow();
                $nextFlow->deleteOne(['bot_user_id' => $payload->fb_id]);

                $return = [
                    'text' => 'Select payment method',
                    'quick_replies' => [
                        [
                            'content_type' => 'text',
                            'title' => 'Dragonpay',
                            'payload' => json_encode([
                                'action' => 'payment_dragonpay'
                            ]),
                        ]
                    ]
                ];
            }

            return $return;
            
        }catch(\Exception $e){
            
        }
    }

    private function getNextFlow(\stdClass $payload)
    {
        $nextFlow = new NextFlowData();
        $nextFlow->setBotType(NextFlow::BOT_MESSENGER)
                 ->setBotUserId($payload->fb_id)
                 ->setNext('complete_shipping_details')
                 ->setCustomData(null);

        return $nextFlow;
    }

}