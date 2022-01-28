<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MessengerFlowNextStep;
use Faker\Generator as Faker;

$factory->define(MessengerFlowNextStep::class, function (Faker $faker) {
    return [
        'fb_id' => 'fb-id',
        'next_expected_steps' => json_encode([
            \FbMessengerBot\Flow\Contracts\FlowInterface::GET_STARTED,
        ]),
        'trigger_class' => '\Namespace\Of\Trigger\Class'
    ];
});
