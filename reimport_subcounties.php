<?php
/**
 * Re-import Subcounties Data
 * This script will properly import the subcounties data from the original SQL file
 */

require_once 'utils/dbaccess.php';

echo "Re-importing Subcounties Data\n";
echo "============================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // First, let's clear the existing subcounties data
    echo "Step 1: Clearing existing subcounties data...\n";
    $result = $dbAccess->conn->query("DELETE FROM sub_counties");
    if ($result) {
        echo "✅ Cleared existing subcounties data\n";
    } else {
        echo "❌ Failed to clear existing data: " . $dbAccess->conn->error . "\n";
        exit();
    }
    
    // Reset auto increment
    $dbAccess->conn->query("ALTER TABLE sub_counties AUTO_INCREMENT = 1");
    echo "✅ Reset auto increment\n";
    
    // Read the original SQL file
    $sqlFile = '/Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp/u367101322_uganda.sql';
    
    if (!file_exists($sqlFile)) {
        echo "❌ SQL file not found: $sqlFile\n";
        exit();
    }
    
    echo "Step 2: Reading SQL file...\n";
    $sqlContent = file_get_contents($sqlFile);
    
    // Extract subcounties INSERT statement
    if (preg_match('/INSERT INTO `sub_counties`.*?VALUES\s*\((.*?)\);/s', $sqlContent, $matches)) {
        echo "✅ Found subcounties INSERT statement\n";
        
        // Parse the VALUES part
        $valuesString = $matches[1];
        
        // Split by individual records (look for the pattern that starts with a number)
        $records = [];
        $currentRecord = '';
        $parenCount = 0;
        $inString = false;
        $escapeNext = false;
        
        for ($i = 0; $i < strlen($valuesString); $i++) {
            $char = $valuesString[$i];
            
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
                        // End of a record
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
                echo "SQL: " . substr($insertSQL, 0, 200) . "...\n";
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
        } else {
            echo "⚠️ KIRA MUNICIPALITY subcounties still not found\n";
        }
        
    } else {
        echo "❌ Could not find subcounties INSERT statement in SQL file\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
