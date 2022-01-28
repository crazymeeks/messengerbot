<?php

namespace Tests\DataProvider\Catalog;

use App\Models\Product;

class DataProvider
{


    public function createCatalog()
    {
        $data = [
            'brand' => 1,
            'unit' => 1,
            'name' => 'Product 1',
            'sku' => 'Product 1',
            'description' => 'Product 1',
            'type' => Product::TYPE_SIMPLE,
            'price' => 25.00,
            'discount_price' => 20.00,
            'status' => Product::ACTIVE,
            'enable_discount' => Product::NO_DISCOUNT,
        ];
        

        return [
            array($data)
        ];
    }
}