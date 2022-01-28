<?php

namespace App\Models;

use Crazymeeks\MongoDB\Model\AbstractModel as Model;

class OrderCatalog extends Model
{
    protected $collection = 'order_catalogs';

    protected $fillable = [
        'order_id', // instance of \MongoDB\BSON\ObjectId
        'catalog_name',
        'sku',
        'price',
        'quantity'
    ];
}
