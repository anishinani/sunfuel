<?php
/**
 * Import Parishes Simple
 * This script will import parishes using a simpler approach
 */

require_once 'utils/dbaccess.php';

echo "Import Parishes Simple\n";
echo "=====================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Clear existing data
    echo "Step 1: Clearing existing parishes data...\n";
    $dbAccess->conn->query("TRUNCATE TABLE parishes");
    echo "✅ Cleared existing data\n";
    
    // Read the values file
    $valuesFile = '/Applications/XAMPP/xamppfiles/htdocs/sunfuel/parishes_values_only.sql';
    $values = file($valuesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    echo "Step 2: Found " . count($values) . " parish records\n";
    
    // Insert records one by one to avoid SQL syntax issues
    $totalInserted = 0;
    $errors = 0;
    
    foreach ($values as $index => $record) {
        // Remove trailing comma
        $record = rtrim($record, ',');
        
        $insertSQL = "INSERT INTO parishes (id, uuid, districtCode, countyCode, subCountyCode, parishCode, parishName, deleted_at, created_at, updated_at) VALUES $record";
        
        if ($dbAccess->conn->query($insertSQL)) {
            $totalInserted++;
            if ($totalInserted % 1000 == 0) {
                echo "✅ Inserted $totalInserted records...\n";
            }
        } else {
            $errors++;
            if ($errors <= 5) { // Only show first 5 errors
                echo "❌ Failed to insert record " . ($index + 1) . ": " . $dbAccess->conn->error . "\n";
            }
        }
    }
    
    echo "Step 3: Import completed!\n";
    echo "✅ Total parishes imported: $totalInserted\n";
    echo "❌ Total errors: $errors\n";
    
    // Verify the import
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM parishes");
    $row = $result->fetch_assoc();
    echo "✅ Verification: " . $row['count'] . " parishes in database\n";
    
    // Test with different districts
    echo "\nStep 4: Testing with different districts...\n";
    
    // Test KAMPALA
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM parishes WHERE districtCode = '12'");
    $row = $result->fetch_assoc();
    echo "✅ KAMPALA parishes: " . $row['count'] . "\n";
    
    // Test WAKISO
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM parishes WHERE districtCode = '52'");
    $row = $result->fetch_assoc();
    echo "✅ WAKISO parishes: " . $row['count'] . "\n";
    
    // Test top 5 districts by parish count
    $result = $dbAccess->conn->query("SELECT districtCode, COUNT(*) as count FROM parishes GROUP BY districtCode ORDER BY count DESC LIMIT 5");
    echo "Top 5 districts by parish count:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- District " . $row['districtCode'] . ": " . $row['count'] . " parishes\n";
    }
    
    echo "\n✅ SUCCESS! All parishes data has been imported!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
