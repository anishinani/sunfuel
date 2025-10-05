<?php
/**
 * Import Geographic Data Properly
 * This script will import geographic data using a more robust approach
 */

require_once 'utils/dbaccess.php';

echo "Import Geographic Data Properly\n";
echo "==============================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Clear existing geographic data
    echo "Step 1: Clearing existing geographic data...\n";
    $dbAccess->conn->query("TRUNCATE TABLE villages");
    $dbAccess->conn->query("TRUNCATE TABLE parishes");
    $dbAccess->conn->query("TRUNCATE TABLE sub_counties");
    $dbAccess->conn->query("TRUNCATE TABLE counties");
    $dbAccess->conn->query("TRUNCATE TABLE districts");
    echo "✅ Cleared existing data\n";
    
    // Read the SQL file
    $sqlFile = '/Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp/u367101322_uganda.sql';
    $sqlContent = file_get_contents($sqlFile);
    
    // Import districts
    echo "Step 2: Importing districts data...\n";
    $startPos = strpos($sqlContent, 'INSERT INTO `districts`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $districtsStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    $districtsStatement = rtrim($districtsStatement, " \t\n\r\0\x0B;");
    
    // Split into smaller chunks
    $valuesStart = strpos($districtsStatement, 'VALUES');
    $valuesPart = substr($districtsStatement, $valuesStart + 6);
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
    
    echo "Found " . count($records) . " district records\n";
    
    // Insert districts in batches
    $batchSize = 50;
    $totalInserted = 0;
    
    for ($i = 0; $i < count($records); $i += $batchSize) {
        $batch = array_slice($records, $i, $batchSize);
        
        $insertSQL = "INSERT INTO districts (id, uuid, districtCode, districtName, deleted_at, created_at, updated_at) VALUES " . implode(', ', $batch);
        
        if ($dbAccess->conn->query($insertSQL)) {
            $totalInserted += count($batch);
            echo "✅ Inserted districts batch " . (floor($i / $batchSize) + 1) . " (" . count($batch) . " records)\n";
        } else {
            echo "❌ Failed to insert districts batch: " . $dbAccess->conn->error . "\n";
            break;
        }
    }
    
    echo "✅ Districts imported: $totalInserted records\n";
    
    // Import counties
    echo "Step 3: Importing counties data...\n";
    $startPos = strpos($sqlContent, 'INSERT INTO `counties`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $countiesStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    $countiesStatement = rtrim($countiesStatement, " \t\n\r\0\x0B;");
    
    // Split into smaller chunks
    $valuesStart = strpos($countiesStatement, 'VALUES');
    $valuesPart = substr($countiesStatement, $valuesStart + 6);
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
    
    echo "Found " . count($records) . " county records\n";
    
    // Insert counties in batches
    $totalInserted = 0;
    
    for ($i = 0; $i < count($records); $i += $batchSize) {
        $batch = array_slice($records, $i, $batchSize);
        
        $insertSQL = "INSERT INTO counties (id, uuid, districtCode, countyCode, countyName, deleted_at, created_at, updated_at) VALUES " . implode(', ', $batch);
        
        if ($dbAccess->conn->query($insertSQL)) {
            $totalInserted += count($batch);
            echo "✅ Inserted counties batch " . (floor($i / $batchSize) + 1) . " (" . count($batch) . " records)\n";
        } else {
            echo "❌ Failed to insert counties batch: " . $dbAccess->conn->error . "\n";
            break;
        }
    }
    
    echo "✅ Counties imported: $totalInserted records\n";
    
    // Test the results
    echo "\nStep 4: Testing the results...\n";
    
    // Test districts
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM districts");
    $row = $result->fetch_assoc();
    echo "✅ Total districts: " . $row['count'] . "\n";
    
    // Test counties for KAMPALA
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM counties WHERE districtCode = '12'");
    $row = $result->fetch_assoc();
    echo "✅ KAMPALA counties: " . $row['count'] . "\n";
    
    if ($row['count'] > 0) {
        $result = $dbAccess->conn->query("SELECT countyName FROM counties WHERE districtCode = '12' ORDER BY countyName");
        echo "KAMPALA counties:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['countyName'] . "\n";
        }
    }
    
    echo "\n✅ SUCCESS! Geographic data has been imported properly!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
