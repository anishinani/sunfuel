<?php
/**
 * Fix Geographic Data Import
 * The original import was using wrong field relationships
 * This script will fix the data properly
 */

require_once 'utils/dbaccess.php';

echo "<h2>🔧 Fixing Geographic Data Import</h2>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;}</style>";

try {
    $dbAccess = new DbAccess();
    echo "<p class='success'>✅ Connected to database</p>";
    
    // First, let's drop and recreate the tables with correct structure
    echo "<h3>Step 1: Dropping existing tables</h3>";
    
    $tables_to_drop = ['villages', 'parishes', 'sub_counties', 'counties', 'districts'];
    foreach ($tables_to_drop as $table) {
        try {
            $dbAccess->query("DROP TABLE IF EXISTS `$table`");
            echo "<p>✅ Dropped table: $table</p>";
        } catch (Exception $e) {
            echo "<p class='warning'>⚠️ Could not drop $table: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<h3>Step 2: Creating tables with correct structure</h3>";
    
    // Create districts table
    $create_districts = "
    CREATE TABLE `districts` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `uuid` char(36) NOT NULL,
        `districtCode` varchar(255) NOT NULL,
        `districtName` varchar(255) NOT NULL,
        `deleted_at` timestamp NULL DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_district_name` (`districtName`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $dbAccess->query($create_districts);
    echo "<p>✅ Created districts table</p>";
    
    // Create counties table
    $create_counties = "
    CREATE TABLE `counties` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `uuid` char(36) NOT NULL,
        `districtCode` bigint(20) UNSIGNED NOT NULL,
        `countyCode` varchar(255) NOT NULL,
        `countyName` varchar(255) NOT NULL,
        `deleted_at` timestamp NULL DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_district_code` (`districtCode`),
        FOREIGN KEY (`districtCode`) REFERENCES `districts`(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $dbAccess->query($create_counties);
    echo "<p>✅ Created counties table</p>";
    
    // Create sub_counties table
    $create_subcounties = "
    CREATE TABLE `sub_counties` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `uuid` char(36) NOT NULL,
        `districtCode` bigint(20) UNSIGNED NOT NULL,
        `countyCode` bigint(20) UNSIGNED NOT NULL,
        `subCountyCode` varchar(255) NOT NULL,
        `subCountyName` varchar(255) NOT NULL,
        `deleted_at` timestamp NULL DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_district_county` (`districtCode`, `countyCode`),
        FOREIGN KEY (`districtCode`) REFERENCES `districts`(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $dbAccess->query($create_subcounties);
    echo "<p>✅ Created sub_counties table</p>";
    
    // Create parishes table
    $create_parishes = "
    CREATE TABLE `parishes` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `uuid` char(36) NOT NULL,
        `districtCode` bigint(20) UNSIGNED NOT NULL,
        `countyCode` bigint(20) UNSIGNED NOT NULL,
        `subCountyCode` bigint(20) UNSIGNED NOT NULL,
        `parishCode` varchar(255) NOT NULL,
        `parishName` varchar(255) NOT NULL,
        `deleted_at` timestamp NULL DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_district_county_subcounty` (`districtCode`, `countyCode`, `subCountyCode`),
        FOREIGN KEY (`districtCode`) REFERENCES `districts`(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $dbAccess->query($create_parishes);
    echo "<p>✅ Created parishes table</p>";
    
    // Create villages table
    $create_villages = "
    CREATE TABLE `villages` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `uuid` char(36) NOT NULL,
        `districtCode` bigint(20) UNSIGNED NOT NULL,
        `countyCode` bigint(20) UNSIGNED NOT NULL,
        `subCountyCode` bigint(20) UNSIGNED NOT NULL,
        `parishCode` bigint(20) UNSIGNED NOT NULL,
        `villageCode` varchar(255) NOT NULL,
        `villageName` varchar(255) NOT NULL,
        `deleted_at` timestamp NULL DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `idx_district_county_subcounty_parish` (`districtCode`, `countyCode`, `subCountyCode`, `parishCode`),
        FOREIGN KEY (`districtCode`) REFERENCES `districts`(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $dbAccess->query($create_villages);
    echo "<p>✅ Created villages table</p>";
    
    echo "<h3>Step 3: Importing data from SQL file</h3>";
    
    // Read the SQL file
    $sql_file = '/Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp/u367101322_uganda.sql';
    if (!file_exists($sql_file)) {
        throw new Exception("SQL file not found: $sql_file");
    }
    
    $sql_content = file_get_contents($sql_file);
    
    // Extract and execute districts data
    echo "<p>📥 Importing districts...</p>";
    if (preg_match('/INSERT INTO `districts`[^;]+;/', $sql_content, $matches)) {
        $districts_sql = $matches[0];
        $dbAccess->query($districts_sql);
        echo "<p>✅ Imported districts data</p>";
    }
    
    // Extract and execute counties data
    echo "<p>📥 Importing counties...</p>";
    if (preg_match('/INSERT INTO `counties`[^;]+;/', $sql_content, $matches)) {
        $counties_sql = $matches[0];
        $dbAccess->query($counties_sql);
        echo "<p>✅ Imported counties data</p>";
    }
    
    // Extract and execute sub_counties data
    echo "<p>📥 Importing sub_counties...</p>";
    if (preg_match('/INSERT INTO `sub_counties`[^;]+;/', $sql_content, $matches)) {
        $subcounties_sql = $matches[0];
        $dbAccess->query($subcounties_sql);
        echo "<p>✅ Imported sub_counties data</p>";
    }
    
    // Extract and execute parishes data
    echo "<p>📥 Importing parishes...</p>";
    if (preg_match('/INSERT INTO `parishes`[^;]+;/', $sql_content, $matches)) {
        $parishes_sql = $matches[0];
        $dbAccess->query($parishes_sql);
        echo "<p>✅ Imported parishes data</p>";
    }
    
    // Extract and execute villages data
    echo "<p>📥 Importing villages...</p>";
    if (preg_match('/INSERT INTO `villages`[^;]+;/', $sql_content, $matches)) {
        $villages_sql = $matches[0];
        $dbAccess->query($villages_sql);
        echo "<p>✅ Imported villages data</p>";
    }
    
    echo "<h3>Step 4: Verifying Kampala data</h3>";
    
    // Check Kampala district
    $kampala_district = $dbAccess->select("districts", "", ["districtName" => "KAMPALA"]);
    if (count($kampala_district) > 0) {
        $kampala_id = $kampala_district[0]['id'];
        echo "<p>✅ Found Kampala district with ID: $kampala_id</p>";
        
        // Check counties for Kampala
        $kampala_counties = $dbAccess->select("counties", "", ["districtCode" => $kampala_id]);
        echo "<p>✅ Found " . count($kampala_counties) . " counties for Kampala:</p>";
        echo "<ul>";
        foreach ($kampala_counties as $county) {
            echo "<li>{$county['countyName']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='error'>❌ Kampala district not found</p>";
    }
    
    echo "<h3>✅ Geographic data import completed successfully!</h3>";
    echo "<p>You can now test the fuel station form at: <a href='views/fuelstation/create.php'>Create Fuel Station</a></p>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
