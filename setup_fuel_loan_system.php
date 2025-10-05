<?php
/**
 * Fuel Loan System Setup Script
 * This script sets up the fuel loan system components
 */

require_once './utils/dbaccess.php';

class FuelLoanSystemSetup {
    private $db;

    public function __construct() {
        $this->db = new DbAccess();
    }

    public function runSetup() {
        echo "<h2>SunFuel Fuel Loan System Setup</h2>\n";
        echo "<div style='font-family: monospace; line-height: 1.6;'>\n";

        try {
            // Step 1: Run database migrations
            echo "<h3>Step 1: Setting up database tables...</h3>\n";
            $this->setupDatabaseTables();
            echo "<p style='color: green;'>✓ Database tables created successfully</p>\n";

            // Step 2: Create default packages
            echo "<h3>Step 2: Creating default packages...</h3>\n";
            $this->createDefaultPackages();
            echo "<p style='color: green;'>✓ Default packages created</p>\n";

            // Step 3: Setup fuel station floats
            echo "<h3>Step 3: Setting up fuel station floats...</h3>\n";
            $this->setupFuelStationFloats();
            echo "<p style='color: green;'>✓ Fuel station floats configured</p>\n";

            // Step 4: Create admin user for fuel loan system
            echo "<h3>Step 4: Setting up admin access...</h3>\n";
            $this->setupAdminAccess();
            echo "<p style='color: green;'>✓ Admin access configured</p>\n";

            // Step 5: Test system components
            echo "<h3>Step 5: Testing system components...</h3>\n";
            $this->testSystemComponents();
            echo "<p style='color: green;'>✓ System components tested</p>\n";

            echo "<h3 style='color: green;'>✓ Fuel Loan System Setup Complete!</h3>\n";
            echo "<p><strong>Next Steps:</strong></p>\n";
            echo "<ul>\n";
            echo "<li>Configure USSD gateway integration</li>\n";
            echo "<li>Set up mobile money payment integration</li>\n";
            echo "<li>Configure SMS gateway settings</li>\n";
            echo "<li>Set up cron jobs for scheduled tasks</li>\n";
            echo "<li>Test the complete workflow</li>\n";
            echo "</ul>\n";

            echo "<p><strong>Access Points:</strong></p>\n";
            echo "<ul>\n";
            echo "<li>Admin Dashboard: <a href='views/dashboard/fuel_loan_dashboard.php'>Fuel Loan Dashboard</a></li>\n";
            echo "<li>Agent Portal: <a href='views/fuelagent/activation.php'>Fuel Activation Portal</a></li>\n";
            echo "<li>USSD Endpoint: <code>api/ussd.php</code></li>\n";
            echo "<li>Scheduler: <a href='jobs/fuel_loan_scheduler.php'>Fuel Loan Scheduler</a></li>\n";
            echo "</ul>\n";

        } catch (Exception $e) {
            echo "<p style='color: red;'>✗ Setup failed: " . $e->getMessage() . "</p>\n";
        }

        echo "</div>\n";
    }

    private function setupDatabaseTables() {
        // Read and execute the migration file
        $migrationFile = './migrations/027_create_fuel_loan_system.sql';
        if (!file_exists($migrationFile)) {
            throw new Exception("Migration file not found: $migrationFile");
        }

        $sql = file_get_contents($migrationFile);
        
        // Split SQL into individual statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement) && !preg_match('/^(--|\/\*)/', $statement)) {
                $this->db->conn->query($statement);
                if ($this->db->conn->error) {
                    echo "<p style='color: orange;'>Warning: " . $this->db->conn->error . "</p>\n";
                }
            }
        }
    }

    private function createDefaultPackages() {
        // Check if packages already exist
        $existingPackages = $this->db->select('package', ['packageId']);
        
        if (empty($existingPackages)) {
            // Create default packages
            $packages = [
                [
                    'packageName' => 'Basic Package',
                    'packageAmount' => 15000.00,
                    'maxLoanAmount' => 15000.00,
                    'interestRate' => 5.00,
                    'packageStatus' => 1
                ],
                [
                    'packageName' => 'Standard Package',
                    'packageAmount' => 25000.00,
                    'maxLoanAmount' => 25000.00,
                    'interestRate' => 4.50,
                    'packageStatus' => 1
                ],
                [
                    'packageName' => 'Premium Package',
                    'packageAmount' => 50000.00,
                    'maxLoanAmount' => 50000.00,
                    'interestRate' => 4.00,
                    'packageStatus' => 1
                ]
            ];

            foreach ($packages as $package) {
                $this->db->insert('package', $package);
            }
        }
    }

    private function setupFuelStationFloats() {
        // Get all fuel stations
        $stations = $this->db->select('fuelstation', ['fuelStationId', 'fuelStationName']);
        
        foreach ($stations as $station) {
            // Initialize fuel station float if not exists
            $existingFloat = $this->db->select('fuel_station_float', ['floatId'], ['fuelStationId' => $station['fuelStationId']]);
            
            if (empty($existingFloat)) {
                $this->db->insert('fuel_station_float', [
                    'fuelStationId' => $station['fuelStationId'],
                    'currentFloat' => 500000.00, // Default float amount
                    'minFloat' => 100000.00,
                    'maxFloat' => 1000000.00
                ]);
            }
        }
    }

    private function setupAdminAccess() {
        // Create admin role for fuel loan system if not exists
        $existingRole = $this->db->select('roles', ['roleId'], ['roleName' => 'Fuel Loan Admin']);
        
        if (empty($existingRole)) {
            $roleId = $this->db->insert('roles', [
                'roleName' => 'Fuel Loan Admin',
                'roleDescription' => 'Administrator for fuel loan system',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            // Assign permissions to the role
            $permissions = ['create', 'read', 'update', 'delete'];
            foreach ($permissions as $permission) {
                $this->db->insert('role_permissions', [
                    'roleId' => $roleId,
                    'permissionId' => $this->getPermissionId($permission)
                ]);
            }
        }
    }

    private function testSystemComponents() {
        // Test database connections
        $tables = ['fuel_activation_codes', 'fuel_loans', 'ussd_sessions', 'sms_logs', 'fuel_station_float'];
        
        foreach ($tables as $table) {
            $result = $this->db->selectQuery("SHOW TABLES LIKE '{$table}'");
            if (empty($result)) {
                throw new Exception("Table {$table} was not created properly");
            }
        }

        // Test SMS controller
        require_once './controllers/SMSController.php';
        $smsController = new SMSController();
        echo "<p>✓ SMS Controller loaded</p>\n";

        // Test Fuel Loan Controller
        require_once './controllers/FuelLoanController.php';
        $fuelLoanController = new FuelLoanController();
        echo "<p>✓ Fuel Loan Controller loaded</p>\n";

        // Test Loan Management Controller
        require_once './controllers/LoanManagementController.php';
        $loanController = new LoanManagementController();
        echo "<p>✓ Loan Management Controller loaded</p>\n";
    }

    private function getPermissionId($permissionName) {
        $permission = $this->db->select('permissions', ['permissionId'], ['permissionName' => $permissionName]);
        return !empty($permission) ? $permission[0]['permissionId'] : null;
    }
}

// Run setup if accessed directly
if (isset($_GET['run']) && $_GET['run'] === 'setup') {
    $setup = new FuelLoanSystemSetup();
    $setup->runSetup();
} else {
    echo "<h2>Fuel Loan System Setup</h2>";
    echo "<p>This script will set up the fuel loan system components.</p>";
    echo "<p><a href='?run=setup' class='btn btn-primary'>Run Setup</a></p>";
    
    echo "<h3>What this setup will do:</h3>";
    echo "<ul>";
    echo "<li>Create database tables for fuel loans, activation codes, and SMS logs</li>";
    echo "<li>Set up default loan packages</li>";
    echo "<li>Configure fuel station floats</li>";
    echo "<li>Create admin access roles</li>";
    echo "<li>Test all system components</li>";
    echo "</ul>";
    
    echo "<h3>Prerequisites:</h3>";
    echo "<ul>";
    echo "<li>Database connection must be working</li>";
    echo "<li>Basic SunFuel system must be installed</li>";
    echo "<li>User must have database admin privileges</li>";
    echo "</ul>";
}
?>
