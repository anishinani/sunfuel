<?php
/**
 * Setup Demo Data for USSD Simulator Testing
 */

require_once './utils/dbaccess.php';

class USSDDemoDataSetup {
    private $db;

    public function __construct() {
        $this->db = new DbAccess();
    }

    public function createDemoData() {
        echo "<h2>Setting up USSD Simulator Demo Data</h2>\n";
        echo "<div style='font-family: monospace; line-height: 1.6;'>\n";

        try {
            // Create demo fuel station if not exists
            $stationId = $this->createDemoFuelStation();
            
            // Create demo stage if not exists
            $stageId = $this->createDemoStage($stationId);
            
            // Create demo package if not exists
            $packageId = $this->createDemoPackage();
            
            // Create demo boda users
            $this->createDemoBodaUsers($stationId, $stageId, $packageId);
            
            // Setup fuel station float
            $this->setupFuelStationFloat($stationId);
            
            echo "<h3 style='color: green;'>✓ USSD Demo Data Setup Complete!</h3>\n";
            
            echo "<h4>Demo Users Created:</h4>\n";
            echo "<ul>\n";
            echo "<li><strong>256700654321</strong> - Demo Boda User (Active, can borrow)</li>\n";
            echo "<li><strong>256700111111</strong> - Test User 1 (Active, can borrow)</li>\n";
            echo "<li><strong>256700222222</strong> - Test User 2 (Active, can borrow)</li>\n";
            echo "</ul>\n";
            
            echo "<h4>Next Steps:</h4>\n";
            echo "<ol>\n";
            echo "<li><a href='ussd_simulator.php' target='_blank'>Open USSD Simulator</a></li>\n";
            echo "<li>Select a demo user phone number</li>\n";
            echo "<li>Test the fuel loan workflow:</li>\n";
            echo "<ul>\n";
            echo "<li>Request fuel (option 1)</li>\n";
            echo "<li>Check balance (option 3)</li>\n";
            echo "<li>Pay loan (option 2)</li>\n";
            echo "</ul>\n";
            echo "<li><a href='fuel_agent_login.php' target='_blank'>Test Agent Portal</a> with activation codes</li>\n";
            echo "</ol>\n";

        } catch (Exception $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>\n";
        }

        echo "</div>\n";
    }

    private function createDemoFuelStation() {
        // Check if demo station exists
        $existingStation = $this->db->select('fuelstation', ['fuelStationId'], ['fuelStationName' => 'DEMO FUEL STATION']);
        
        if (!empty($existingStation)) {
            echo "<p style='color: orange;'>Demo fuel station already exists.</p>\n";
            return $existingStation[0]['fuelStationId'];
        }

        // Create demo fuel station
        $stationId = $this->db->insert('fuelstation', [
            'fuelStationName' => 'DEMO FUEL STATION',
            'fuelStationAddress' => 'Demo Address, Kampala',
            'fuelStationContactPerson' => 'DEMO MANAGER',
            'fuelStationContactPhone' => '256700000000',
            'fuelStationStatus' => 1,
            'NIN' => 'DEMO000000001',
            'frontIDPhoto' => 'demo_front.jpg',
            'backIDPhoto' => 'demo_back.jpg',
            'bankName' => 'DEMO BANK',
            'bankBranch' => 'DEMO BRANCH',
            'AccName' => 'DEMO ACCOUNT',
            'AccNumber' => '1234567890',
            'merchantCode' => '000001',
            'districtCode' => '001',
            'countyCode' => '001',
            'subCountyCode' => '001',
            'parishCode' => '001',
            'villageCode' => '001',
            'currentFloat' => 1000000.00,
            'minFloat' => 100000.00,
            'maxFloat' => 2000000.00
        ]);

        echo "<p style='color: green;'>✓ Demo fuel station created (ID: {$stationId})</p>\n";
        return $stationId;
    }

    private function createDemoStage($stationId) {
        // Check if demo stage exists
        $existingStage = $this->db->select('stage', ['stageId'], ['stageName' => 'DEMO STAGE']);
        
        if (!empty($existingStage)) {
            echo "<p style='color: orange;'>Demo stage already exists.</p>\n";
            return $existingStage[0]['stageId'];
        }

        // Create demo stage
        $stageId = $this->db->insert('stage', [
            'stageName' => 'DEMO STAGE',
            'stageLocation' => 'Demo Stage Location',
            'fuelStationId' => $stationId,
            'territoryId' => 1,
            'stageStatus' => 1,
            'chairmanId' => null
        ]);

        echo "<p style='color: green;'>✓ Demo stage created (ID: {$stageId})</p>\n";
        return $stageId;
    }

    private function createDemoPackage() {
        // Check if demo package exists
        $existingPackage = $this->db->select('package', ['packageId'], ['packageName' => 'Demo Package']);
        
        if (!empty($existingPackage)) {
            echo "<p style='color: orange;'>Demo package already exists.</p>\n";
            return $existingPackage[0]['packageId'];
        }

        // Create demo package
        $packageId = $this->db->insert('package', [
            'packageName' => 'Demo Package',
            'packageAmount' => 15000.00,
            'packageDescription' => 'Demo package for testing',
            'packageStatus' => 1,
            'maxLoanAmount' => 15000.00,
            'interestRate' => 5.00,
            'borrowStartTime' => '06:00:00',
            'borrowEndTime' => '12:00:00',
            'paymentStartTime' => '17:00:00',
            'paymentEndTime' => '23:59:59'
        ]);

        echo "<p style='color: green;'>✓ Demo package created (ID: {$packageId})</p>\n";
        return $packageId;
    }

    private function createDemoBodaUsers($stationId, $stageId, $packageId) {
        $demoUsers = [
            [
                'name' => 'DEMO BODA USER',
                'phone' => '256700654321',
                'nin' => 'DEMO987654321',
                'bodaNumber' => 'DEMO001',
                'alternativePhone' => '256700654322'
            ],
            [
                'name' => 'TEST USER ONE',
                'phone' => '256700111111',
                'nin' => 'DEMO111111111',
                'bodaNumber' => 'TEST001',
                'alternativePhone' => '256700111112'
            ],
            [
                'name' => 'TEST USER TWO',
                'phone' => '256700222222',
                'nin' => 'DEMO222222222',
                'bodaNumber' => 'TEST002',
                'alternativePhone' => '256700222223'
            ]
        ];

        foreach ($demoUsers as $userData) {
            // Check if user already exists
            $existingUser = $this->db->select('bodauser', ['bodaUserId'], ['bodaUserPhoneNumber' => $userData['phone']]);
            
            if (!empty($existingUser)) {
                echo "<p style='color: orange;'>User {$userData['phone']} already exists.</p>\n";
                continue;
            }

            // Create boda user
            $userId = $this->db->insert('bodauser', [
                'bodaUserName' => $userData['name'],
                'bodaUserNIN' => $userData['nin'],
                'bodaUserBodaNumber' => $userData['bodaNumber'],
                'bodaUserPhoneNumber' => $userData['phone'],
                'bodaUserFrontPhoto' => 'demo_front.jpg',
                'bodaUserBackPhoto' => 'demo_back.jpg',
                'bodaUserRole' => 'BodaUser',
                'alternativePhotoNumber' => $userData['alternativePhone'],
                'fuelStationId' => $stationId,
                'stageId' => $stageId,
                'packageId' => $packageId,
                'maxDailyLoan' => 15000.00,
                'canBorrowToday' => 1,
                'lastLoanDate' => null,
                'bodaUserStatus' => 1
            ]);

            echo "<p style='color: green;'>✓ Demo user created: {$userData['name']} ({$userData['phone']})</p>\n";
        }
    }

    private function setupFuelStationFloat($stationId) {
        // Update fuel station float
        $this->db->update('fuelstation', [
            'currentFloat' => 1000000.00,
            'minFloat' => 100000.00,
            'maxFloat' => 2000000.00
        ], ['fuelStationId' => $stationId]);

        // Create float tracking record
        $existingFloat = $this->db->select('fuel_station_float', ['floatId'], ['fuelStationId' => $stationId]);
        
        if (empty($existingFloat)) {
            $this->db->insert('fuel_station_float', [
                'fuelStationId' => $stationId,
                'currentFloat' => 1000000.00,
                'minFloat' => 100000.00,
                'maxFloat' => 2000000.00
            ]);
        }

        echo "<p style='color: green;'>✓ Fuel station float configured (1,000,000 UGX)</p>\n";
    }
}

// Run setup if accessed directly
if (isset($_GET['run']) && $_GET['run'] === 'setup') {
    $setup = new USSDDemoDataSetup();
    $setup->createDemoData();
} else {
    echo "<h2>USSD Simulator Demo Data Setup</h2>";
    echo "<p>This will create demo data for testing the USSD simulator.</p>";
    echo "<p><a href='?run=setup' class='btn btn-primary'>Create Demo Data</a></p>";
    
    echo "<h3>What this will create:</h3>";
    echo "<ul>";
    echo "<li>Demo fuel station</li>";
    echo "<li>Demo stage</li>";
    echo "<li>Demo package</li>";
    echo "<li>3 demo boda users with different phone numbers</li>";
    echo "<li>Fuel station float configuration</li>";
    echo "</ul>";
    
    echo "<h3>Demo Users:</h3>";
    echo "<ul>";
    echo "<li><strong>256700654321</strong> - Main demo user</li>";
    echo "<li><strong>256700111111</strong> - Test user 1</li>";
    echo "<li><strong>256700222222</strong> - Test user 2</li>";
    echo "</ul>";
    
    echo "<h3>After setup, test:</h3>";
    echo "<ol>";
    echo "<li><a href='ussd_simulator.php'>USSD Simulator</a> - Test fuel loan workflow</li>";
    echo "<li><a href='fuel_agent_login.php'>Agent Portal</a> - Test activation codes</li>";
    echo "<li><a href='setup_demo_fuel_agent.php'>Create Demo Agent</a> - For agent portal testing</li>";
    echo "</ol>";
}
?>
