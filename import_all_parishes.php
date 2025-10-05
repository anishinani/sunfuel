<?php
/**
 * Import All Parishes from Original Data
 * This script will import all parishes from the original SQL file
 */

require_once 'utils/dbaccess.php';

echo "Import All Parishes from Original Data\n";
echo "======================================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Clear existing parishes data
    echo "Step 1: Clearing existing parishes data...\n";
    $dbAccess->conn->query("TRUNCATE TABLE parishes");
    echo "✅ Cleared existing parishes data\n";
    
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
    
    // Execute the INSERT statement
    echo "Step 3: Importing all parishes data...\n";
    
    if ($dbAccess->conn->query($insertStatement)) {
        echo "✅ Parishes data imported successfully!\n";
        
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
        
        // Test with other districts
        echo "\nStep 5: Testing with other districts...\n";
        $result = $dbAccess->conn->query("SELECT DISTINCT districtCode, COUNT(*) as count FROM parishes GROUP BY districtCode ORDER BY count DESC LIMIT 5");
        echo "Top 5 districts by parish count:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- District " . $row['districtCode'] . ": " . $row['count'] . " parishes\n";
        }
        
        echo "\n✅ SUCCESS! All parishes data has been imported!\n";
        
    } else {
        echo "❌ Failed to import parishes data: " . $dbAccess->conn->error . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
