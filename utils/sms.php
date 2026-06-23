<?php

error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

include_once __DIR__ . '/dbaccess.php';
require_once __DIR__ . '/IntegrationSettings.php';

/**
 * Central SMS service — Thinkx SMS only.
 * Credentials are loaded from integration_settings via IntegrationSettings.
 */
class SmsService
{
    private IntegrationSettings $settings;

    public function __construct(?IntegrationSettings $settings = null)
    {
        $this->settings = $settings ?? new IntegrationSettings();
    }

    public function formatMobileInternational($mobile)
    {
        $length = strlen($mobile);
        $m = '+256';
        if ($length == 13) {
            return $mobile;
        } elseif ($length == 12) {
            return '+' . $mobile;
        } elseif ($length == 10) {
            return $m . substr($mobile, 1);
        } elseif ($length == 9) {
            return $m . $mobile;
        }

        return $mobile;
    }

    public function sms_faster($message, $receivers = [], $status = 1, $username = 'SUNFUEL')
    {
        if ((int) $status !== 1 || empty($receivers)) {
            return false;
        }

        if (count($receivers) === 1) {
            return $this->sendsms($username, $receivers[0], $message);
        }

        return $this->sendBulk($receivers, $message);
    }

    public function sendsms($from, $to, $msg)
    {
        if (!$this->settings->isSmsEnabled()) {
            return false;
        }

        $apiKey = $this->settings->get('sms_api_key');
        if ($apiKey === '') {
            return false;
        }

        $number = $this->formatMobileInternational($to);
        $url = $this->settings->get('sms_api_url', 'https://sms.thinkxcloud.com/api/send-message');

        $payload = json_encode([
            'api_key' => $apiKey,
            'number' => $number,
            'message' => $msg,
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return false;
        }

        $decoded = json_decode($response, true);
        if (!is_array($decoded) || ($decoded['response'] ?? '') !== 'OK') {
            return false;
        }

        $this->logGatewayMessage($to, $msg, $decoded['data']['message_reference'] ?? null, 'SENT');

        return $decoded['data']['message_reference'] ?? true;
    }

    public function sendBulk(array $receivers, string $message)
    {
        if (!$this->settings->isSmsEnabled()) {
            return false;
        }

        $apiKey = $this->settings->get('sms_api_key');
        if ($apiKey === '') {
            return false;
        }

        $numbers = array_map([$this, 'formatMobileInternational'], $receivers);
        $url = $this->settings->get('sms_bulk_api_url', 'https://sms.thinkxcloud.com/api/send-bulk-message');

        $payload = json_encode([
            'api_key' => $apiKey,
            'numbers' => array_values($numbers),
            'message' => $message,
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return false;
        }

        $decoded = json_decode($response, true);
        if (!is_array($decoded) || ($decoded['response'] ?? '') !== 'OK') {
            return false;
        }

        return $decoded['data']['batch_number'] ?? true;
    }

    public function getCreditBalance(): array
    {
        $apiKey = $this->settings->get('sms_api_key');
        if ($apiKey === '') {
            return ['success' => false, 'message' => 'SMS API key is not configured'];
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://sms.thinkxcloud.com/api/message-credit-balance',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => ['api_key' => $apiKey],
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return ['success' => false, 'message' => $error];
        }

        $decoded = json_decode($response, true);

        return [
            'success' => is_array($decoded) && ($decoded['response'] ?? '') === 'OK',
            'data' => $decoded['data'] ?? [],
            'raw' => $decoded,
        ];
    }

    private function logGatewayMessage($to, $msg, $messageId, $status): void
    {
        try {
            $db = new DbAccess();
            if ($db->selectQuery("SHOW TABLES LIKE 'sms_gateway'")) {
                $db->insert('sms_gateway', [
                    'tel' => $to,
                    'message' => $msg,
                    'message_id' => $messageId,
                    'success_code' => 'OK',
                    'status' => $status,
                ]);
            }
        } catch (Throwable $e) {
            // Optional legacy log table.
        }
    }
}

/** @deprecated Use SmsService — kept for backward compatibility */
class_alias(SmsService::class, 'infobip');
