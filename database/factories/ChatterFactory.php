<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Chatter;
use Faker\Generator as Faker;


$factory->define(Chatter::class, function (Faker $faker) {
    
    return [
        'fb_id' => '3429492650408259',
        'fullname' => 'John Doe',
        'read' => '0'
    ];
});
