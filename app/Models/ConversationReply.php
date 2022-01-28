<?php

namespace App\Models;

use Crazymeeks\MongoDB\Model\AbstractModel as Model;

class ConversationReply extends Model
{
    
    protected $collection = 'conversation_reply';

    protected $fillable = [
        'chatter_id',
        'admin_user_id',
        'reply',
        'answered_by_admin',
        'stime',
    ];
}
