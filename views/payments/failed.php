<?php

require_once __DIR__ . '/../../utils/dbaccess.php';

$dbAccess = new DbAccess();
$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    http_response_code(400);
    exit('Invalid callback payload');
}

$callback = $data['data'] ?? $data;
$externalReference = $callback['externalReference']
    ?? ($data['externalReference'] ?? ($_POST['failed_transaction_reference'] ?? null));

if (!$externalReference) {
    http_response_code(400);
    exit('Missing external reference');
}

$dbAccess->update('payments', [
    'status' => 'failed',
    'transactionStatus' => '0',
], [
    'external_ref' => $externalReference,
]);

$dbAccess->update('loan', ['status' => '1'], ['loanRef' => $externalReference]);

http_response_code(200);
echo 'OK';
