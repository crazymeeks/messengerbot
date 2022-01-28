<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\AdminUser;
use Faker\Generator as Faker;

$factory->define(AdminUser::class, function (Faker $faker) {
    return [
        'role_id' => 1,
        'firstname' => 'John',
        'lastname' => 'Doe',
        'username' => 'johndoe',
        'email' => 'john.doe@example.com',
        'password' => bcrypt('test'),
        'status' => 'active'
    ];
});
