<?php

/**
 * Add to Cart
 */
namespace FbMessengerBot\Flow;

use App\Models\Catalog;
use App\Models\NextFlow;
use App\Models\Data\Cart;
use App\Models\Repositories\CartRepository;

class BuyQuantity implements \Chatbot\Api\FlowInterface
{

    public function getResponse(\FbMessengerBot\HttpClient\Server $server): array
    {

        $payload = $server->getPostBackPayload();
        
        try{
            
            $cartRepository = new CartRepository();
            $cartRepository->save($this->extractData($payload));
            $catalog = new Catalog();
            $catalog = $catalog->findOne(['_id' => new \MongoDB\BSON\ObjectId($payload->_id)]);

            $this->saveNextFlow($payload);
            
            return [
                'text' => "How many {$catalog->name} would you like to buy?"
            ];
        }catch(\Exception $e){
            
            return [
                'text' => 'Sorry, we are unable to add this item to your cart :( Can you please try again?',
            ];
        }
    }

    /**
     * @todo:: Refactor this
     * 
     * @see \FbMessengerBot\HttpClient\Server::saveNextFlow()
     *
     * @param \stdClass $payload
     * 
     * @return void
     */
    private function saveNextFlow(\stdClass $payload)
    {
        $next_flow = new NextFlow();
        $next_flow->saveNextFlow('save_input_cart_quantity', $payload->fb_id, $payload->_id);
    }

    private function extractData(\stdClass $payload)
    {
        $catalog = new Cart();
        
        $catalog->setCatalogId($payload->_id)
                ->setUserId($payload->fb_id)
                ->setQuantity(0);
        return $catalog;
    }
}