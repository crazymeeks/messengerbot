<?php

namespace Payment\Tests;

use Payment\DragonPay;
use Crazymeeks\Foundation\PaymentGateway\Parameters;
use Crazymeeks\Foundation\PaymentGateway\DragonPay\Token;
use Crazymeeks\Foundation\PaymentGateway\Dragonpay as CrazymeeksDragonpay;

class DragonPayTest extends \Tests\TestCase
{
    private $dragonpay;
    private $token;
    private $parameters;

    public function setUp(): void
    {
        parent::setUp();

        $this->dragonpay = \Mockery::mock(CrazymeeksDragonpay::class)->makePartial();
        $this->token = new Token('the-encrypted-token');
        $this->parameters = \Mockery::mock(Parameters::class)->makePartial();
        $this->parameters->shouldReceive('query')
                         ->andReturn('mode=1&amount=200');
    }

    public function testGenerateLink()
    {
        $parameters = [
            'txnid' => 'TXNID', # Varchar(40) A unique id identifying this specific transaction from the merchant site
            'amount' => 1, # Numeric(12,2) The amount to get from the end-user (XXXX.XX)
            'ccy' => 'PHP', # Char(3) The currency of the amount
            'description' => 'Test', # Varchar(128) A brief description of what the payment is for
            'email' => 'some@merchant.ph', # Varchar(40) email address of customer
            'param1' => 'param1', # Varchar(80) [OPTIONAL] value that will be posted back to the merchant url when completed
            'param2' => 'param2', # Varchar(80) [OPTIONAL] value that will be posted back to the merchant url when completed

        ];
        $this->dragonpay->shouldReceive('getToken')
                        ->with($parameters)
                        ->andSet('parameters', $this->parameters)
                        ->andReturn($this->token);

        $this->dragonpay->shouldReceive('filterPaymentChannel')
                        ->with(\Mockery::any())
                        ->andReturnSelf();

        $this->dragonpay->shouldReceive('getPaymentUrl')
                        ->andReturn('https://test.dragonpay.com');
        
        $dragonPay = new DragonPay($this->dragonpay);

        $paymentLink = $dragonPay->getLink($parameters);

        $this->assertEquals('https://test.dragonpay.com?mode=1&amount=200', $paymentLink);

    }

}