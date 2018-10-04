<?php

namespace Jetfuel\Xmtpay;

use Jetfuel\Xmtpay\HttpClient\GuzzleHttpClient;
use Jetfuel\Xmtpay\Traits\ConvertMoney;

class Payment
{
    use ConvertMoney;

    const BASE_API_URL = 'http://xmtpay.vip/';

      /**
     * @var string
     */
    protected $orgId;

    /**
     * @var string
     */
    protected $merchantId;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $baseApiUrl;

    /**
     * @var \Jetfuel\Xmtpay\HttpClient\HttpClientInterface
     */
    protected $httpClient;

    /**
     * Payment constructor.
     *
     * @param string $orgId
     * @param string $merchantId
     * @param string $secretKey
     * @param null|string $baseApiUrl
     */
    protected function __construct($merchantId, $secretKey, $baseApiUrl = null)
    {
        //$this->orgId = $orgId;
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;
        $this->baseApiUrl = $baseApiUrl === null ? self::BASE_API_URL : $baseApiUrl;

        $this->httpClient = new GuzzleHttpClient($this->baseApiUrl);
    }

    /**
     * Sign request payload.
     *
     * @param array $payload
     * @return array
     */
    protected function signPayload(array $payload)
    {
        $payload['mert_no'] = $this->merchantId;
        $payload['order_ip'] = '192.168.1.1';
        $payload['order_time'] = time();

        $payload['sign'] = Signature::generate($payload, $this->secretKey);

        return $payload;
    }
}
