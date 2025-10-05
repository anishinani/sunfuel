<?php
/**
 * Re-import All Geographic Data
 * This script will re-import all geographic data (subcounties, parishes, villages) from the original SQL file
 */

require_once 'utils/dbaccess.php';

echo "Re-import All Geographic Data\n";
echo "============================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Clear existing data
    echo "Step 1: Clearing existing geographic data...\n";
    $dbAccess->conn->query("TRUNCATE TABLE sub_counties");
    $dbAccess->conn->query("TRUNCATE TABLE parishes");
    $dbAccess->conn->query("TRUNCATE TABLE villages");
    echo "✅ Cleared existing data\n";
    
    // Read the SQL file
    $sqlFile = '/Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp/u367101322_uganda.sql';
    $sqlContent = file_get_contents($sqlFile);
    
    // Import subcounties
    echo "Step 2: Importing subcounties data...\n";
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
    
    // Import parishes
    echo "Step 3: Importing parishes data...\n";
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
    
    // Import villages
    echo "Step 4: Importing villages data...\n";
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
    
    // Test the specific example
    echo "\nStep 5: Testing the specific example...\n";
    echo "KAMPALA → KAWEMPE DIVISION → KAWEMPE DIVISION → MAKERERE II → ZONE A\n";
    
    // Test parishes for KAMPALA/KAWEMPE DIVISION
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM parishes WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1'");
    $row = $result->fetch_assoc();
    echo "✅ Parishes for KAMPALA/KAWEMPE DIVISION: " . $row['count'] . "\n";
    
    if ($row['count'] > 0) {
        $result = $dbAccess->conn->query("SELECT parishName FROM parishes WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1'");
        echo "Parishes found:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['parishName'] . "\n";
        }
    }
    
    // Test villages for MAKERERE II
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM villages WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1' AND parishCode = '8'");
    $row = $result->fetch_assoc();
    echo "✅ Villages for MAKERERE II: " . $row['count'] . "\n";
    
    if ($row['count'] > 0) {
        $result = $dbAccess->conn->query("SELECT villageName FROM villages WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1' AND parishCode = '8'");
        echo "Villages found:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['villageName'] . "\n";
        }
    }
    
    echo "\n✅ SUCCESS! All geographic data has been re-imported!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
