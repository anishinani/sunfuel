<?php
/**
 * Import Subcounties Data in Chunks
 * This script will import the subcounties data in smaller chunks to avoid size limits
 */

require_once 'utils/dbaccess.php';

echo "Import Subcounties Data in Chunks\n";
echo "=================================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Clear existing data
    echo "Step 1: Clearing existing subcounties data...\n";
    $dbAccess->conn->query("TRUNCATE TABLE sub_counties");
    echo "✅ Cleared existing data\n";
    
    // Read the SQL file
    $sqlFile = '/Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp/u367101322_uganda.sql';
    $sqlContent = file_get_contents($sqlFile);
    
    // Find the subcounties INSERT statement
    $startPos = strpos($sqlContent, 'INSERT INTO `sub_counties`');
    if ($startPos === false) {
        echo "❌ Could not find subcounties INSERT statement\n";
        exit();
    }
    
    // Find the end of the INSERT statement
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    if ($nextInsertPos === false) {
        echo "❌ Could not find end of subcounties INSERT statement\n";
        exit();
    }
    
    // Extract the INSERT statement
    $insertStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    
    // Remove the INSERT INTO part and get just the VALUES
    $valuesStart = strpos($insertStatement, 'VALUES');
    if ($valuesStart === false) {
        echo "❌ Could not find VALUES in INSERT statement\n";
        exit();
    }
    
    $valuesPart = substr($insertStatement, $valuesStart + 6); // Skip "VALUES"
    $valuesPart = trim($valuesPart);
    
    echo "Step 2: Found subcounties data\n";
    echo "Values length: " . strlen($valuesPart) . " characters\n";
    
    // Split the values into individual records
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
    
    echo "Step 3: Found " . count($records) . " subcounty records\n";
    
    // Insert records in batches
    $batchSize = 100;
    $totalInserted = 0;
    
    for ($i = 0; $i < count($records); $i += $batchSize) {
        $batch = array_slice($records, $i, $batchSize);
        
        $insertSQL = "INSERT INTO sub_counties (id, uuid, districtCode, countyCode, subCountyCode, subCountyName, deleted_at, created_at, updated_at) VALUES ";
        
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
    
    echo "Step 4: Import completed!\n";
    echo "✅ Total subcounties imported: $totalInserted\n";
    
    // Verify the import
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM sub_counties");
    $row = $result->fetch_assoc();
    echo "✅ Verification: " . $row['count'] . " subcounties in database\n";
    
    // Test with WAKISO
    echo "\nStep 5: Testing with WAKISO...\n";
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM sub_counties WHERE districtCode = '52'");
    $row = $result->fetch_assoc();
    echo "✅ WAKISO subcounties: " . $row['count'] . "\n";
    
    // Test with KIRA MUNICIPALITY
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM sub_counties WHERE districtCode = '52' AND countyCode = '240'");
    $row = $result->fetch_assoc();
    echo "✅ KIRA MUNICIPALITY subcounties: " . $row['count'] . "\n";
    
    if ($row['count'] > 0) {
        echo "✅ SUCCESS! Subcounties data has been properly imported!\n";
        
        // Show some examples
        $result = $dbAccess->conn->query("SELECT subCountyName FROM sub_counties WHERE districtCode = '52' AND countyCode = '240' LIMIT 5");
        echo "\nSample KIRA MUNICIPALITY subcounties:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['subCountyName'] . "\n";
        }
    } else {
        echo "⚠️ KIRA MUNICIPALITY subcounties still not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
