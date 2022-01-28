<?php

namespace App\Models;

use Crazymeeks\MongoDB\Model\AbstractModel as Model;

class Role extends Model
{
    
    protected $collection = 'roles';

    protected $fillable = [
        'name',
        'description',
    ];
}
