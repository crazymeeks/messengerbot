<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutCustomerInfo extends Model
{

    protected $table = 'checkout_customer_info';

    protected $fillable = [
        'fb_id',
        'name',
        'email_address',
        'mobile_number',
        'city',
        'delivery_address',
        'barangay',
        'payment_type',
        'previous_step_done',
    ];
    
}
