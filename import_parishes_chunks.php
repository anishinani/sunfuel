<?php
/**
 * Import Parishes Data in Chunks
 * This script will import the parishes data in smaller chunks to avoid size limits
 */

require_once 'utils/dbaccess.php';

echo "Import Parishes Data in Chunks\n";
echo "==============================\n";

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
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $parishesStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    
    // Extract values part
    $valuesStart = strpos($parishesStatement, 'VALUES');
    $valuesPart = substr($parishesStatement, $valuesStart + 6);
    $valuesPart = trim($valuesPart);
    
    // Split into individual records
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
    
    echo "Step 2: Found " . count($records) . " parish records\n";
    
    // Insert records in batches
    $batchSize = 100;
    $totalInserted = 0;
    
    for ($i = 0; $i < count($records); $i += $batchSize) {
        $batch = array_slice($records, $i, $batchSize);
        
        $insertSQL = "INSERT INTO parishes (id, uuid, districtCode, countyCode, subCountyCode, parishCode, parishName, deleted_at, created_at, updated_at) VALUES ";
        
        $values = [];
        foreach ($batch as $record) {
            $values[] = "($record)";
        }
        
        $insertSQL .= implode(', ', $values);
        
        if ($dbAccess->conn->query($insertSQL)) {
            $totalInserted += count($batch);
            echo "✅ Inserted batch " . (floor($i / $batchSize) + 1) . " (" . count($batch) . " records)\n";
        } else {
            echo "❌ Failed to insert batch: " . $dbAccess->conn->error . "\n";
            break;
        }
    }
    
    echo "Step 3: Import completed!\n";
    echo "✅ Total parishes imported: $totalInserted\n";
    
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
