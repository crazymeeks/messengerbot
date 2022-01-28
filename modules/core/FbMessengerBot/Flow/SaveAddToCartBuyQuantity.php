<?php

/**
 * Add to Cart
 */
namespace FbMessengerBot\Flow;

use App\Models\Catalog;
use App\Models\NextFlow;
use App\Models\Data\Cart;
use App\Models\Repositories\CartRepository;
use App\Models\Repositories\NextFlowRepository;

class SaveAddToCartBuyQuantity implements \Chatbot\Api\FlowInterface
{

    public function getResponse(\FbMessengerBot\HttpClient\Server $server): array
    {

        $payload = $server->getPostBackPayload();
        
        try{
            // @todo:: save cart quantity to database
            $cartRepository = new CartRepository();
            $cartRepository->save($this->extractData($payload));
            $nextFlowRepository = app(NextFlowRepository::class);
            $nextFlowRepository->deleteUserFlow($payload->fb_id);
            
            return [
                'text' => "Quantity has been added to your cart :) Add more?",
                'quick_replies' => [
                    [
                        'content_type' => 'text',
                        'title' => 'Yes',
                        'payload' => json_encode([
                            'action' => 'show_products'
                        ]),
                    ],
                    [
                        'content_type' => 'text',
                        'title' => 'No, Proceed to Checkout',
                        'payload' => json_encode([
                            'action' => 'show_order_summary'
                        ]),
                    ],
                ]
            ];

        }catch(\Exception $e){
           \Log::error($e->getMessage());
            return [
                'text' => 'Sorry, we are unable to add quantity to your cart :( Can you please try again?',
            ];
        }
    }

    private function extractData(\stdClass $payload)
    {
        
        $catalog = new Cart();
        $next_flow = new NextFlow();
        $result = $next_flow->findOne(['bot_user_id' => $payload->fb_id]);
        
        $catalog->setCatalogId($result->custom_data)
                ->setUserId($payload->fb_id)
                ->setQuantity($payload->user_reply);
        return $catalog;
    }
}