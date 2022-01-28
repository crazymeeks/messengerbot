<?php

namespace App\Models;

use Crazymeeks\MongoDB\Model\AbstractModel as Model;

class Catalog extends Model
{
    
    const ACTIVE = '1';
    const INACTIVE = '0';

    protected $collection = 'catalogs';

    protected $fillable = [
        'name',
        'description',
        'sku',
        'price',
        'image_urls',
        'discount_price',
        'status',
        'created_at',
        'updated_at',
    ];
}
