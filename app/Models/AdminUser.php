<?php

namespace App\Models;

use Crazymeeks\MongoDB\Model\AbstractModel as Model;

class AdminUser extends Model
{
    
    const ACTIVE = '1';

    protected $collection = 'admin_users';

    protected $fillable = [
        'role_id',
        'firstname',
        'lastname',
        'email',
        'username',
        'password',
        'status',
        'deleted_at',
    ];
}
