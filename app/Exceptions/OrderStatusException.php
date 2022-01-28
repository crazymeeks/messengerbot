<?php

namespace App\Exceptions;

class OrderStatusException extends \Exception
{
    public static function cannotUpdateOrderStatus()
    {
        return new static('Order cannot be updated! Order status may be updated if in Pending or Processing state.');
    }

    public static function cannotUpdateOrderPaymentStatus()
    {
        return new static('Order cannot be updated! Order payment status may be updated if in Pending or Processing state.');
    }


}