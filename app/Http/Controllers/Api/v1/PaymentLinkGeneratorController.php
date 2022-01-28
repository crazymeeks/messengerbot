<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Data\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\OrderRepository;
use Crazymeeks\Foundation\PaymentGateway\Dragonpay;
use Crazymeeks\Foundation\PaymentGateway\Dragonpay\Token;

class DragonpayPaymentLinkGeneratorController extends Controller
{

    protected $dp;
    protected $orderRepository;

    public function __construct(Dragonpay $dp, OrderRepository $orderRepository)
    {
        $this->dp = $dp;
        $this->orderRepository = $orderRepository;
    }
    

    public function generateLinkAndCreateOrder(Request $request)
    {
        
        $request->validate([
            'amount' => 'required|numeric',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email:rfc,dns',
            'items' => 'required',
            'shipping_address' => 'required'
        ]);
        
        $refno = strtoupper(uniqid());
        $parameters = [
            'txnid' => $request->has('test') ? 'TESTING' : $refno, # Varchar(40) A unique id identifying this specific transaction from the merchant site
            'amount' => $request->amount, # Numeric(12,2) The amount to get from the end-user (XXXX.XX)
            'ccy' => 'PHP', # Char(3) The currency of the amount
            'description' => 'Processing the payment of ' . $request->firstname . ' ' . $request->lastname, # Varchar(128) A brief description of what the payment is for
            'email' => $request->email, # Varchar(40) email address of customer
        ];
        
        
        $this->dp->filterPaymentChannel(Dragonpay::ONLINE_BANK);
        $token = $this->dp->getToken($parameters);
        
        if ($token instanceof Token) {
            $queryString = $this->dp->parameters->query();
            
            $this->orderRepository->createOrder($this->extractOrderFromRequest($request, $refno));
            return response()->json(['url' => $this->dp->getPaymentUrl() . '?' . $queryString], 200);
        }
        return response()->json(['error' => 'Unable to generate dragonpay link. Please check your parameter.'], 400);
    }

    private function extractOrderFromRequest(Request $request, $refno)
    {
        $order = app(Order::class);
        
        $order->setFirstname($request->firstname)
              ->setLastname($request->lastname)
              ->setEmail($request->email)
              ->setReferenceNumber($refno)
              ->setSource(Order::DEFAULT_SOURCE)
              ->setShippingAddress($request->shipping_address)
              ->setPaymentMethod(Order::DEFAULT_PAYMENT_METHOD)
              ->setPaymentStatus(Order::DEFAULT_PAYMENT_STATUS)
              ->setState(Order::DEFAULT_STATE)
              ->setItems(['items' => json_decode($request->items, true)]);

        
        return $order;
    }

}
