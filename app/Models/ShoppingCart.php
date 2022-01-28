<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    

    protected $table = 'shopping_cart';
    
    protected $fillable = [
        '_token',
        'product_id',
        'quantity',
    ];
}
