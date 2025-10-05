<?php
/**
 * Simple Import of Subcounties Data
 */

require_once 'utils/dbaccess.php';

echo "Simple Import of Subcounties Data\n";
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
    $endPos = strpos($sqlContent, ');', $startPos);
    if ($endPos === false) {
        echo "❌ Could not find end of subcounties INSERT statement\n";
        exit();
    }
    
    // Extract the INSERT statement
    $insertStatement = substr($sqlContent, $startPos, $endPos - $startPos + 2);
    
    echo "Step 2: Found subcounties INSERT statement\n";
    
    // Execute the INSERT statement
    echo "Step 3: Importing subcounties data...\n";
    
    if ($dbAccess->conn->query($insertStatement)) {
        echo "✅ Subcounties data imported successfully!\n";
        
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
        echo "❌ Failed to import subcounties data: " . $dbAccess->conn->error . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
