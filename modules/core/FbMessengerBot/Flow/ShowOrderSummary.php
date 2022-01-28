<?php

/**
 * Add to Cart
 */
namespace FbMessengerBot\Flow;

use App\Models\Cart;
use App\Models\NextFlow;
use App\Models\Data\NextFlow as NextFlowData;
use App\Models\Repositories\NextFlowRepository;
use App\Models\Repositories\CartRepository;
use Crazymeeks\MessengerBot\Builder\FlowBuilder;
use Crazymeeks\MessengerBot\Builder\Messaging\MessagingInterface;
use Crazymeeks\MessengerBot\Builder\Messaging\ClassTypeFlowInterface;

class ShowOrderSummary implements ClassTypeFlowInterface
{

    public function getResponse(FlowBuilder $server, MessagingInterface $message): array
    {

        $payload = $server->getPostBackPayload();
        
        try{

            $cartRepository = new CartRepository();
            $cartItems = $cartRepository->getCartItemsByUser($payload->fb_id);
               
            if (count($cartItems) > 0) {
                $text = "Order Summary\n\n";
                $grandTotal = 0;
                foreach($cartItems as $cartItem){
                    $total = $cartItem->cart_item[0]->price * $cartItem->quantity;
                    $text .= "Item name: " . $cartItem->cart_item[0]->name . "\nPrice: PHP" . number_format($cartItem->cart_item[0]->price, 2) . "\nQuantity: " . $cartItem->quantity . "\nTotal: PHP" . number_format($total, 2) . "\n\n";
                    $grandTotal += $total;
                    unset($cartItem);
                }

                $text .= "\n\nGrand Total: PHP" . number_format($grandTotal, 2) . "\n\nPlease enter your name :)";
                $nextFlowRepository = app(NextFlowRepository::class);
                $nextFlowRepository->save($this->getNextFlow($payload));
                return [
                    'message' => [
                        'text' => $text
                    ]
                ];
            }

        }catch(\Exception $e){
           
            return [
                'message' => [
                    'text' => 'Sorry, you don\'t have any item in your cart. Would you like to add?',
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
                            'title' => 'No',
                            'payload' => json_encode([
                                'action' => 'end'
                            ]),
                        ],
                    ]

                ]
            ];
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