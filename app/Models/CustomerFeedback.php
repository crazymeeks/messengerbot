<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerFeedback extends Model
{
    

    protected $table = 'customer_feedback';

    protected $fillable = [
        'fb_id',
        'complete_name',
        'contact_number',
        'location',
        'product_location_purchase',
        'date_of_purchase',
        'production_code',
        'detailed_feedback',
        'previous_step_done',
    ];
}
