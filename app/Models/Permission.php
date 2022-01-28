<?php

namespace App\Models;

use Crazymeeks\MongoDB\Model\AbstractModel as Model;

class Permission extends Model
{
    
    protected $collection = 'permissions';

    protected $fillable = [
        'value'
    ];
}
