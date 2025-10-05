<?php
/**
 * Import Real Geographic Data
 * This script imports the actual Uganda geographic data from the provided SQL file
 */

require_once 'utils/dbaccess.php';

echo "<h2>Importing Real Uganda Geographic Data</h2>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background-color:#f2f2f2;}</style>";

try {
    $dbAccess = new DbAccess();
    echo "<p class='success'>✅ Database connection successful</p>";
    
    // First, let's create the proper table structure that matches your data
    echo "<h3>Creating Tables with Correct Structure</h3>";
    
    // Create districts table (matching your data structure)
    $createDistricts = "
    CREATE TABLE IF NOT EXISTS districts (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        uuid CHAR(36) NOT NULL,
        districtCode VARCHAR(255) NOT NULL,
        districtName VARCHAR(255) NOT NULL,
        deleted_at TIMESTAMP NULL DEFAULT NULL,
        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    // Create counties table (matching your data structure)
    $createCounties = "
    CREATE TABLE IF NOT EXISTS counties (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        uuid CHAR(36) NOT NULL,
        districtCode BIGINT UNSIGNED NOT NULL,
        countyCode VARCHAR(255) NOT NULL,
        countyName VARCHAR(255) NOT NULL,
        deleted_at TIMESTAMP NULL DEFAULT NULL,
        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (districtCode) REFERENCES districts(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    // Create sub_counties table (matching your data structure)
    $createSubCounties = "
    CREATE TABLE IF NOT EXISTS sub_counties (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        uuid CHAR(36) NOT NULL,
        districtCode BIGINT UNSIGNED NOT NULL,
        countyCode BIGINT UNSIGNED NOT NULL,
        subCountyCode VARCHAR(255) NOT NULL,
        subCountyName VARCHAR(255) NOT NULL,
        deleted_at TIMESTAMP NULL DEFAULT NULL,
        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (districtCode) REFERENCES districts(id),
        FOREIGN KEY (countyCode) REFERENCES counties(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    // Create parishes table (matching your data structure)
    $createParishes = "
    CREATE TABLE IF NOT EXISTS parishes (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        uuid CHAR(36) NOT NULL,
        districtCode BIGINT UNSIGNED NOT NULL,
        countyCode BIGINT UNSIGNED NOT NULL,
        subCountyCode BIGINT UNSIGNED NOT NULL,
        parishCode VARCHAR(255) NOT NULL,
        parishName VARCHAR(255) NOT NULL,
        deleted_at TIMESTAMP NULL DEFAULT NULL,
        created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (districtCode) REFERENCES districts(id),
        FOREIGN KEY (countyCode) REFERENCES counties(id),
        FOREIGN KEY (subCountyCode) REFERENCES sub_counties(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    // Create villages table (matching your data structure)
    $createVillages = "
    CREATE TABLE IF NOT EXISTS villages (
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
        updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (districtCode) REFERENCES districts(id),
        FOREIGN KEY (countyCode) REFERENCES counties(id),
        FOREIGN KEY (subCountyCode) REFERENCES sub_counties(id),
        FOREIGN KEY (parishCode) REFERENCES parishes(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    // Execute table creation
    $tables = [
        'districts' => $createDistricts,
        'counties' => $createCounties,
        'sub_counties' => $createSubCounties,
        'parishes' => $createParishes,
        'villages' => $createVillages
    ];
    
    foreach ($tables as $tableName => $sql) {
        try {
            $dbAccess->execute($sql);
            echo "<p class='success'>✅ Created table: $tableName</p>";
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'already exists') !== false) {
                echo "<p class='warning'>⚠️ Table $tableName already exists</p>";
            } else {
                echo "<p class='error'>❌ Error creating $tableName: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    // Now let's read and import the actual data
    echo "<h3>Importing Data from SQL File</h3>";
    
    $sqlFile = '/Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp/u367101322_uganda.sql';
    
    if (!file_exists($sqlFile)) {
        echo "<p class='error'>❌ SQL file not found: $sqlFile</p>";
        exit;
    }
    
    $sqlContent = file_get_contents($sqlFile);
    
    // Extract districts data
    if (preg_match('/INSERT INTO `districts`[^;]+;/', $sqlContent, $matches)) {
        $districtsSQL = $matches[0];
        // Convert to work with our table structure
        $districtsSQL = str_replace('INSERT INTO `districts`', 'INSERT INTO districts', $districtsSQL);
        
        try {
            // Clear existing data first
            $dbAccess->execute("DELETE FROM districts");
            $dbAccess->execute($districtsSQL);
            echo "<p class='success'>✅ Imported districts data</p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ Error importing districts: " . $e->getMessage() . "</p>";
        }
    }
    
    // Extract counties data
    if (preg_match('/INSERT INTO `counties`[^;]+;/', $sqlContent, $matches)) {
        $countiesSQL = $matches[0];
        $countiesSQL = str_replace('INSERT INTO `counties`', 'INSERT INTO counties', $countiesSQL);
        
        try {
            $dbAccess->execute("DELETE FROM counties");
            $dbAccess->execute($countiesSQL);
            echo "<p class='success'>✅ Imported counties data</p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ Error importing counties: " . $e->getMessage() . "</p>";
        }
    }
    
    // Extract sub_counties data
    if (preg_match('/INSERT INTO `sub_counties`[^;]+;/', $sqlContent, $matches)) {
        $subCountiesSQL = $matches[0];
        $subCountiesSQL = str_replace('INSERT INTO `sub_counties`', 'INSERT INTO sub_counties', $subCountiesSQL);
        
        try {
            $dbAccess->execute("DELETE FROM sub_counties");
            $dbAccess->execute($subCountiesSQL);
            echo "<p class='success'>✅ Imported sub_counties data</p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ Error importing sub_counties: " . $e->getMessage() . "</p>";
        }
    }
    
    // Extract parishes data
    if (preg_match('/INSERT INTO `parishes`[^;]+;/', $sqlContent, $matches)) {
        $parishesSQL = $matches[0];
        $parishesSQL = str_replace('INSERT INTO `parishes`', 'INSERT INTO parishes', $parishesSQL);
        
        try {
            $dbAccess->execute("DELETE FROM parishes");
            $dbAccess->execute($parishesSQL);
            echo "<p class='success'>✅ Imported parishes data</p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ Error importing parishes: " . $e->getMessage() . "</p>";
        }
    }
    
    // Extract villages data
    if (preg_match('/INSERT INTO `villages`[^;]+;/', $sqlContent, $matches)) {
        $villagesSQL = $matches[0];
        $villagesSQL = str_replace('INSERT INTO `villages`', 'INSERT INTO villages', $villagesSQL);
        
        try {
            $dbAccess->execute("DELETE FROM villages");
            $dbAccess->execute($villagesSQL);
            echo "<p class='success'>✅ Imported villages data</p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ Error importing villages: " . $e->getMessage() . "</p>";
        }
    }
    
    // Now we need to update the territory_districts table to link with the new districts
    echo "<h3>Updating Territory Districts</h3>";
    
    try {
        // Clear existing territory_districts
        $dbAccess->execute("DELETE FROM territory_districts");
        
        // Insert districts into territory_districts table
        $districts = $dbAccess->select("districts");
        foreach ($districts as $district) {
            $insertSQL = "INSERT INTO territory_districts (territoryId, districtName) VALUES (1, '" . 
                        $dbAccess->clean($district['districtName']) . "')";
            $dbAccess->execute($insertSQL);
        }
        echo "<p class='success'>✅ Updated territory_districts table</p>";
    } catch (Exception $e) {
        echo "<p class='error'>❌ Error updating territory_districts: " . $e->getMessage() . "</p>";
    }
    
    // Display summary
    echo "<h3>Import Summary</h3>";
    echo "<table>";
    echo "<tr><th>Table</th><th>Records</th></tr>";
    
    $tables = ['districts', 'counties', 'sub_counties', 'parishes', 'villages', 'territory_districts'];
    foreach ($tables as $table) {
        try {
            $data = $dbAccess->select($table);
            $count = count($data);
            echo "<tr><td>$table</td><td>$count</td></tr>";
        } catch (Exception $e) {
            echo "<tr><td>$table</td><td>Error: " . $e->getMessage() . "</td></tr>";
        }
    }
    echo "</table>";
    
    echo "<div class='success' style='background:#d4edda;padding:15px;border-radius:5px;margin-top:20px;'>";
    echo "<h4>🎉 Import Complete!</h4>";
    echo "<p>Your fuel station registration form should now work with the real Uganda geographic data.</p>";
    echo "<p><a href='views/fuelstation/create.php' target='_blank'>Test the form here</a></p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Import failed: " . $e->getMessage() . "</p>";
}
?>
