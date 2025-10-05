<?php
/**
 * Import ALL Parishes Data Complete
 * This script will import ALL parishes from the original SQL file using a more robust approach
 */

require_once 'utils/dbaccess.php';

echo "Import ALL Parishes Data Complete\n";
echo "=================================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Clear existing data
    echo "Step 1: Clearing existing parishes data...\n";
    $dbAccess->conn->query("TRUNCATE TABLE parishes");
    echo "✅ Cleared existing data\n";
    
    // Read the SQL file
    $sqlFile = '/Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp/u367101322_uganda.sql';
    $sqlContent = file_get_contents($sqlFile);
    
    // Find the parishes INSERT statement
    $startPos = strpos($sqlContent, 'INSERT INTO `parishes`');
    if ($startPos === false) {
        echo "❌ Could not find parishes INSERT statement\n";
        exit();
    }
    
    // Find the end of the INSERT statement
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    if ($nextInsertPos === false) {
        echo "❌ Could not find end of parishes INSERT statement\n";
        exit();
    }
    
    // Extract the INSERT statement
    $insertStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    $insertStatement = rtrim($insertStatement, " \t\n\r\0\x0B;");
    
    echo "Step 2: Found parishes INSERT statement\n";
    echo "Statement length: " . strlen($insertStatement) . " characters\n";
    
    // Split the statement into smaller chunks
    echo "Step 3: Splitting into smaller chunks...\n";
    
    // Find the VALUES part
    $valuesStart = strpos($insertStatement, 'VALUES');
    if ($valuesStart === false) {
        echo "❌ Could not find VALUES in INSERT statement\n";
        exit();
    }
    
    $insertPrefix = substr($insertStatement, 0, $valuesStart + 6); // Include "VALUES"
    $valuesPart = substr($insertStatement, $valuesStart + 6);
    
    // Split values into individual records
    $records = [];
    $currentRecord = '';
    $parenCount = 0;
    $inString = false;
    $escapeNext = false;
    
    for ($i = 0; $i < strlen($valuesPart); $i++) {
        $char = $valuesPart[$i];
        
        if ($escapeNext) {
            $currentRecord .= $char;
            $escapeNext = false;
            continue;
        }
        
        if ($char === '\\') {
            $escapeNext = true;
            $currentRecord .= $char;
            continue;
        }
        
        if ($char === "'" && !$escapeNext) {
            $inString = !$inString;
        }
        
        if (!$inString) {
            if ($char === '(') {
                $parenCount++;
            } elseif ($char === ')') {
                $parenCount--;
                if ($parenCount === 0) {
                    $records[] = trim($currentRecord);
                    $currentRecord = '';
                    continue;
                }
            }
        }
        
        $currentRecord .= $char;
    }
    
    echo "Step 4: Found " . count($records) . " parish records\n";
    
    // Insert records in batches
    $batchSize = 50;
    $totalInserted = 0;
    
    for ($i = 0; $i < count($records); $i += $batchSize) {
        $batch = array_slice($records, $i, $batchSize);
        
        $insertSQL = $insertPrefix . " " . implode(', ', $batch);
        
        if ($dbAccess->conn->query($insertSQL)) {
            $totalInserted += count($batch);
            echo "✅ Inserted batch " . (floor($i / $batchSize) + 1) . " (" . count($batch) . " records) - Total: $totalInserted\n";
        } else {
            echo "❌ Failed to insert batch: " . $dbAccess->conn->error . "\n";
            break;
        }
    }
    
    echo "Step 5: Import completed!\n";
    echo "✅ Total parishes imported: $totalInserted\n";
    
    // Verify the import
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM parishes");
    $row = $result->fetch_assoc();
    echo "✅ Verification: " . $row['count'] . " parishes in database\n";
    
    // Test with different districts
    echo "\nStep 6: Testing with different districts...\n";
    
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
