<?php
/**
 * Setup Demo Fuel Agent for Testing
 */

require_once './utils/dbaccess.php';

class DemoFuelAgentSetup {
    private $db;

    public function __construct() {
        $this->db = new DbAccess();
    }

    public function createDemoAgent() {
        echo "<h2>Setting up Demo Fuel Agent</h2>\n";
        echo "<div style='font-family: monospace; line-height: 1.6;'>\n";

        try {
            // Check if demo agent already exists
            $existingAgent = $this->db->select('fuelagent', ['fuelAgentId'], ['fuelAgentPhoneNumber' => '256700123456']);
            
            if (!empty($existingAgent)) {
                echo "<p style='color: orange;'>Demo fuel agent already exists.</p>\n";
                echo "<p><strong>Login Credentials:</strong></p>\n";
                echo "<ul>\n";
                echo "<li>Phone Number: 256700123456</li>\n";
                echo "<li>Password: 256700123456</li>\n";
                echo "</ul>\n";
                echo "<p><a href='fuel_agent_login.php' class='btn btn-primary'>Login to Agent Portal</a></p>\n";
                return;
            }

            // Get first fuel station
            $stations = $this->db->select('fuelstation', ['fuelStationId', 'fuelStationName'], ['fuelStationStatus' => 1]);
            
            if (empty($stations)) {
                echo "<p style='color: red;'>No active fuel stations found. Please create a fuel station first.</p>\n";
                return;
            }

            $stationId = $stations[0]['fuelStationId'];
            $stationName = $stations[0]['fuelStationName'];

            // Create demo fuel agent
            $agentId = $this->db->insert('fuelagent', [
                'fuelAgentName' => 'DEMO AGENT',
                'fuelAgentPhoneNumber' => '256700123456',
                'fuelAgentNIN' => 'DEMO123456789',
                'stationId' => $stationId,
                'frontIDPhoto' => 'demo_front.jpg',
                'backIDPhoto' => 'demo_back.jpg',
                'anotherPhoneNumber' => '256700123457',
                'status' => 1
            ]);

            if ($agentId) {
                echo "<p style='color: green;'>✓ Demo fuel agent created successfully</p>\n";
                echo "<p><strong>Agent Details:</strong></p>\n";
                echo "<ul>\n";
                echo "<li>Name: DEMO AGENT</li>\n";
                echo "<li>Phone: 256700123456</li>\n";
                echo "<li>Station: {$stationName}</li>\n";
                echo "<li>Status: Active</li>\n";
                echo "</ul>\n";
                
                echo "<p><strong>Login Credentials:</strong></p>\n";
                echo "<ul>\n";
                echo "<li>Phone Number: 256700123456</li>\n";
                echo "<li>Password: 256700123456</li>\n";
                echo "</ul>\n";
                
                echo "<p><a href='fuel_agent_login.php' class='btn btn-primary'>Login to Agent Portal</a></p>\n";
                
                // Create demo boda user for testing
                $this->createDemoBodaUser($stationId);
                
            } else {
                echo "<p style='color: red;'>Failed to create demo fuel agent</p>\n";
            }

        } catch (Exception $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>\n";
        }

        echo "</div>\n";
    }

    private function createDemoBodaUser($stationId) {
        echo "<h3>Creating Demo Boda User</h3>\n";
        
        // Get first stage for the station
        $stages = $this->db->select('stage', ['stageId', 'stageName'], ['fuelStationId' => $stationId]);
        
        if (empty($stages)) {
            echo "<p style='color: orange;'>No stages found for this station. Creating a demo stage...</p>\n";
            
            $stageId = $this->db->insert('stage', [
                'stageName' => 'DEMO STAGE',
                'stageLocation' => 'Demo Location',
                'fuelStationId' => $stationId,
                'territoryId' => 1,
                'stageStatus' => 1
            ]);
        } else {
            $stageId = $stages[0]['stageId'];
        }

        // Get first package
        $packages = $this->db->select('package', ['packageId'], ['packageStatus' => 1]);
        $packageId = !empty($packages) ? $packages[0]['packageId'] : null;

        // Create demo boda user
        $bodaUserId = $this->db->insert('bodauser', [
            'bodaUserName' => 'DEMO BODA USER',
            'bodaUserNIN' => 'DEMO987654321',
            'bodaUserBodaNumber' => 'DEMO001',
            'bodaUserPhoneNumber' => '256700654321',
            'bodaUserFrontPhoto' => 'demo_front.jpg',
            'bodaUserBackPhoto' => 'demo_back.jpg',
            'bodaUserRole' => 'BodaUser',
            'alternativePhotoNumber' => '256700654322',
            'fuelStationId' => $stationId,
            'stageId' => $stageId,
            'packageId' => $packageId,
            'maxDailyLoan' => 15000.00,
            'canBorrowToday' => 1,
            'bodaUserStatus' => 1
        ]);

        if ($bodaUserId) {
            echo "<p style='color: green;'>✓ Demo boda user created successfully</p>\n";
            echo "<p><strong>Boda User Details:</strong></p>\n";
            echo "<ul>\n";
            echo "<li>Name: DEMO BODA USER</li>\n";
            echo "<li>Phone: 256700654321</li>\n";
            echo "<li>Boda Number: DEMO001</li>\n";
            echo "<li>Status: Active</li>\n";
            echo "</ul>\n";
        }

        // Update fuel station float
        $this->db->update('fuelstation', [
            'currentFloat' => 500000.00,
            'minFloat' => 100000.00,
            'maxFloat' => 1000000.00
        ], ['fuelStationId' => $stationId]);

        echo "<p style='color: green;'>✓ Fuel station float updated</p>\n";
    }
}

// Run setup if accessed directly
if (isset($_GET['run']) && $_GET['run'] === 'setup') {
    $setup = new DemoFuelAgentSetup();
    $setup->createDemoAgent();
} else {
    echo "<h2>Demo Fuel Agent Setup</h2>";
    echo "<p>This will create a demo fuel agent for testing the activation interface.</p>";
    echo "<p><a href='?run=setup' class='btn btn-primary'>Create Demo Agent</a></p>";
    
    echo "<h3>What this will create:</h3>";
    echo "<ul>";
    echo "<li>Demo fuel agent with login credentials</li>";
    echo "<li>Demo boda user for testing</li>";
    echo "<li>Demo stage if needed</li>";
    echo "<li>Updated fuel station float</li>";
    echo "</ul>";
    
    echo "<h3>After setup, you can:</h3>";
    echo "<ul>";
    echo "<li>Login to the fuel agent portal</li>";
    echo "<li>Test activation code entry</li>";
    echo "<li>View station float status</li>";
    echo "<li>See recent activations</li>";
    echo "</ul>";
}
?>
