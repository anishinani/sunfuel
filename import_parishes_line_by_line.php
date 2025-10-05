<?php
/**
 * Import Parishes Data Line by Line
 * This script will import the parishes data by reading the SQL file line by line
 */

require_once 'utils/dbaccess.php';

echo "Import Parishes Data Line by Line\n";
echo "=================================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Clear existing data
    echo "Step 1: Clearing existing parishes data...\n";
    $dbAccess->conn->query("TRUNCATE TABLE parishes");
    echo "✅ Cleared existing data\n";
    
    // Read the SQL file line by line
    $sqlFile = '/Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp/u367101322_uganda.sql';
    $handle = fopen($sqlFile, 'r');
    
    if (!$handle) {
        echo "❌ Could not open SQL file\n";
        exit();
    }
    
    $inParishesSection = false;
    $inserted = 0;
    $batch = [];
    $batchSize = 100;
    
    echo "Step 2: Reading SQL file line by line...\n";
    
    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        
        // Check if we're entering the parishes section
        if (strpos($line, 'INSERT INTO `parishes`') !== false) {
            $inParishesSection = true;
            echo "✅ Found parishes INSERT statement\n";
            continue;
        }
        
        // Check if we're leaving the parishes section
        if ($inParishesSection && strpos($line, 'INSERT INTO `') !== false && strpos($line, 'INSERT INTO `parishes`') === false) {
            $inParishesSection = false;
            echo "✅ Finished parishes section\n";
            break;
        }
        
        // Process parishes data
        if ($inParishesSection && strpos($line, '(') === 0) {
            // Remove trailing comma and semicolon
            $line = rtrim($line, ',;');
            
            if (!empty($line)) {
                $batch[] = $line;
                
                // Insert batch when it reaches the batch size
                if (count($batch) >= $batchSize) {
                    $insertSQL = "INSERT INTO parishes (id, uuid, districtCode, countyCode, subCountyCode, parishCode, parishName, deleted_at, created_at, updated_at) VALUES " . implode(', ', $batch);
                    
                    if ($dbAccess->conn->query($insertSQL)) {
                        $inserted += count($batch);
                        echo "✅ Inserted batch (" . count($batch) . " records) - Total: $inserted\n";
                    } else {
                        echo "❌ Failed to insert batch: " . $dbAccess->conn->error . "\n";
                    }
                    
                    $batch = [];
                }
            }
        }
    }
    
    // Insert remaining records
    if (!empty($batch)) {
        $insertSQL = "INSERT INTO parishes (id, uuid, districtCode, countyCode, subCountyCode, parishCode, parishName, deleted_at, created_at, updated_at) VALUES " . implode(', ', $batch);
        
        if ($dbAccess->conn->query($insertSQL)) {
            $inserted += count($batch);
            echo "✅ Inserted final batch (" . count($batch) . " records) - Total: $inserted\n";
        } else {
            echo "❌ Failed to insert final batch: " . $dbAccess->conn->error . "\n";
        }
    }
    
    fclose($handle);
    
    echo "Step 3: Import completed!\n";
    echo "✅ Total parishes imported: $inserted\n";
    
    // Verify the import
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM parishes");
    $row = $result->fetch_assoc();
    echo "✅ Verification: " . $row['count'] . " parishes in database\n";
    
    // Test with KAWEMPE DIVISION
    echo "\nStep 4: Testing with KAWEMPE DIVISION...\n";
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM parishes WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1'");
    $row = $result->fetch_assoc();
    echo "✅ KAWEMPE DIVISION parishes: " . $row['count'] . "\n";
    
    if ($row['count'] > 0) {
        $result = $dbAccess->conn->query("SELECT parishName FROM parishes WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1' ORDER BY parishName");
        echo "KAWEMPE DIVISION parishes:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['parishName'] . "\n";
        }
    }
    
    echo "\n✅ SUCCESS! All parishes data has been imported!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
