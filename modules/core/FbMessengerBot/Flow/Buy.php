<?php

/**
 * Add to Cart
 */
namespace FbMessengerBot\Flow;

use App\Models\Catalog;
use App\Models\NextFlow;
use App\Models\Data\Cart;
use App\Models\Repositories\CartRepository;
use App\Models\Data\NextFlow as NextFlowData;
use App\Models\Repositories\NextFlowRepository;

class Buy implements \Chatbot\Api\FlowInterface
{

    public function getResponse(\FbMessengerBot\HttpClient\Server $server): array
    {

        $payload = $server->getPostBackPayload();
        
        try{
            
            $cartRepository = new CartRepository();
            $cartRepository->save($this->extractData($payload));
            $catalog = new Catalog();
            $catalog = $catalog->findOne(['_id' => new \MongoDB\BSON\ObjectId($payload->_id)]);
            $nextFlowRepository = app(NextFlowRepository::class);
            $nextFlowRepository->save($this->getNextFlow($payload, $catalog->_id->__toString()));

            return [
                'text' => "How many {$catalog->name} would you like to buy?"
            ];
        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return [
                'text' => 'Sorry, we are unable to add this item to your cart :( Can you please try again?',
            ];
        }
    }

    private function getNextFlow(\stdClass $payload, string $catalogId)
    {
        $nextFlow = new NextFlowData();
        $nextFlow->setBotType(NextFlow::BOT_MESSENGER)
                 ->setBotUserId($payload->fb_id)
                 ->setNext('save_input_cart_quantity')
                 ->setCustomData($catalogId);

        return $nextFlow;
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