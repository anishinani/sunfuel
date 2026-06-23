<?php

require_once __DIR__ . '/../../utils/dbaccess.php';
require_once __DIR__ . '/../../utils/SsentezoWallet.php';
require_once __DIR__ . '/../../utils/pin.php';

$wallet = new SsentezoWallet();
$pin = new pin();

if (!$wallet->isConfigured()) {
    die('Configure Ssentezo Wallet credentials under Integrations > Wallet Settings.');
}

$externalReference = 'SFTEST' . time() . $pin->randomkey(4);
$result = $wallet->collectMoney(
    $externalReference,
    '256759983853',
    1000,
    'Sunfuel payment test'
);

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);
