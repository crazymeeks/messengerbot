<?php

namespace FbMessengerBot\Payment;

use Crazymeeks\Foundation\PaymentGateway\Dragonpay as DP;
use Crazymeeks\Foundation\PaymentGateway\Dragonpay\Token;

class Dragonpay
{

    protected $dp;

    protected $txn_id;

    protected $amount;

    protected $currency = 'PHP';
    
    protected $description;

    protected $email;

    public function __construct(DP $dp)
    {
        $this->dp = $dp;
    }

    public function setTxnId(string $txn_id)
    {

        $this->txn_id = $txn_id;

        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    public function setCurrency(string $currency = 'PHP')
    {
        $this->currency = $currency;

        return $this;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getLink()
    {
        $this->dp->filterPaymentChannel(DP::ONLINE_BANK);

        $token = $this->dp->getToken([
            'txnid' => $this->txn_id,
            'amount' => $this->amount,
            'ccy' => $this->currency,
            'description' => $this->description,
            'email' => $this->email,
        ]);
        
        if ($token instanceof Token) {
            $queryString = $this->dp->parameters->query();
        
            $payment_url = $this->dp->getPaymentUrl() . '?' . $queryString;

            return $payment_url;
        }

        throw new \FbMessengerBot\Exceptions\BotResponseException('Error while generating dragonpay payment link.');

    }
}