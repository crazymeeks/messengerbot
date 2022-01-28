<?php

/**
 * Add to Cart
 */
namespace FbMessengerBot\Flow;

use App\Models\NextFlow;
use App\Models\Data\NextFlow as NextFlowData;
use App\Models\Repositories\NextFlowRepository;
use Crazymeeks\MessengerBot\Builder\FlowBuilder;
use Crazymeeks\MessengerBot\Builder\Messaging\MessagingInterface;
use Crazymeeks\MessengerBot\Builder\Messaging\ClassTypeFlowInterface;

class AskShippingName implements ClassTypeFlowInterface
{

    public function getResponse(FlowBuilder $server, MessagingInterface $message): array
    {

        $payload = $server->getPostBackPayload();
        
        try{
            
            $nextFlowRepository = app(NextFlowRepository::class);
            $nextFlowRepository->save($this->getNextFlow($payload));

            return [
                'message' => [
                    'text' => "Please enter your name :)"
                ]
            ];
            
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