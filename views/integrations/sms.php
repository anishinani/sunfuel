<?php

require_once '../../utils/session.php';

if (!can('view-sms-settings')) {
    $_SESSION['warning'] = 'UnAuthorized Operation';
    header('Location:../dashboard/');
    die;
}

require_once '../../utils/dbaccess.php';
require_once '../../utils/IntegrationSettings.php';
require_once '../../utils/sms.php';

$settings = new IntegrationSettings();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings->set('sms_api_key', trim($_POST['sms_api_key'] ?? ''));
    $settings->set('sms_api_url', trim($_POST['sms_api_url'] ?? 'https://sms.thinkxcloud.com/api/send-message'));
    $settings->set('sms_bulk_api_url', trim($_POST['sms_bulk_api_url'] ?? 'https://sms.thinkxcloud.com/api/send-bulk-message'));
    $settings->set('sms_enabled', isset($_POST['sms_enabled']) ? '1' : '0');

    if (!empty($_POST['test_number']) && !empty($_POST['test_message'])) {
        $sms = new infobip($settings);
        $result = $sms->sendsms('SUNFUEL', $_POST['test_number'], $_POST['test_message']);
        $_SESSION[$result ? 'success' : 'error'] = $result
            ? 'Test SMS sent successfully.'
            : 'Test SMS failed. Check your API key and phone number.';
    } else {
        $_SESSION['success'] = 'SMS settings saved successfully.';
    }

    header('Location:./sms.php');
    exit;
}

include_once '../templates/SecurePageHeader.php';
include_once '../templates/Components.php';

$config = $settings->getMany([
    'sms_api_key',
    'sms_api_url',
    'sms_bulk_api_url',
    'sms_enabled',
]);

$balance = (new infobip($settings))->getCreditBalance();
$maskedKey = $config['sms_api_key'] !== ''
    ? substr($config['sms_api_key'], 0, 4) . str_repeat('*', max(0, strlen($config['sms_api_key']) - 8)) . substr($config['sms_api_key'], -4)
    : '';

startContent();
breadCrumbs([
    'title' => 'SMS Settings',
    'sub_title' => 'Thinkx SMS',
    'previous' => 'Dashboard',
    'previous_action' => '../dashboard/',
]);
?>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Thinkx SMS Configuration</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Configure the <a href="https://sms.thinkxcloud.com/documentation" target="_blank" rel="noopener">Thinkx SMS API</a>.
                    Generate your API key from the Thinkx SMS dashboard under API Credentials.
                </p>

                <form method="POST">
                    <div class="form-group">
                        <label for="sms_api_key">API Key</label>
                        <input type="password" class="form-control" id="sms_api_key" name="sms_api_key"
                               value="<?= htmlspecialchars($config['sms_api_key']) ?>"
                               placeholder="Enter Thinkx SMS API key">
                        <?php if ($maskedKey): ?>
                            <small class="form-text text-muted">Current key: <?= htmlspecialchars($maskedKey) ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="sms_api_url">Send Message URL</label>
                        <input type="url" class="form-control" id="sms_api_url" name="sms_api_url"
                               value="<?= htmlspecialchars($config['sms_api_url']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="sms_bulk_api_url">Bulk Message URL</label>
                        <input type="url" class="form-control" id="sms_bulk_api_url" name="sms_bulk_api_url"
                               value="<?= htmlspecialchars($config['sms_bulk_api_url']) ?>">
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="sms_enabled" name="sms_enabled"
                            <?= $config['sms_enabled'] === '1' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="sms_enabled">Enable SMS sending</label>
                    </div>

                    <hr>
                    <h5>Test SMS</h5>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="test_number">Test phone number</label>
                            <input type="text" class="form-control" id="test_number" name="test_number"
                                   placeholder="e.g. 0700123456">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="test_message">Test message</label>
                            <input type="text" class="form-control" id="test_message" name="test_message"
                                   value="Sunfuel test message">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Settings</button>
                    <button type="submit" name="send_test" value="1" class="btn btn-outline-secondary"
                            onclick="document.getElementById('test_number').required=true;document.getElementById('test_message').required=true;">
                        Save &amp; Send Test
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">Account Status</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($balance['success'])): ?>
                    <p><strong>Credit balance:</strong>
                        <?= htmlspecialchars($balance['data']['message_credit_balance'] ?? 'N/A') ?></p>
                <?php else: ?>
                    <p class="text-muted mb-0">
                        <?= htmlspecialchars($balance['message'] ?? 'Save a valid API key to check balance.') ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
endContent();
include_once '../templates/footer.php';
endPage();
