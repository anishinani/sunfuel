<?php
/**
 * Verify DataTables server-side endpoints return valid JSON.
 * Usage: php test_datatables_endpoints.php
 */

$endpoints = [
    'views/bodauser/serverside.php',
    'views/bodauser/serversideActiveBodaUser.php',
    'views/bodauser/serversideInactiveBodaUser.php',
    'views/bodauser/serversideDefaulted.php',
    'views/dashboard/serverside.php',
    'views/dashboard/serverside_today.php',
    'views/deposits/serverside.php',
    'views/fuelagent/serverside.php',
    'views/fuelstation/serverside.php',
    'views/fuelstation/serversideactivefuelstation.php',
    'views/fuelstation/serversideinactivefuelstation.php',
    'views/fuelstation/serverside_territory.php?territory=1',
    'views/loans/serverside.php',
    'views/packages/serverside.php',
    'views/payments/serverside.php',
    'views/roles/serverside.php',
    'views/stage/serverside.php',
    'views/stage/serversideActiveStage.php?name=SUNFUEL%20TEST%20STATION&table=activefuelstation',
    'views/stage/serverside/stagebodaserverside.php?stagename=KIRA%20STAGE&table=activebodausers',
    'views/stage/Territory_serverside.php?territory=1',
    'views/territories/serverside.php',
    'views/users/serverside.php',
    'views/users/userserverside.php',
];

$post = 'draw=1&start=0&length=10&search[value]=&order[0][column]=0&order[0][dir]=desc';
$ok = 0;
$fail = 0;

foreach ($endpoints as $ep) {
    $url = 'http://localhost/sunfuel/' . $ep;
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $post,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 15,
    ]);
    $body = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $status = 'FAIL';
    $detail = '';

    if ($http !== 200) {
        $detail = "HTTP {$http}";
    } else {
        $trim = ltrim((string) $body);
        if ($trim === '' || ($trim[0] !== '{' && $trim[0] !== '[')) {
            $detail = substr(preg_replace('/\s+/', ' ', strip_tags($body)), 0, 140);
        } else {
            $json = json_decode($body, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $detail = json_last_error_msg();
            } elseif (!isset($json['draw'], $json['recordsTotal'], $json['recordsFiltered'], $json['data'])) {
                $detail = 'missing DataTables keys';
            } elseif (!empty($json['error'])) {
                $detail = $json['error'];
            } else {
                $status = 'OK';
                $detail = 'rows=' . count($json['data']) . ' total=' . $json['recordsTotal'];
                $ok++;
            }
        }
    }

    if ($status === 'FAIL') {
        $fail++;
    }

    printf("%-4s %-70s %s\n", $status, $ep, $detail);
}

echo "\nSummary: {$ok}/" . count($endpoints) . " OK, {$fail} failed\n";
exit($fail > 0 ? 1 : 0);
