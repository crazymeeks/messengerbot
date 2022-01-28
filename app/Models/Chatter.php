<?php

namespace App\Models;

use Crazymeeks\MongoDB\Model\AbstractModel as Model;

class Chatter extends Model
{
    protected $collection = 'chatters';
    
    protected $fillable = [
        'page_id',
        'fb_id',
        'fullname',
        'read',
    ];

}
