<?php

namespace App\Models;

use Crazymeeks\MongoDB\Model\AbstractModel as Model;

class Channel extends Model
{
    protected $collection = 'channels';
    
    protected $fillable = [
        'type_identification',
        'page_id',
        'page_name',
        'page_access_token'
    ];
}
