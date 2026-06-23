<?php

require_once __DIR__ . '/../../utils/dbaccess.php';
require_once __DIR__ . '/../../utils/sms.php';

$dbAccess = new DbAccess();
$sms = new SmsService();

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
    http_response_code(400);
    exit('Invalid callback payload');
}

$callback = $data['data'] ?? $data;
$externalReference = $callback['externalReference'] ?? ($data['externalReference'] ?? null);
$status = $callback['transactionStatus'] ?? ($data['status'] ?? null);
$financialTransactionId = $callback['financialTransactionId'] ?? ($data['financialTransactionId'] ?? null);

if (!$externalReference || $status !== 'SUCCEEDED') {
    http_response_code(200);
    exit('Ignored');
}

$date_time = new DateTime();

$dbAccess->update('payments', [
    'date_time' => $date_time->format('Y-m-d H:i:s'),
    'network_ref' => $financialTransactionId,
    'transactionStatus' => '1',
    'status' => 'completed',
], [
    'external_ref' => $externalReference,
]);

$dbAccess->update('loan', ['status' => '0'], ['loanRef' => $externalReference]);
$details = $dbAccess->select('payments', ['msisdn', 'amount'], ['external_ref' => $externalReference]);

if (!empty($details)) {
    $dbAccess->update('bodauser', ['bodaUserStatus' => '1'], [
        'bodaUserPhoneNumber' => formatPhoneNumber($details[0]['msisdn']),
    ]);

    $sms->sendsms(
        'Dear Customer',
        $sms->formatMobileInternational($details[0]['msisdn']),
        'Your payment of shs ' . $details[0]['amount'] . ' has been received successfully'
    );
}

http_response_code(200);
echo 'OK';

function formatPhoneNumber($msisdn)
{
    $msisdn = preg_replace('/\D+/', '', $msisdn);
    if (str_starts_with($msisdn, '256')) {
        return '0' . substr($msisdn, 3);
    }

    return $msisdn;
}
