<?php

namespace App\Http\Controllers\Api\v1\Messenger;

use App\Models\Data\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Repositories\CartRepository;

class CartController extends Controller
{
    
    /**
     * Add item to cart
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Repositories\CartRepository $cartRepository
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAdd(Request $request, CartRepository $cartRepository)
    {
        $request->validate([
            'catalog_id' => 'required',
            'user_id' => 'required',
            'quantity' => 'required|numeric',
        ]);

        try{
            $cartRepository->save($this->extractData($request));
            return response()->json('Item successfully added in the cart');
        }catch(\Exception $e){
            return response()->json('Oops. Error while adding item to cart. Please try again', 400);
        }

    }

    private function extractData(Request $request)
    {
        $cart = new Cart();
        $cart->setCatalogId($request->catalog_id)
             ->setUserId($request->user_id)
             ->setQuantity($request->quantity);

        return $cart;
    }
}
