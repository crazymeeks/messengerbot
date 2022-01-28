<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ShoppingCart;
use Faker\Generator as Faker;

$factory->define(ShoppingCart::class, function (Faker $faker) {
    return [
        '_token' => 'laravel-csrf-token',
        'product_id' => 1,
        'quantity' => 1,
    ];
});
