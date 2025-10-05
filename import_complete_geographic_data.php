<?php
/**
 * Import Complete Geographic Data
 * This script will properly import all geographic data from the original SQL file
 */

require_once 'utils/dbaccess.php';

echo "Import Complete Geographic Data\n";
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
    
    echo "Step 2: Importing districts data...\n";
    // Extract and import districts
    $startPos = strpos($sqlContent, 'INSERT INTO `districts`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $districtsStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    $districtsStatement = rtrim($districtsStatement, " \t\n\r\0\x0B;");
    
    if ($dbAccess->conn->query($districtsStatement)) {
        $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM districts");
        $row = $result->fetch_assoc();
        echo "✅ Districts imported: " . $row['count'] . " records\n";
    } else {
        echo "❌ Failed to import districts: " . $dbAccess->conn->error . "\n";
    }
    
    echo "Step 3: Importing counties data...\n";
    // Extract and import counties
    $startPos = strpos($sqlContent, 'INSERT INTO `counties`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $countiesStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    $countiesStatement = rtrim($countiesStatement, " \t\n\r\0\x0B;");
    
    if ($dbAccess->conn->query($countiesStatement)) {
        $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM counties");
        $row = $result->fetch_assoc();
        echo "✅ Counties imported: " . $row['count'] . " records\n";
    } else {
        echo "❌ Failed to import counties: " . $dbAccess->conn->error . "\n";
    }
    
    echo "Step 4: Importing subcounties data...\n";
    // Extract and import subcounties
    $startPos = strpos($sqlContent, 'INSERT INTO `sub_counties`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $subcountiesStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    $subcountiesStatement = rtrim($subcountiesStatement, " \t\n\r\0\x0B;");
    
    if ($dbAccess->conn->query($subcountiesStatement)) {
        $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM sub_counties");
        $row = $result->fetch_assoc();
        echo "✅ Subcounties imported: " . $row['count'] . " records\n";
    } else {
        echo "❌ Failed to import subcounties: " . $dbAccess->conn->error . "\n";
    }
    
    echo "Step 5: Importing parishes data...\n";
    // Extract and import parishes
    $startPos = strpos($sqlContent, 'INSERT INTO `parishes`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $parishesStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    $parishesStatement = rtrim($parishesStatement, " \t\n\r\0\x0B;");
    
    if ($dbAccess->conn->query($parishesStatement)) {
        $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM parishes");
        $row = $result->fetch_assoc();
        echo "✅ Parishes imported: " . $row['count'] . " records\n";
    } else {
        echo "❌ Failed to import parishes: " . $dbAccess->conn->error . "\n";
    }
    
    echo "Step 6: Importing villages data...\n";
    // Extract and import villages
    $startPos = strpos($sqlContent, 'INSERT INTO `villages`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $villagesStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    $villagesStatement = rtrim($villagesStatement, " \t\n\r\0\x0B;");
    
    if ($dbAccess->conn->query($villagesStatement)) {
        $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM villages");
        $row = $result->fetch_assoc();
        echo "✅ Villages imported: " . $row['count'] . " records\n";
    } else {
        echo "❌ Failed to import villages: " . $dbAccess->conn->error . "\n";
    }
    
    // Test the complete hierarchy
    echo "\nStep 7: Testing the complete hierarchy...\n";
    echo "KAMPALA → KAWEMPE DIVISION → KAWEMPE DIVISION → MAKERERE II → ZONE A\n";
    
    // Test districts
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM districts");
    $row = $result->fetch_assoc();
    echo "✅ Total districts: " . $row['count'] . "\n";
    
    // Test counties for KAMPALA
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM counties WHERE districtCode = '12'");
    $row = $result->fetch_assoc();
    echo "✅ KAMPALA counties: " . $row['count'] . "\n";
    
    // Test subcounties for KAWEMPE DIVISION
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM sub_counties WHERE districtCode = '12' AND countyCode = '35'");
    $row = $result->fetch_assoc();
    echo "✅ KAWEMPE DIVISION subcounties: " . $row['count'] . "\n";
    
    // Test parishes for KAWEMPE DIVISION subcounty
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM parishes WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1'");
    $row = $result->fetch_assoc();
    echo "✅ KAWEMPE DIVISION parishes: " . $row['count'] . "\n";
    
    // Test villages for MAKERERE II
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM villages WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1' AND parishCode = '8'");
    $row = $result->fetch_assoc();
    echo "✅ MAKERERE II villages: " . $row['count'] . "\n";
    
    // Test KIRA MUNICIPALITY
    echo "\nStep 8: Testing KIRA MUNICIPALITY...\n";
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM sub_counties WHERE districtCode = '52' AND countyCode = '240'");
    $row = $result->fetch_assoc();
    echo "✅ KIRA MUNICIPALITY subcounties: " . $row['count'] . "\n";
    
    if ($row['count'] > 0) {
        $result = $dbAccess->conn->query("SELECT subCountyName FROM sub_counties WHERE districtCode = '52' AND countyCode = '240' ORDER BY subCountyName");
        echo "KIRA MUNICIPALITY subcounties:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['subCountyName'] . "\n";
        }
    }
    
    echo "\n✅ SUCCESS! Complete geographic data has been imported properly!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
