<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MessengerSetting;
use Faker\Generator as Faker;

$factory->define(MessengerSetting::class, function (Faker $faker) {
    return [
        'page_name' => 'Messenger Bot',
        'page_id' => '12321323',
        'type' => MessengerSetting::MSGR_CONFIG_SETTINGS,
        'primary_access_token' => 'EAAhzopUCXwMBAA8M6p6xW8BqZAkMRl6xtaj3lOrXeYs2aoQSfa1oEXJDjWLntRxLrpRpwqDZAc3nezAShEb7LHl0JZCxi5oiswF96ZBaUlm9pscbNLuStQ2yxSHyZBtxnfte1DcQlPUeseZALzTp4b7Yk1sNKOsJGF4aGkFUevBgZDZD',
        'secondary_access_token' => 'EAAhzopUCXwMBAA8M6p6xW8BqZAkMRl6xtaj3lOrXeYs2aoQSfa1oEXJDjWLntRxLrpRpwqDZAc3nezAShEb7LHl0JZCxi5oiswF96ZBaUlm9pscbNLuStQ2yxSHyZBtxnfte1DcQlPUeseZALzTp4b7Yk1sNKOsJGF4aGkFUevBgZDZD'
    ];
});
