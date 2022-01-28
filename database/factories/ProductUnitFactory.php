<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ProductUnit;
use Faker\Generator as Faker;

$factory->define(ProductUnit::class, function (Faker $faker) {
    return [
        'unit_value' => '330',
        'unit_type' => 'ml',
    ];
});
