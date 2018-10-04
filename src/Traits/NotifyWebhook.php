<?php

namespace Jetfuel\Xmtpay\Traits;

use Jetfuel\Xmtpay\Signature;

trait NotifyWebhook
{
    /**
     * Verify notify request's signature.
     *
     * @param array $payload
     * @param $secretKey
     * @return bool
     */
    public function verifyNotifyPayload(array $payload, $secretKey)
    {
        if (!isset($payload['sign'])) {
            return false;
        }

        $signature = $payload['sign'];

        unset($payload['sign']);
        
        return Signature::validate($payload, $secretKey, $signature);
    }

    /**
     * Verify notify request's signature and parse payload.
     *
     * @param array $payload
     * @param string $secretKey
     * @return array|null
     */
    public function parseNotifyPayload(array $payload, $secretKey)
    {
        if (!$this->verifyNotifyPayload($payload, $secretKey)) {
            return null;
        }
        
        $payload['amount'] = $this->convertFenToYuan($payload['amount']);
        
        return $payload;
    }

    /**
     * Response content for successful notify.
     *
     * @return string
     */
    public function successNotifyResponse()
    {
        return 'ok';
    }
}
