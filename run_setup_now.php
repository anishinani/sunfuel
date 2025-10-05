<?php
/**
 * Direct Setup - No Web Interface
 * This script directly sets up the system without waiting for user input
 */

echo "🚀 Setting up Sunfuel system with real Uganda data...\n\n";

// Test database connection
$configs = [
    ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'db' => 'sunfuel'],
    ['host' => 'localhost', 'user' => 'root', 'pass' => '!Log19tan88', 'db' => 'sunfuel'],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => '', 'db' => 'sunfuel'],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => '!Log19tan88', 'db' => 'sunfuel']
];

$conn = null;
$working_config = null;

echo "Step 1: Testing database connection...\n";
foreach ($configs as $config) {
    try {
        $test_conn = new mysqli($config['host'], $config['user'], $config['pass']);
        if (!$test_conn->connect_error) {
            $conn = $test_conn;
            $working_config = $config;
            echo "✅ Connected to MySQL server\n";
            break;
        }
    } catch (Exception $e) {
        continue;
    }
}

if (!$conn) {
    echo "❌ Cannot connect to MySQL. Please check XAMPP is running.\n";
    exit;
}

// Create database
echo "\nStep 2: Creating database...\n";
try {
    $conn->query("CREATE DATABASE IF NOT EXISTS sunfuel");
    $conn->select_db("sunfuel");
    echo "✅ Database 'sunfuel' created/selected\n";
} catch (Exception $e) {
    echo "❌ Error creating database: " . $e->getMessage() . "\n";
    exit;
}

// Create basic tables
echo "\nStep 3: Creating basic tables...\n";
$basic_tables = [
    'territories' => "CREATE TABLE IF NOT EXISTS territories (
        territoryId INT AUTO_INCREMENT PRIMARY KEY,
        territoryName VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    
    'territory_districts' => "CREATE TABLE IF NOT EXISTS territory_districts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        territoryId INT NOT NULL,
        districtName VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (territoryId) REFERENCES territories(territoryId)
    )"
];

foreach ($basic_tables as $table => $sql) {
    try {
        $conn->query($sql);
        echo "✅ Created table: $table\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "✅ Table $table already exists\n";
        } else {
            echo "⚠️ $table: " . $e->getMessage() . "\n";
        }
    }
}

// Create geographic tables
echo "\nStep 4: Creating geographic tables...\n";
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
        echo "✅ Created table: $table\n";
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'already exists') !== false) {
            echo "✅ Table $table already exists\n";
        } else {
            echo "⚠️ $table: " . $e->getMessage() . "\n";
        }
    }
}

// Import data from SQL file
echo "\nStep 5: Importing real Uganda data...\n";
$sqlFile = '/Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp/u367101322_uganda.sql';

if (file_exists($sqlFile)) {
    echo "✅ Found SQL file with real data\n";
    
    $sqlContent = file_get_contents($sqlFile);
    
    // Import districts
    if (preg_match('/INSERT INTO `districts`[^;]+;/', $sqlContent, $matches)) {
        try {
            $districtsSQL = str_replace('INSERT INTO `districts`', 'INSERT INTO districts', $matches[0]);
            $conn->query("DELETE FROM districts");
            $conn->query($districtsSQL);
            echo "✅ Imported districts data\n";
        } catch (Exception $e) {
            echo "⚠️ Districts: " . $e->getMessage() . "\n";
        }
    }
    
    // Import counties
    if (preg_match('/INSERT INTO `counties`[^;]+;/', $sqlContent, $matches)) {
        try {
            $countiesSQL = str_replace('INSERT INTO `counties`', 'INSERT INTO counties', $matches[0]);
            $conn->query("DELETE FROM counties");
            $conn->query($countiesSQL);
            echo "✅ Imported counties data\n";
        } catch (Exception $e) {
            echo "⚠️ Counties: " . $e->getMessage() . "\n";
        }
    }
    
    // Import sub_counties
    if (preg_match('/INSERT INTO `sub_counties`[^;]+;/', $sqlContent, $matches)) {
        try {
            $subCountiesSQL = str_replace('INSERT INTO `sub_counties`', 'INSERT INTO sub_counties', $matches[0]);
            $conn->query("DELETE FROM sub_counties");
            $conn->query($subCountiesSQL);
            echo "✅ Imported sub_counties data\n";
        } catch (Exception $e) {
            echo "⚠️ Sub Counties: " . $e->getMessage() . "\n";
        }
    }
    
    // Import parishes
    if (preg_match('/INSERT INTO `parishes`[^;]+;/', $sqlContent, $matches)) {
        try {
            $parishesSQL = str_replace('INSERT INTO `parishes`', 'INSERT INTO parishes', $matches[0]);
            $conn->query("DELETE FROM parishes");
            $conn->query($parishesSQL);
            echo "✅ Imported parishes data\n";
        } catch (Exception $e) {
            echo "⚠️ Parishes: " . $e->getMessage() . "\n";
        }
    }
    
    // Import villages
    if (preg_match('/INSERT INTO `villages`[^;]+;/', $sqlContent, $matches)) {
        try {
            $villagesSQL = str_replace('INSERT INTO `villages`', 'INSERT INTO villages', $matches[0]);
            $conn->query("DELETE FROM villages");
            $conn->query($villagesSQL);
            echo "✅ Imported villages data\n";
        } catch (Exception $e) {
            echo "⚠️ Villages: " . $e->getMessage() . "\n";
        }
    }
} else {
    echo "❌ SQL file not found: $sqlFile\n";
}

// Update territory_districts
echo "\nStep 6: Updating territory_districts for the form...\n";
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
    echo "✅ Updated territory_districts with $count districts\n";
} catch (Exception $e) {
    echo "❌ Error updating territory_districts: " . $e->getMessage() . "\n";
}

// Final summary
echo "\n🎉 Setup Complete!\n";
echo "Your Sunfuel system is now ready with real Uganda geographic data.\n";
echo "Test your fuel station form at: http://127.0.0.1/sunfuel/views/fuelstation/create.php\n";

$conn->close();
?>
