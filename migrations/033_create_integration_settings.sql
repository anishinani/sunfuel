-- Migration 033: Integration settings for SMS and Ssentezo Wallet

USE sunfuel;

CREATE TABLE IF NOT EXISTS integration_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO integration_settings (setting_key, setting_value) VALUES
('sms_api_key', ''),
('sms_api_url', 'https://sms.thinkxcloud.com/api/send-message'),
('sms_bulk_api_url', 'https://sms.thinkxcloud.com/api/send-bulk-message'),
('sms_enabled', '1'),
('ssentezo_api_user', ''),
('ssentezo_api_key', ''),
('ssentezo_environment', 'live')
ON DUPLICATE KEY UPDATE setting_key = setting_key;

INSERT INTO modules (id, name, description, icon) VALUES
(13, 'Integrations', 'SMS and payment gateway settings', 'fas fa-plug')
ON DUPLICATE KEY UPDATE name = VALUES(name), description = VALUES(description), icon = VALUES(icon);

INSERT INTO features (name, permission, action, module_id, featureName, description) VALUES
('SMS Settings', 'view-sms-settings', '/sunfuel/views/integrations/sms.php', 13, 'view-sms-settings', 'Manage Thinkx SMS API credentials'),
('Wallet Settings', 'view-wallet-settings', '/sunfuel/views/integrations/wallet.php', 13, 'view-wallet-settings', 'Manage Ssentezo Wallet API credentials')
ON DUPLICATE KEY UPDATE name = VALUES(name), action = VALUES(action), module_id = VALUES(module_id);

INSERT IGNORE INTO role_modules (role_id, module_id) VALUES (1, 13);

INSERT IGNORE INTO role_permissions (role_id, feature_id, permission, status)
SELECT 1, f.id, f.permission, 1
FROM features f
WHERE f.permission IN ('view-sms-settings', 'view-wallet-settings');
