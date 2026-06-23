<?php

require_once __DIR__ . '/dbaccess.php';

class IntegrationSettings extends DbAccess
{
    public function get(string $key, string $default = ''): string
    {
        $rows = $this->select('integration_settings', ['setting_value'], ['setting_key' => $key]);
        if (empty($rows) || $rows[0]['setting_value'] === null) {
            return $default;
        }

        return (string) $rows[0]['setting_value'];
    }

    public function set(string $key, string $value): bool
    {
        $existing = $this->select('integration_settings', ['id'], ['setting_key' => $key]);

        if (!empty($existing)) {
            return (bool) $this->update(
                'integration_settings',
                ['setting_value' => $value],
                ['setting_key' => $key]
            );
        }

        return (bool) $this->insert('integration_settings', [
            'setting_key' => $key,
            'setting_value' => $value,
        ]);
    }

    public function getMany(array $keys): array
    {
        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $this->get($key);
        }

        return $values;
    }

    public function isSmsEnabled(): bool
    {
        return $this->get('sms_enabled', '1') === '1';
    }

    public function getSsentezoBaseUrl(): string
    {
        return $this->get('ssentezo_environment', 'live') === 'sandbox'
            ? 'https://devwallet.ssentezo.com/api'
            : 'https://wallet.ssentezo.com/api';
    }

    public function getSsentezoAuthHeader(): string
    {
        $user = $this->get('ssentezo_api_user');
        $key = $this->get('ssentezo_api_key');

        if ($user === '' || $key === '') {
            return '';
        }

        return 'Basic ' . base64_encode($user . ':' . $key);
    }
}
