<?php

namespace Payment;

use Crazymeeks\Foundation\PaymentGateway\DragonPay\Token;
use Crazymeeks\Foundation\PaymentGateway\Dragonpay as CrazymeeksDragonpay;

class DragonPay
{

    private $dragonpay;

    public function __construct(CrazymeeksDragonpay $dragonpay)
    {
        $this->dragonpay = $dragonpay;
    }

    /**
     * Get dragonpay payment link
     *
     * @param array $parameters
     * 
     * @return string
     */
    public function getLink(array $parameters)
    {
        
        $token = $this->dragonpay->getToken($parameters);
        
        if ($token instanceof Token) {
            $queryString = $this->dragonpay->parameters->query();
            
            return $this->dragonpay->getPaymentUrl() . '?' . $queryString;
        }
    }
}