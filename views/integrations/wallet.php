<?php

require_once '../../utils/session.php';

if (!can('view-wallet-settings')) {
    $_SESSION['warning'] = 'UnAuthorized Operation';
    header('Location:../dashboard/');
    die;
}

require_once '../../utils/dbaccess.php';
require_once '../../utils/IntegrationSettings.php';
require_once '../../utils/SsentezoWallet.php';

$settings = new IntegrationSettings();
$wallet = new SsentezoWallet($settings);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings->set('ssentezo_api_user', trim($_POST['ssentezo_api_user'] ?? ''));
    $settings->set('ssentezo_api_key', trim($_POST['ssentezo_api_key'] ?? ''));
    $settings->set('ssentezo_environment', ($_POST['ssentezo_environment'] ?? 'live') === 'sandbox' ? 'sandbox' : 'live');

    $wallet = new SsentezoWallet($settings);

    if (isset($_POST['check_balance'])) {
        $balance = $wallet->getBalance();
        $_SESSION[$balance['success'] ? 'success' : 'error'] = $balance['success']
            ? 'Wallet connection successful.'
            : ($balance['message'] ?? 'Could not connect to Ssentezo Wallet.');
    } else {
        $_SESSION['success'] = 'Wallet settings saved successfully.';
    }

    header('Location:./wallet.php');
    exit;
}

include_once '../templates/SecurePageHeader.php';
include_once '../templates/Components.php';

$config = $settings->getMany([
    'ssentezo_api_user',
    'ssentezo_api_key',
    'ssentezo_environment',
]);

$balance = $wallet->isConfigured() ? $wallet->getBalance() : ['success' => false, 'message' => 'Credentials not configured'];
$maskedKey = $config['ssentezo_api_key'] !== ''
    ? substr($config['ssentezo_api_key'], 0, 4) . str_repeat('*', max(0, strlen($config['ssentezo_api_key']) - 8)) . substr($config['ssentezo_api_key'], -4)
    : '';

startContent();
breadCrumbs([
    'title' => 'Wallet Settings',
    'sub_title' => 'Ssentezo Wallet',
    'previous' => 'Dashboard',
    'previous_action' => '../dashboard/',
]);
?>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Ssentezo Wallet Configuration</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Configure the <a href="https://wallet.ssentezo.com/documentation" target="_blank" rel="noopener">Ssentezo Wallet API</a>.
                    API credentials are generated from the API Access menu in your wallet account.
                </p>

                <form method="POST">
                    <div class="form-group">
                        <label for="ssentezo_api_user">API User</label>
                        <input type="text" class="form-control" id="ssentezo_api_user" name="ssentezo_api_user"
                               value="<?= htmlspecialchars($config['ssentezo_api_user']) ?>"
                               placeholder="Ssentezo API user">
                    </div>

                    <div class="form-group">
                        <label for="ssentezo_api_key">API Key</label>
                        <input type="password" class="form-control" id="ssentezo_api_key" name="ssentezo_api_key"
                               value="<?= htmlspecialchars($config['ssentezo_api_key']) ?>"
                               placeholder="Ssentezo API key">
                        <?php if ($maskedKey): ?>
                            <small class="form-text text-muted">Current key: <?= htmlspecialchars($maskedKey) ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="ssentezo_environment">Environment</label>
                        <select class="form-control" id="ssentezo_environment" name="ssentezo_environment">
                            <option value="live" <?= $config['ssentezo_environment'] === 'live' ? 'selected' : '' ?>>
                                Live (wallet.ssentezo.com)
                            </option>
                            <option value="sandbox" <?= $config['ssentezo_environment'] === 'sandbox' ? 'selected' : '' ?>>
                                Sandbox (devwallet.ssentezo.com)
                            </option>
                        </select>
                    </div>

                    <div class="alert alert-light border">
                        <strong>Base URL:</strong> <?= htmlspecialchars($settings->getSsentezoBaseUrl()) ?>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Settings</button>
                    <button type="submit" name="check_balance" value="1" class="btn btn-outline-secondary">
                        Save &amp; Test Connection
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">Wallet Status</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($balance['success'])): ?>
                    <pre class="small mb-0"><?= htmlspecialchars(json_encode($balance['data'], JSON_PRETTY_PRINT)) ?></pre>
                <?php else: ?>
                    <p class="text-muted mb-0">
                        <?= htmlspecialchars($balance['message'] ?? 'Save credentials and test the connection.') ?>
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
