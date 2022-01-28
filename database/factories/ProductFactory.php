<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'product_brand_id' => 1,
        'product_unit_id' => 1,
        'name' => '320ml San Miguel Flavored Beer',
        'description' => '320ml San Miguel Flavored Beer',
        'sku' => '320ml San Miguel Flavored Beer',
        'type_id' => Product::TYPE_SIMPLE,
        'price' => 25,
        'discount_price' => 0,
        'image_url' => '/image/flb.jpg',
        'enable_discount' => Product::NO_DISCOUNT,
        'status' => Product::ACTIVE,
    ];
});
