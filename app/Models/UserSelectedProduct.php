<?php

/**
 * Temporary storage of product that users are about to add to their cart
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSelectedProduct extends Model
{
    
    protected $table = 'user_selected_product';

    protected $fillable = [
        'fb_id',
        'product_id',
    ];
}
