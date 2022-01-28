<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProductBrand;
use Faker\Generator as Faker;

$factory->define(ProductBrand::class, function (Faker $faker) {
    return [
        'name' => 'Kirin Ichiban',
        'description' => '<p>Kirin Ichiban</p>',
        'thumbnail' => 'images/product-brands/brand.jpg',
    ];
});
