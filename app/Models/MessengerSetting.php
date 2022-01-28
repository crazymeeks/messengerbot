<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessengerSetting extends Model
{

    const MSGR_CONFIG_SETTINGS = 'messenger_settings';

    const PRIMARY_APP_ACCESS_TOKEN = 'messenger_primary_page_access_token';

    const SECONDARY_APP_ACCESS_TOKEN = 'messenger_secondary_page_access_token';
    
    protected $table = 'messenger_settings';

    protected $fillable = [
        'page_name',
        'page_id',
        'type',
        'primary_access_token',
        'secondary_access_token',
    ];

}
