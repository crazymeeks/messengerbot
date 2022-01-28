<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\UserSelectedProduct;
use Faker\Generator as Faker;

$factory->define(UserSelectedProduct::class, function (Faker $faker) {
    return [
        'fb_id' => '2933643720082184',
        'product_id' => 1,
    ];
});
