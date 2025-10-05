<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete System Setup - Sunfuel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .setup-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
        }
        .status-success {
            color: #28a745;
        }
        .status-error {
            color: #dc3545;
        }
        .status-warning {
            color: #ffc107;
        }
        .step {
            margin-bottom: 20px;
            padding: 15px;
            border-left: 4px solid #007bff;
            background-color: #f8f9fa;
        }
        .step.success {
            border-left-color: #28a745;
            background-color: #d4edda;
        }
        .step.error {
            border-left-color: #dc3545;
            background-color: #f8d7da;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="setup-container">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">🚀 Complete Sunfuel System Setup</h3>
                </div>
                <div class="card-body">
                    <p class="lead">This will set up the entire system: database, tables, and geographic data.</p>
                    
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setup_system'])) {
                        echo '<div class="card mt-3">';
                        echo '<div class="card-header"><h5>Setup Progress</h5></div>';
                        echo '<div class="card-body">';
                        
                        $steps = [];
                        $overall_success = true;
                        
                        // Step 1: Test Database Connection
                        echo '<div class="step">';
                        echo '<h6>Step 1: Testing Database Connection</h6>';
                        
                        $db_configs = [
                            ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'db' => 'sunfuel'],
                            ['host' => 'localhost', 'user' => 'root', 'pass' => '!Log19tan88', 'db' => 'sunfuel'],
                            ['host' => '127.0.0.1', 'user' => 'root', 'pass' => '', 'db' => 'sunfuel'],
                            ['host' => '127.0.0.1', 'user' => 'root', 'pass' => '!Log19tan88', 'db' => 'sunfuel']
                        ];
                        
                        $conn = null;
                        $working_config = null;
                        
                        foreach ($db_configs as $config) {
                            try {
                                $test_conn = new mysqli($config['host'], $config['user'], $config['pass']);
                                if (!$test_conn->connect_error) {
                                    $conn = $test_conn;
                                    $working_config = $config;
                                    echo "<p class='status-success'>✅ Connected to MySQL server</p>";
                                    break;
                                }
                            } catch (Exception $e) {
                                continue;
                            }
                        }
                        
                        if (!$conn) {
                            echo "<p class='status-error'>❌ Cannot connect to MySQL. Please check:</p>";
                            echo "<ul>";
                            echo "<li>XAMPP is running (Apache and MySQL)</li>";
                            echo "<li>MySQL service is started</li>";
                            echo "<li>Database credentials are correct</li>";
                            echo "</ul>";
                            $overall_success = false;
                        } else {
                            echo "<p class='status-success'>✅ Database connection successful</p>";
                        }
                        echo '</div>';
                        
                        if ($overall_success) {
                            // Step 2: Create Database
                            echo '<div class="step">';
                            echo '<h6>Step 2: Creating Database</h6>';
                            
                            try {
                                $conn->query("CREATE DATABASE IF NOT EXISTS sunfuel");
                                $conn->select_db("sunfuel");
                                echo "<p class='status-success'>✅ Database 'sunfuel' created/selected</p>";
                            } catch (Exception $e) {
                                echo "<p class='status-error'>❌ Error creating database: " . $e->getMessage() . "</p>";
                                $overall_success = false;
                            }
                            echo '</div>';
                            
                            if ($overall_success) {
                                // Step 3: Run Basic Migrations
                                echo '<div class="step">';
                                echo '<h6>Step 3: Running Basic Migrations</h6>';
                                
                                $migration_files = [
                                    'migrations/001_create_database.sql',
                                    'migrations/002_create_roles_table.sql',
                                    'migrations/003_create_permissions_table.sql',
                                    'migrations/004_create_features_table.sql',
                                    'migrations/005_create_users_table.sql',
                                    'migrations/006_create_role_permissions_table.sql',
                                    'migrations/007_create_role_modules_table.sql',
                                    'migrations/008_create_territories_table.sql',
                                    'migrations/009_create_territory_districts_table.sql',
                                    'migrations/010_create_fuelstation_table.sql'
                                ];
                                
                                $migration_success = true;
                                foreach ($migration_files as $file) {
                                    if (file_exists($file)) {
                                        try {
                                            $sql = file_get_contents($file);
                                            $statements = array_filter(array_map('trim', explode(';', $sql)));
                                            
                                            foreach ($statements as $statement) {
                                                if (!empty($statement) && !preg_match('/^--/', $statement)) {
                                                    $conn->query($statement);
                                                }
                                            }
                                            echo "<p class='status-success'>✅ Executed: " . basename($file) . "</p>";
                                        } catch (Exception $e) {
                                            if (strpos($e->getMessage(), 'already exists') === false) {
                                                echo "<p class='status-warning'>⚠️ " . basename($file) . ": " . $e->getMessage() . "</p>";
                                            } else {
                                                echo "<p class='status-success'>✅ " . basename($file) . " (already exists)</p>";
                                            }
                                        }
                                    }
                                }
                                echo '</div>';
                                
                                // Step 4: Create Geographic Tables
                                echo '<div class="step">';
                                echo '<h6>Step 4: Creating Geographic Tables</h6>';
                                
                                $geo_tables = [
                                    'districts' => "CREATE TABLE IF NOT EXISTS districts (
                                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        uuid CHAR(36) NOT NULL,
                                        districtCode VARCHAR(255) NOT NULL,
                                        districtName VARCHAR(255) NOT NULL,
                                        deleted_at TIMESTAMP NULL DEFAULT NULL,
                                        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                                        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
                                    
                                    'counties' => "CREATE TABLE IF NOT EXISTS counties (
                                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        uuid CHAR(36) NOT NULL,
                                        districtCode BIGINT UNSIGNED NOT NULL,
                                        countyCode VARCHAR(255) NOT NULL,
                                        countyName VARCHAR(255) NOT NULL,
                                        deleted_at TIMESTAMP NULL DEFAULT NULL,
                                        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                                        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
                                    
                                    'sub_counties' => "CREATE TABLE IF NOT EXISTS sub_counties (
                                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        uuid CHAR(36) NOT NULL,
                                        districtCode BIGINT UNSIGNED NOT NULL,
                                        countyCode BIGINT UNSIGNED NOT NULL,
                                        subCountyCode VARCHAR(255) NOT NULL,
                                        subCountyName VARCHAR(255) NOT NULL,
                                        deleted_at TIMESTAMP NULL DEFAULT NULL,
                                        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                                        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
                                    
                                    'parishes' => "CREATE TABLE IF NOT EXISTS parishes (
                                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        uuid CHAR(36) NOT NULL,
                                        districtCode BIGINT UNSIGNED NOT NULL,
                                        countyCode BIGINT UNSIGNED NOT NULL,
                                        subCountyCode BIGINT UNSIGNED NOT NULL,
                                        parishCode VARCHAR(255) NOT NULL,
                                        parishName VARCHAR(255) NOT NULL,
                                        deleted_at TIMESTAMP NULL DEFAULT NULL,
                                        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                                        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
                                    
                                    'villages' => "CREATE TABLE IF NOT EXISTS villages (
                                        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        uuid CHAR(36) NOT NULL,
                                        districtCode BIGINT UNSIGNED NOT NULL,
                                        countyCode BIGINT UNSIGNED NOT NULL,
                                        subCountyCode BIGINT UNSIGNED NOT NULL,
                                        parishCode BIGINT UNSIGNED NOT NULL,
                                        villageCode VARCHAR(255) NOT NULL,
                                        villageName VARCHAR(255) NOT NULL,
                                        deleted_at TIMESTAMP NULL DEFAULT NULL,
                                        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                                        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
                                ];
                                
                                foreach ($geo_tables as $table => $sql) {
                                    try {
                                        $conn->query($sql);
                                        echo "<p class='status-success'>✅ Created table: $table</p>";
                                    } catch (Exception $e) {
                                        if (strpos($e->getMessage(), 'already exists') !== false) {
                                            echo "<p class='status-success'>✅ Table $table already exists</p>";
                                        } else {
                                            echo "<p class='status-error'>❌ Error creating $table: " . $e->getMessage() . "</p>";
                                        }
                                    }
                                }
                                echo '</div>';
                                
                                // Step 5: Import Geographic Data
                                echo '<div class="step">';
                                echo '<h6>Step 5: Importing Geographic Data</h6>';
                                
                                $sqlFile = '/Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp/u367101322_uganda.sql';
                                
                                if (file_exists($sqlFile)) {
                                    echo "<p class='status-success'>✅ Found SQL file with real data</p>";
                                    
                                    $sqlContent = file_get_contents($sqlFile);
                                    
                                    // Import districts
                                    if (preg_match('/INSERT INTO `districts`[^;]+;/', $sqlContent, $matches)) {
                                        try {
                                            $districtsSQL = str_replace('INSERT INTO `districts`', 'INSERT INTO districts', $matches[0]);
                                            $conn->query("DELETE FROM districts");
                                            $conn->query($districtsSQL);
                                            echo "<p class='status-success'>✅ Imported districts data</p>";
                                        } catch (Exception $e) {
                                            echo "<p class='status-warning'>⚠️ Districts: " . $e->getMessage() . "</p>";
                                        }
                                    }
                                    
                                    // Import counties
                                    if (preg_match('/INSERT INTO `counties`[^;]+;/', $sqlContent, $matches)) {
                                        try {
                                            $countiesSQL = str_replace('INSERT INTO `counties`', 'INSERT INTO counties', $matches[0]);
                                            $conn->query("DELETE FROM counties");
                                            $conn->query($countiesSQL);
                                            echo "<p class='status-success'>✅ Imported counties data</p>";
                                        } catch (Exception $e) {
                                            echo "<p class='status-warning'>⚠️ Counties: " . $e->getMessage() . "</p>";
                                        }
                                    }
                                    
                                    // Import sub_counties
                                    if (preg_match('/INSERT INTO `sub_counties`[^;]+;/', $sqlContent, $matches)) {
                                        try {
                                            $subCountiesSQL = str_replace('INSERT INTO `sub_counties`', 'INSERT INTO sub_counties', $matches[0]);
                                            $conn->query("DELETE FROM sub_counties");
                                            $conn->query($subCountiesSQL);
                                            echo "<p class='status-success'>✅ Imported sub_counties data</p>";
                                        } catch (Exception $e) {
                                            echo "<p class='status-warning'>⚠️ Sub Counties: " . $e->getMessage() . "</p>";
                                        }
                                    }
                                    
                                    // Import parishes
                                    if (preg_match('/INSERT INTO `parishes`[^;]+;/', $sqlContent, $matches)) {
                                        try {
                                            $parishesSQL = str_replace('INSERT INTO `parishes`', 'INSERT INTO parishes', $matches[0]);
                                            $conn->query("DELETE FROM parishes");
                                            $conn->query($parishesSQL);
                                            echo "<p class='status-success'>✅ Imported parishes data</p>";
                                        } catch (Exception $e) {
                                            echo "<p class='status-warning'>⚠️ Parishes: " . $e->getMessage() . "</p>";
                                        }
                                    }
                                    
                                    // Import villages
                                    if (preg_match('/INSERT INTO `villages`[^;]+;/', $sqlContent, $matches)) {
                                        try {
                                            $villagesSQL = str_replace('INSERT INTO `villages`', 'INSERT INTO villages', $matches[0]);
                                            $conn->query("DELETE FROM villages");
                                            $conn->query($villagesSQL);
                                            echo "<p class='status-success'>✅ Imported villages data</p>";
                                        } catch (Exception $e) {
                                            echo "<p class='status-warning'>⚠️ Villages: " . $e->getMessage() . "</p>";
                                        }
                                    }
                                } else {
                                    echo "<p class='status-error'>❌ SQL file not found: $sqlFile</p>";
                                }
                                echo '</div>';
                                
                                // Step 6: Update Territory Districts
                                echo '<div class="step">';
                                echo '<h6>Step 6: Updating Territory Districts</h6>';
                                
                                try {
                                    $conn->query("DELETE FROM territory_districts");
                                    
                                    $result = $conn->query("SELECT id, districtName FROM districts");
                                    $count = 0;
                                    while ($row = $result->fetch_assoc()) {
                                        $stmt = $conn->prepare("INSERT INTO territory_districts (territoryId, districtName) VALUES (1, ?)");
                                        $stmt->bind_param("s", $row['districtName']);
                                        $stmt->execute();
                                        $count++;
                                    }
                                    echo "<p class='status-success'>✅ Updated territory_districts with $count districts</p>";
                                } catch (Exception $e) {
                                    echo "<p class='status-error'>❌ Error updating territory_districts: " . $e->getMessage() . "</p>";
                                }
                                echo '</div>';
                                
                                // Final Status
                                echo '<div class="step success">';
                                echo '<h6>🎉 Setup Complete!</h6>';
                                echo '<p>Your Sunfuel system is now ready with real Uganda geographic data.</p>';
                                echo '<p><a href="views/fuelstation/create.php" class="btn btn-success" target="_blank">Test Fuel Station Form</a></p>';
                                echo '</div>';
                            }
                        }
                        
                        if ($conn) {
                            $conn->close();
                        }
                        
                        echo '</div>';
                        echo '</div>';
                    } else {
                    ?>
                    
                    <div class="alert alert-info">
                        <strong>This setup will:</strong>
                        <ul class="mb-0">
                            <li>Test database connection with multiple configurations</li>
                            <li>Create the sunfuel database if it doesn't exist</li>
                            <li>Run all basic migrations</li>
                            <li>Create geographic tables with proper structure</li>
                            <li>Import real Uganda data from your SQL file</li>
                            <li>Update territory_districts for the form</li>
                        </ul>
                    </div>
                    
                    <form method="POST">
                        <div class="d-grid gap-2">
                            <button type="submit" name="setup_system" class="btn btn-primary btn-lg">
                                🚀 Setup Complete System
                            </button>
                        </div>
                    </form>
                    
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
