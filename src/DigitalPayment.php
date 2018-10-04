<?php

namespace Jetfuel\Xmtpay;

use Jetfuel\Xmtpay\Traits\ResultParser;
use Jetfuel\Xmtpay\Constants\Channel;

class DigitalPayment extends Payment
{
    use ResultParser;

    /**
     * DigitalPayment constructor.
     *
     * @param string $merchantId
     * @param string $secretKey
     * @param null|string $baseApiUrl
     */
    public function __construct($merchantId, $secretKey, $baseApiUrl = null)
    {
        parent::__construct($merchantId, $secretKey, $baseApiUrl);
    }

    /**
     * Create digital payment order.
     *
     * @param string $tradeNo
     * @param string $channel
     * @param float $amount
     * @param string $notifyUrl
     * @param string $returnUrl
     * @return array
     */
    public function order($tradeNo, $channel, $amount, $notifyUrl)
    {
        
        $payload = $this->signPayload([
            'out_trade_no'        => $tradeNo,
            'pay_type'            => $channel,
            'amount'              => $this->convertYuanToFen($amount),
            'notify_url'          => $notifyUrl,
        ]);

        return $this->parseResponse($this->httpClient->post('/guava/pay', $payload));
    }
}
