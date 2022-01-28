<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessengerFlowNextStep extends Model
{
    
    protected $table = 'messenger_flow_next_steps';

    protected $fillable = [
        'fb_id',
        'next_expected_steps',
        'trigger_class',
        'another_trigger_class',
    ];
}
