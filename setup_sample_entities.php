<?php
/**
 * Create one fuel station, stage, fuel agent, and boda user for local testing.
 * Usage: php setup_sample_entities.php
 */

require_once __DIR__ . '/utils/dbaccess.php';

$db = new DbAccess();
$conn = $db->conn;

function println(string $message): void
{
    echo $message . PHP_EOL;
}

function tableExists(mysqli $conn, string $table): bool
{
    $table = $conn->real_escape_string($table);
    $result = $conn->query("SHOW TABLES LIKE '{$table}'");
    return $result && $result->num_rows > 0;
}

function ensureFuelAgentTable(mysqli $conn): void
{
    if (tableExists($conn, 'fuelagent')) {
        return;
    }

    $sql = file_get_contents(__DIR__ . '/migrations/030_create_fuelagent_table.sql');
    if ($sql === false) {
        throw new RuntimeException('Could not read fuelagent migration file.');
    }

    if (!$conn->multi_query($sql)) {
        throw new RuntimeException('Failed to create fuelagent table: ' . $conn->error);
    }

    while ($conn->more_results() && $conn->next_result()) {
        // drain multi-query results
    }

    println('Created missing fuelagent table.');
}

try {
    ensureFuelAgentTable($conn);

    $existingStation = $db->select('fuelstation', ['fuelStationId'], ['fuelStationName' => 'SUNFUEL TEST STATION']);
    if (!empty($existingStation)) {
        println('Sample entities already exist. Skipping creation.');
        println('Fuel station ID: ' . $existingStation[0]['fuelStationId']);
        exit(0);
    }

    $stationId = $db->insert('fuelstation', [
        'fuelStationName' => 'SUNFUEL TEST STATION',
        'fuelStationAddress' => 'KIRA ROAD, WAKISO',
        'fuelStationContactPerson' => 'JAMES MUKASA',
        'fuelStationContactPhone' => '0700123456',
        'fuelStationStatus' => 1,
        'NIN' => 'CM12345678ABCD',
        'frontIDPhoto' => 'sample_front.jpg',
        'backIDPhoto' => 'sample_back.jpg',
        'bankName' => 'STANBIC BANK',
        'bankBranch' => 'KAMPALA ROAD',
        'AccName' => 'SUNFUEL TEST STATION',
        'AccNumber' => '1000123456789',
        'merchantCode' => '000001',
        'districtCode' => '2',
        'countyCode' => '240',
        'subCountyCode' => '1',
        'parishCode' => '4',
        'villageCode' => '1',
        'currentFloat' => 500000.00,
        'minFloat' => 100000.00,
        'maxFloat' => 1000000.00,
    ]);

    if (!$stationId) {
        throw new RuntimeException('Failed to create fuel station.');
    }

    $stageId = $db->insert('stage', [
        'stageName' => 'KIRA STAGE',
        'stageLocation' => 'KIRA TOWN, WAKISO',
        'fuelStationId' => $stationId,
        'stageStatus' => 1,
    ]);

    if (!$stageId) {
        throw new RuntimeException('Failed to create stage.');
    }

    $agentId = $db->insert('fuelagent', [
        'fuelAgentName' => 'PETER SSEKALONGO',
        'fuelAgentPhoneNumber' => '0700987654',
        'fuelAgentNIN' => 'CM98765432WXYZ',
        'stationId' => $stationId,
        'frontIDPhoto' => 'agent_front.jpg',
        'backIDPhoto' => 'agent_back.jpg',
        'anotherPhoneNumber' => '0700987655',
        'status' => 1,
    ]);

    if (!$agentId) {
        throw new RuntimeException('Failed to create fuel agent.');
    }

    $bodaUserId = $db->insert('bodauser', [
        'bodaUserName' => 'RONALD OKELLO',
        'bodaUserNIN' => 'CM11223344ABCD',
        'bodaUserBodaNumber' => 'UBK 123A',
        'bodaUserPhoneNumber' => '0700555123',
        'bodaUserFrontPhoto' => 'boda_front.jpg',
        'bodaUserBackPhoto' => 'boda_back.jpg',
        'bodaUserRole' => 'BodaUser',
        'alternativePhotoNumber' => '0700555124',
        'fuelStationId' => $stationId,
        'stageId' => $stageId,
        'packageId' => 1,
        'maxDailyLoan' => 15000.00,
        'canBorrowToday' => 1,
        'bodaUserStatus' => 1,
    ]);

    if (!$bodaUserId) {
        throw new RuntimeException('Failed to create boda user.');
    }

    $existingFloat = $db->select('fuel_station_float', ['floatId'], ['fuelStationId' => $stationId]);
    if (empty($existingFloat)) {
        $db->insert('fuel_station_float', [
            'fuelStationId' => $stationId,
            'currentFloat' => 500000.00,
            'minFloat' => 100000.00,
            'maxFloat' => 1000000.00,
        ]);
    }

    println('Sample entities created successfully.');
    println('');
    println('Fuel Station: SUNFUEL TEST STATION (ID ' . $stationId . ')');
    println('Stage: KIRA STAGE (ID ' . $stageId . ')');
    println('Fuel Agent: PETER SSEKALONGO (ID ' . $agentId . ')');
    println('  Phone: 0700987654 (active, can log in to agent portal)');
    println('Boda User: RONALD OKELLO (ID ' . $bodaUserId . ')');
    println('  Phone: 0700555123');
    println('  Boda Number: UBK 123A');
    println('  Package: Basic Package');
    println('');
    println('Agent portal login: http://localhost/sunfuel/fuel_agent_login.php');
    println('  Username: 0700987654');
    println('  Password: 0700987654');
} catch (Throwable $e) {
    fwrite(STDERR, 'Error: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
