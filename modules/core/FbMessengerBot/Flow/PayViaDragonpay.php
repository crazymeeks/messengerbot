<?php

/**
 * Add to Cart
 */
namespace FbMessengerBot\Flow;

use App\Models\Order;
use App\Models\Catalog;
use App\Models\NextFlow;
use App\Models\Data\Cart;
use App\Models\ShippingDetails;
use App\Models\Repositories\OrderRepository;
use Payment\DragonPay;

class PayViaDragonpay implements \Chatbot\Api\FlowInterface
{

    public function getResponse(\FbMessengerBot\HttpClient\Server $server): array
    {

        $payload = $server->getPostBackPayload();
        
        try{

            // create order
            $orderRepository = new OrderRepository();
            $order = $orderRepository->save($this->getOrderFromCart($payload));
            
            $dragonpay = app(DragonPay::class);
            
            $parameters = [
                'txnid' => $order->getReferenceNumber(), # Varchar(40) A unique id identifying this specific transaction from the merchant site
                'amount' => $order->getGrandTotal(), # Numeric(12,2) The amount to get from the end-user (XXXX.XX)
                'ccy' => 'PHP', # Char(3) The currency of the amount
                'description' => 'Test payment', # Varchar(128) A brief description of what the payment is for
                'email' => $order->getEmail(), # Varchar(40) email address of customer
    
            ];

            $link = $dragonpay->getLink($parameters);
            $server->resetSpecifics();
            return [
                'attachment' => [
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'button',
                        'text' => 'Please click Pay Now! to pay :) You will be redirected to payment portal',
                        'buttons' => [
                            [
                                'type' => 'web_url',
                                'url' => $link,
                                'title' => 'Pay Now!'
                            ]
                        ]
                    ]
                ]
            ];
        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return [
                'text' => 'Sorry, we are unable to add this item to your cart :( Can you please try again?',
            ];
        }
    }

    private function getOrderFromCart(\stdClass $payload)
    {
        $order = new \App\Models\Data\Order();
        $shipping_details = new ShippingDetails();
        $shipping = $shipping_details->findOne(['user_id' => $payload->fb_id]);
        
        $name = explode(' ', $shipping->name);
        $lastname = null;
        if (count($name) > 1) {
            $firstname = $name[0];
            $lastname = $name[1];
        }
        $order->setUserId($payload->fb_id)
              ->setReferenceNumber(\App\Models\Data\Order::generateReferenceNumber())
              ->setFirstName($firstname)
              ->setLastName($lastname)
              ->setEmail($shipping->email)
              ->setMobileNumber($shipping->mobile_number)
              ->setShippingAddress($shipping->shipping_address)
              ->setStatus('Pending')
              ->setPaymentMethod('Dragonpay')
              ->setPaymentStatus('Pending');

        return $order;
    }
}