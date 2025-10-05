<?php
/**
 * Import Geographic Data Values Only
 * This script will import only the VALUES part of the INSERT statements
 */

echo "Import Geographic Data Values Only\n";
echo "=================================\n";

try {
    // Clear existing data
    echo "Step 1: Clearing existing geographic data...\n";
    $output = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' -e \"USE sunfuel; TRUNCATE TABLE villages; TRUNCATE TABLE parishes; TRUNCATE TABLE sub_counties; TRUNCATE TABLE counties; TRUNCATE TABLE districts;\" 2>&1");
    echo "✅ Cleared existing data\n";
    
    // Read the SQL file
    $sqlFile = '/Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp/u367101322_uganda.sql';
    $sqlContent = file_get_contents($sqlFile);
    
    // Create clean SQL with only VALUES
    $cleanSQL = '';
    
    // Extract districts VALUES
    $startPos = strpos($sqlContent, 'INSERT INTO `districts`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $districtsStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    
    $valuesStart = strpos($districtsStatement, 'VALUES');
    $valuesPart = substr($districtsStatement, $valuesStart);
    $cleanSQL .= "INSERT INTO districts (id, uuid, districtCode, districtName, deleted_at, created_at, updated_at) " . $valuesPart . ";\n\n";
    
    // Extract counties VALUES
    $startPos = strpos($sqlContent, 'INSERT INTO `counties`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $countiesStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    
    $valuesStart = strpos($countiesStatement, 'VALUES');
    $valuesPart = substr($countiesStatement, $valuesStart);
    $cleanSQL .= "INSERT INTO counties (id, uuid, districtCode, countyCode, countyName, deleted_at, created_at, updated_at) " . $valuesPart . ";\n\n";
    
    // Extract subcounties VALUES
    $startPos = strpos($sqlContent, 'INSERT INTO `sub_counties`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $subcountiesStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    
    $valuesStart = strpos($subcountiesStatement, 'VALUES');
    $valuesPart = substr($subcountiesStatement, $valuesStart);
    $cleanSQL .= "INSERT INTO sub_counties (id, uuid, districtCode, countyCode, subCountyCode, subCountyName, deleted_at, created_at, updated_at) " . $valuesPart . ";\n\n";
    
    // Extract parishes VALUES
    $startPos = strpos($sqlContent, 'INSERT INTO `parishes`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $parishesStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    
    $valuesStart = strpos($parishesStatement, 'VALUES');
    $valuesPart = substr($parishesStatement, $valuesStart);
    $cleanSQL .= "INSERT INTO parishes (id, uuid, districtCode, countyCode, subCountyCode, parishCode, parishName, deleted_at, created_at, updated_at) " . $valuesPart . ";\n\n";
    
    // Extract villages VALUES
    $startPos = strpos($sqlContent, 'INSERT INTO `villages`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $villagesStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    
    $valuesStart = strpos($villagesStatement, 'VALUES');
    $valuesPart = substr($villagesStatement, $valuesStart);
    $cleanSQL .= "INSERT INTO villages (id, uuid, districtCode, countyCode, subCountyCode, parishCode, villageCode, villageName, deleted_at, created_at, updated_at) " . $valuesPart . ";\n\n";
    
    // Write the clean SQL file
    file_put_contents('/Applications/XAMPP/xamppfiles/htdocs/sunfuel/geographic_values_only.sql', $cleanSQL);
    echo "✅ Created clean SQL file with VALUES only\n";
    
    // Import the data
    echo "Step 2: Importing geographic data...\n";
    $output = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' sunfuel < /Applications/XAMPP/xamppfiles/htdocs/sunfuel/geographic_values_only.sql 2>&1");
    
    if (empty($output)) {
        echo "✅ Geographic data imported successfully!\n";
        
        // Verify the import
        echo "Step 3: Verifying import...\n";
        $result = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' -e \"USE sunfuel; SELECT COUNT(*) as districts FROM districts; SELECT COUNT(*) as counties FROM counties; SELECT COUNT(*) as subcounties FROM sub_counties; SELECT COUNT(*) as parishes FROM parishes; SELECT COUNT(*) as villages FROM villages;\" 2>&1");
        echo "Results:\n$result\n";
        
        // Test KIRA MUNICIPALITY
        $result = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' -e \"USE sunfuel; SELECT subCountyName FROM sub_counties WHERE districtCode = '52' AND countyCode = '240' ORDER BY subCountyName;\" 2>&1");
        echo "KIRA MUNICIPALITY subcounties:\n$result\n";
        
        // Test KAWEMPE DIVISION
        $result = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' -e \"USE sunfuel; SELECT subCountyName FROM sub_counties WHERE districtCode = '12' AND countyCode = '35' ORDER BY subCountyName;\" 2>&1");
        echo "KAWEMPE DIVISION subcounties:\n$result\n";
        
    } else {
        echo "❌ Failed to import geographic data: $output\n";
    }
    
    // Clean up
    unlink('/Applications/XAMPP/xamppfiles/htdocs/sunfuel/geographic_values_only.sql');
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
