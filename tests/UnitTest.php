<?php

namespace Test;

use Faker\Factory;
use Jetfuel\Xmtpay\BankPayment;
use Jetfuel\Xmtpay\Constants\Bank;
use Jetfuel\Xmtpay\Constants\Channel;
use Jetfuel\Xmtpay\DigitalPayment;
use Jetfuel\Xmtpay\TradeQuery;
use Jetfuel\Xmtpay\Traits\NotifyWebhook;
use Jetfuel\Xmtpay\BalanceQuery;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    private $orgId;
    private $merchantId;
    private $secretKey;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->merchantId = getenv('MERCHANT_ID');
        $this->secretKey = getenv('SECRET_KEY');
    }

    public function testDigitalPaymentOrder()
    {
        $faker = Factory::create();
        $tradeNo = date('YmdHis').rand(1000, 9999);
        $channel = Channel::ALIPAY;
        $amount = 10.5;
        $notifyUrl = 'http://a.a.com';

        $payment = new DigitalPayment($this->merchantId, $this->secretKey);
        $result = $payment->order($tradeNo, $channel, $amount, $notifyUrl);
        var_dump($result);
        $this->assertEquals('ok',$result['state']);
        
        return $tradeNo;
    }

    public function testNotifyWebhookVerifyNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'state'          => 'ok',
            'mert_no'        => 'PG201808001',
            'out_trade_no'   => '2018091015000011',
            'order_no'       => '2018091015000011',
            'amount'         => '100',
            'pay_amount'     => '100',
            'notify_time'    => '1538616757',
            'sign'           => 'E0F6AACF4D88640AFB1969FBCDFA747B',
        ];
        
        $this->assertTrue($mock->verifyNotifyPayload($payload, $this->secretKey));
    }

    public function testNotifyWebhookParseNotifyPayload()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $payload = [
            'state'          => 'ok',
            'mert_no'        => 'PG201808001',
            'out_trade_no'   => '2018091015000011',
            'order_no'       => '2018091015000011',
            'amount'         => '100',
            'pay_amount'     => '100',
            'notify_time'    => '1538616757',
            'sign'           => 'E0F6AACF4D88640AFB1969FBCDFA747B',
        ];

         $this->assertEquals([
            'state'          => 'ok',
            'mert_no'        => 'PG201808001',
            'out_trade_no'   => '2018091015000011',
            'order_no'       => '2018091015000011',
            'amount'         => '100',
            'pay_amount'     => '100',
            'notify_time'    => '1538616757',
            'sign'           => 'E0F6AACF4D88640AFB1969FBCDFA747B',
         ], $mock->parseNotifyPayload($payload, $this->secretKey));
    }

    public function testNotifyWebhookSuccessNotifyResponse()
    {
        $mock = $this->getMockForTrait(NotifyWebhook::class);

        $this->assertEquals('ok', $mock->successNotifyResponse());
    }

}
