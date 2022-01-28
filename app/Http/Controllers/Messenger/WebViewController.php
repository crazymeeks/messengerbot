<?php

namespace App\Http\Controllers\Messenger;

use DB;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ShoppingCart;
use App\Models\Api\OrderInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use FbMessengerBot\Messenger\Facebook\GetProfile;
use App\Models\Repositories\OrderRepository;
use Crazymeeks\Foundation\PaymentGateway\Dragonpay;
use App\Mail\Messengerbot\CheckoutOrderConfirmation;
use Crazymeeks\Foundation\PaymentGateway\Dragonpay\Token;

class WebViewController extends Controller
{
    
    const MSG_ID_SESSION = 'sender_id_session';

    

    /**
     * Return order view form. This will be iframed by messenger(webview)
     *
     * @param Request $request
     * @param string $senderid
     *    Id of current messenger user
     * 
     * @return \Illuminate\View\View
     */
    public function getOrderViewForm(Request $request, $senderid, $brand, $pageid)
    {
        
        $products = Product::where('product_brand_id', $brand)->orderBy('id','desc')->limit(10)->get();
        
        $_products = [];
        foreach($products as $product){
            list($image_url) = explode(';', $product->image_url);
            $cart = ShoppingCart::where('_token', csrf_token())
                                ->where('product_id', $product->id)
                                ->first();
            $_products[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image_url' => url($image_url), //'https://beta-chatbot-payment.nuworks.ph/images/catalog/oCgtLWZVJqc3AgviHC4xKVKhZD6K8CTjtiurQN1j.png',//,
                'description' => $product->description,
                'quantity' => $cart->quantity?? 0,
            ];
        }

        $view_data = [
            'products' => json_decode(json_encode($_products)),
            'senderid' => Crypt::encryptString($senderid),
            'pageid'   => Crypt::encryptString($pageid),
        ];

        return view('frontend.messengerbot.webview.ordernow.form', $view_data);
    }

    /**
     * Add item to cart
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request)
    {
        
        $request->validate([
            '_token' => 'required',
            'product_id' => 'required',
            'quantity' => 'required|numeric'
        ]);

        
        $cart = ShoppingCart::where('_token', $request->_token)
                           ->where('product_id', $request->product_id);
        if ($cart->count() > 0) {
            $cart = $cart->first();
            
        } else {
            $cart = app(ShoppingCart::class);
        }

        $cart->_token = $request->_token;
        $cart->product_id = $request->product_id;
        $cart->quantity = $request->quantity;

        $cart->save();

        $carts = DB::table('shopping_cart')
                   ->leftJoin('products', 'shopping_cart.product_id', '=', 'products.id')
                   ->where('_token', $request->_token)
                   ->get();

        $grandTotal = 0;
        foreach($carts as $cart){
            $total = $cart->quantity * $cart->price;
            $grandTotal = $grandTotal + $total;
        }


        return response()->json(['grand_total' => $grandTotal], 200);

    }

    /**
     * Checkout
     *
     * @param \Illuminate\Http\Request $request
     * @param \Crazymeeks\Foundation\PaymentGateway\Dragonpay $dragonpay
     * @param \App\Models\Repositories\OrderRepository $orderRepository
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCheckout(Request $request, Dragonpay $dragonpay, OrderRepository $orderRepository)
    {
        
        $rules = [
            '_token' => 'required',
            'firstname' => 'required',
            'email' => 'required|email:rfc,dns',
            'shipping_address' => 'required',
            'mview_sender_id' => 'required',
            'pageid' => 'required',
        ];
        if ($request->has('___xTestx')) {
            unset($rules['email']);
        }
        $request->validate($rules);


        $refno = strtoupper(uniqid());
        
        $items = DB::table('shopping_cart')
                  ->select(
                      'products.*',
                      'shopping_cart.quantity',
                      'shopping_cart.id as cart_id'
                  )
                  ->leftJoin('products', 'shopping_cart.product_id', '=', 'products.id')
                  ->where('shopping_cart._token', $request->_token)
                  ->get();
        
        $_items = [];
        $grandTotal = 0;
        foreach($items as $item){
            $_items[] = [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'name' => $item->name,
                'price' => $item->price,
            ];
            $total = $item->quantity * $item->price;
            $grandTotal += $total;
        }

        $request->merge([
            'items' => json_encode($_items)
        ]);

        $parameters = [
            'txnid' => $request->has('test') ? 'TESTING' : $refno, # Varchar(40) A unique id identifying this specific transaction from the merchant site
            'amount' => $grandTotal, # Numeric(12,2) The amount to get from the end-user (XXXX.XX)
            'ccy' => 'PHP', # Char(3) The currency of the amount
            'description' => 'Processing the payment of ' . $request->firstname . ' ' . $request->lastname, # Varchar(128) A brief description of what the payment is for
            'email' => $request->email, # Varchar(40) email address of customer
        ];
        
        $dragonpay->filterPaymentChannel(Dragonpay::ONLINE_BANK);
        $token = $dragonpay->getToken($parameters);
        
        if ($token instanceof Token) {
            $queryString = $dragonpay->parameters->query();
            
            $orderRepository->createOrder($this->extractOrderFromRequest($request, $refno));
            $payment_url = $dragonpay->getPaymentUrl() . '?' . $queryString;
            try{
                $stdClass = new \stdClass();
                $stdClass->firstname = $request->firstname;
                $stdClass->payment_url = $payment_url;
                $this->fireEvents($request, $payment_url, $_items);
                Mail::to($request->email)->send(new CheckoutOrderConfirmation($stdClass));
            }catch(\Exception $e){
                
                $this->fireUnsentOrderConfirmation($request);
            }

            return response()->json(['url' => $payment_url], 200);
        }
        $this->firePaymentGateUnavailable($request);
        return response()->json(['error' => 'Unable to checkout. Payment gateway unavailable.'], 400);

    }

    private function firePaymentGateUnavailable(Request $request)
    {
        // Send Pay Now! button back to messenger
        $gatewayUnavailable = app(\FbMessengerBot\Messenger\Reply\PaymentGatewayUnavailableReply::class);
        $senderid = Crypt::decryptString($request->mview_sender_id);
        
        $gatewayUnavailable->setSenderId($senderid);

        event(new \App\Events\Messenger\PaymentGateUnavailableEvent($gatewayUnavailable));
    }



    private function fireUnsentOrderConfirmation(Request $request)
    {
        // Send Pay Now! button back to messenger
        $unsentConfirmOrderToMessenger = app(\FbMessengerBot\Messenger\Reply\UnsentOrderConfirmationReply::class);
        $senderid = Crypt::decryptString($request->mview_sender_id);
        $unsentConfirmOrderToMessenger->setSenderId($senderid);

        event(new \App\Events\Messenger\UnsentOrderConfirmationEvent($unsentConfirmOrderToMessenger));
    }

    private function fireEvents(Request $request, $payment_url, $_items)
    {
        $senderid = Crypt::decryptString($request->mview_sender_id);
        $pageid = Crypt::decryptString($request->pageid);
        
        // send order confirmation back to messenger
        /**
         * @todo need to bug on this
         */
        
        $pageToken = app(\FbMessengerBot\Messenger\Facebook\Page\Token\Context::class);
        $pageToken->setPageId($pageid);
        $profile = app(GetProfile::class);
        
        $profile->get($pageToken, $senderid);
        
        $reply = app(\FbMessengerBot\Messenger\Reply\OrderSummaryAfterCheckoutReply::class);
        $reply->setProfile($profile)
              ->setSenderId($senderid)
              ->setPageId($pageid)
              ->setReplyAdditional(json_decode(json_encode($_items)));
              
        event(new \App\Events\Messenger\AfterCheckoutEvent($reply));
        
        // Send Pay Now! button back to messenger
        $paybutton = app(\FbMessengerBot\Messenger\Reply\Buttons\Paynow::class);

        $paybutton->setSenderId($senderid)
                  ->setPageId($pageid)
                  ->setReplyAdditional($payment_url);
        
        event(new \App\Events\Messenger\Buttons\PaynowEvent($paybutton));
        
    }

    private function extractOrderFromRequest(Request $request, $refno)
    {
        $order = app(OrderInterface::class);
        
        $order->setFirstname($request->firstname)
              ->setLastname($request->lastname)
              ->setEmail($request->email)
              ->setReferenceNumber($refno)
              ->setSource(OrderInterface::WEBVIEW_SOURCE)
              ->setShippingAddress($request->shipping_address)
              ->setPaymentMethod(OrderInterface::DEFAULT_PAYMENT_METHOD)
              ->setPaymentStatus(OrderInterface::DEFAULT_PAYMENT_STATUS)
              ->setState(OrderInterface::DEFAULT_STATE)
              ->setItems(['items' => json_decode($request->items, true)]);

        
        return $order;
    }
}
