<?php

namespace App\Models;

use Crazymeeks\MongoDB\Model\AbstractModel as Model;

class ShippingDetails extends Model
{
    
    protected $collection = 'shipping_details';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'mobile_number',
        'shipping_address',
    ];
}
