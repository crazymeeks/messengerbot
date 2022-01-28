<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order;
use Faker\Generator as Faker;
use App\Models\Api\OrderInterface;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'firstname' => 'John',
        'lastname' => 'Doe',
        'email' => 'john.doe@example.com',
        'mobile_number' => '09356565254',
        'reference_number' => \Tests\Feature\Backend\Orders\OrderControllerTest::TEST_ORDER_REF,
        'source' => OrderInterface::DEFAULT_SOURCE,
        'shipping_address' => 'Manila',
        'payment_method' => OrderInterface::DEFAULT_PAYMENT_METHOD,
        'payment_status' => OrderInterface::DEFAULT_PAYMENT_STATUS,
        'state' => OrderInterface::DEFAULT_STATE,
    ];
});
