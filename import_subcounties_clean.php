<?php
/**
 * Clean Import of Subcounties Data
 */

require_once 'utils/dbaccess.php';

echo "Clean Import of Subcounties Data\n";
echo "================================\n";

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
    
    // Extract the subcounties INSERT statement
    if (preg_match('/INSERT INTO `sub_counties`.*?VALUES\s*\((.*?)\);/s', $sqlContent, $matches)) {
        echo "✅ Found subcounties data\n";
        
        $valuesString = $matches[1];
        
        // Split into individual records
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
                        $records[] = trim($currentRecord);
                        $currentRecord = '';
                        continue;
                    }
                }
            }
            
            $currentRecord .= $char;
        }
        
        echo "Step 2: Found " . count($records) . " subcounty records\n";
        
        // Insert records in batches
        $batchSize = 50;
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
        
        echo "Step 3: Import completed!\n";
        echo "✅ Total subcounties imported: $totalInserted\n";
        
        // Verify the import
        $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM sub_counties");
        $row = $result->fetch_assoc();
        echo "✅ Verification: " . $row['count'] . " subcounties in database\n";
        
        // Test with WAKISO
        echo "\nStep 4: Testing with WAKISO...\n";
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
        
    } else {
        echo "❌ Could not find subcounties data in SQL file\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
