<?php

namespace FbMessengerBot\Tests\Unit\Payment;

use FbMessengerBot\Payment\Dragonpay;
use Crazymeeks\Foundation\PaymentGateway\Dragonpay as DP;
use Crazymeeks\Foundation\PaymentGateway\Dragonpay\Token;
use Crazymeeks\Foundation\PaymentGateway\Parameters;

class DragonpayTest extends \Tests\TestCase
{



    private $dp;
    private $dragonpay;
    private $dragonpay_token;

    public function setUp(): void
    {
        parent::setUp();

        $this->dragonpay = \Mockery::mock(DP::class);
        $this->dragonpay_token = \Mockery::mock(Token::class);

        $this->dragonpay->shouldReceive('filterPaymentChannel')
                        ->with(\Mockery::any())
                        ->set('parameters', new Parameters($this->dragonpay))
                        ->andReturnSelf();
        $this->dragonpay->shouldReceive('getToken')
                        ->with(\Mockery::any())
                        ->andReturn($this->dragonpay_token);
        $this->dragonpay->shouldReceive('getPaymentChannel')
                        ->andReturn(DP::ONLINE_BANK);
        $this->dragonpay->shouldReceive('getPaymentUrl')
                        ->andReturn('https://test.dragonpay.ph/Pay.aspx');

    }

    public function testGeneratePaymentLink()
    {

        
        $dp = new Dragonpay($this->dragonpay);
        $paylink = $dp->setTxnId('somerandomstring')
                            ->setAmount(10)
                            ->setCurrency('PHP')
                            ->setDescription('Some Description')
                            ->setEmail('jefferson.claud@nuworks.ph')
                            ->getLink();
        $this->assertEquals('https://test.dragonpay.ph/Pay.aspx?mode=1', $paylink);

        
    }
}