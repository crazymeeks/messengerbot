<?php

namespace App\Models\Repositories;

use App\Models\Cart;
use App\Models\Repositories\BaseRepository;

class CartRepository extends BaseRepository
{


    public function getCartItemsByUser(string $user_id)
    {
        $cart = new Cart();
        $cartItems = $cart->aggregate([
            [
                '$match' => [
                    'user_id' => $user_id
                ]
            ],
            [
                '$lookup' => [
                    'from' => 'catalogs',
                    'localField' => 'catalog_id',
                    'foreignField' => '_id',
                    'as' => 'cart_item',
                ]
            ]
        ])->toArray();

        return count($cartItems) > 0 ? $cartItems : null;
    }
}