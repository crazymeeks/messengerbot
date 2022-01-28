<?php

namespace App\Models;

use Crazymeeks\MongoDB\Model\AbstractModel as Model;

class Cart extends Model
{
    protected $collection = 'cart';

    protected $fillable = [
        'user_id',
        'catalog_id',
        'quantity'
    ];
}
