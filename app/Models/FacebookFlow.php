<?php

namespace App\Models;

use Crazymeeks\MongoDB\Model\AbstractModel as Model;

class FacebookFlow extends Model
{
    
    protected $collection = 'facebook_flow';

    protected $fillable = [
        'flow'
    ];
}
