<?php

require_once __DIR__ . '/IntegrationSettings.php';

/**
 * Central payment service — Ssentezo Wallet only.
 * Credentials are loaded from integration_settings via IntegrationSettings.
 */
class SsentezoWallet
{
    private IntegrationSettings $settings;

    public function __construct(?IntegrationSettings $settings = null)
    {
        $this->settings = $settings ?? new IntegrationSettings();
    }

    public function isConfigured(): bool
    {
        return $this->settings->getSsentezoAuthHeader() !== '';
    }

    public function formatMsisdn(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone);

        if (str_starts_with($phone, '256')) {
            return $phone;
        }

        if (str_starts_with($phone, '0')) {
            return '256' . substr($phone, 1);
        }

        return '256' . $phone;
    }

    public function collectMoney(
        string $externalReference,
        string $msisdn,
        float $amount,
        string $reason,
        array $options = []
    ): array {
        $payload = [
            'externalReference' => $externalReference,
            'msisdn' => $this->formatMsisdn($msisdn),
            'amount' => (int) round($amount),
            'currency' => $options['currency'] ?? 'UGX',
            'reason' => $reason,
        ];

        if (!empty($options['name'])) {
            $payload['name'] = $options['name'];
        }
        if (!empty($options['success_callback'])) {
            $payload['success_callback'] = $options['success_callback'];
        }
        if (!empty($options['failure_callback'])) {
            $payload['failure_callback'] = $options['failure_callback'];
        }

        return $this->post('/deposit', $payload);
    }

    public function withdrawMoney(
        string $externalReference,
        string $msisdn,
        float $amount,
        string $reason,
        array $options = []
    ): array {
        $payload = [
            'externalReference' => $externalReference,
            'msisdn' => $this->formatMsisdn($msisdn),
            'amount' => (int) round($amount),
            'currency' => $options['currency'] ?? 'UGX',
            'reason' => $reason,
        ];

        if (!empty($options['name'])) {
            $payload['name'] = $options['name'];
        }
        if (!empty($options['success_callback'])) {
            $payload['success_callback'] = $options['success_callback'];
        }
        if (!empty($options['failure_callback'])) {
            $payload['failure_callback'] = $options['failure_callback'];
        }

        return $this->post('/withdraw', $payload);
    }

    public function getTransactionStatus(string $externalReference): array
    {
        $result = $this->request(
            'POST',
            '/get_status/' . rawurlencode($externalReference)
        );

        if (!$result['success']) {
            return $result;
        }

        $data = $result['data'];

        return array_merge($result, [
            'status' => $data['status']
                ?? ($data['data']['transactionStatus'] ?? ($data['data']['status'] ?? null)),
            'message' => $data['message'] ?? ($data['response'] ?? null),
        ]);
    }

    public function getBalance(): array
    {
        return $this->post('/acc_balance', ['currency' => 'UGX']);
    }

    private function post(string $path, array $payload = []): array
    {
        return $this->request('POST', $path, $payload);
    }

    private function request(string $method, string $path, ?array $payload = null): array
    {
        $auth = $this->settings->getSsentezoAuthHeader();
        if ($auth === '') {
            return ['success' => false, 'message' => 'Ssentezo Wallet credentials are not configured'];
        }

        $url = rtrim($this->settings->getSsentezoBaseUrl(), '/') . $path;
        $headers = [
            'Authorization: ' . $auth,
            'Accept: application/json',
        ];

        $curl = curl_init();
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30,
        ];

        if ($payload !== null) {
            $options[CURLOPT_POSTFIELDS] = json_encode($payload);
            $headers[] = 'Content-Type: application/json';
            $options[CURLOPT_HTTPHEADER] = $headers;
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($error) {
            return ['success' => false, 'message' => $error];
        }

        $data = json_decode($response, true);
        if (!is_array($data)) {
            return ['success' => false, 'message' => 'Invalid response from Ssentezo Wallet', 'raw' => $response];
        }

        $apiOk = ($data['response'] ?? '') === 'OK';

        return [
            'success' => $httpCode >= 200 && $httpCode < 300 && $apiOk,
            'http_code' => $httpCode,
            'data' => $data,
            'transactionStatus' => $data['data']['transactionStatus'] ?? null,
            'walletReference' => $data['data']['ssentezoWalletReference'] ?? null,
            'financialTransactionId' => $data['data']['financialTransactionId'] ?? null,
            'message' => $data['error']['message'] ?? ($data['message'] ?? null),
        ];
    }
}
