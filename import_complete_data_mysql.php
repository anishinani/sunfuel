<?php
/**
 * Import Complete Data using MySQL Command Line
 * This script will import all geographic data using MySQL command line
 */

echo "Import Complete Data using MySQL Command Line\n";
echo "===========================================\n";

try {
    // Clear existing data
    echo "Step 1: Clearing existing geographic data...\n";
    $output = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' -e \"USE sunfuel; TRUNCATE TABLE villages; TRUNCATE TABLE parishes; TRUNCATE TABLE sub_counties; TRUNCATE TABLE counties; TRUNCATE TABLE districts;\" 2>&1");
    echo "✅ Cleared existing data\n";
    
    // Create a clean SQL file with only the geographic data
    echo "Step 2: Creating clean SQL file...\n";
    $sqlFile = '/Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp/u367101322_uganda.sql';
    $sqlContent = file_get_contents($sqlFile);
    
    // Extract only the geographic data INSERT statements
    $cleanSQL = '';
    
    // Add districts
    $startPos = strpos($sqlContent, 'INSERT INTO `districts`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $districtsStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    $districtsStatement = rtrim($districtsStatement, " \t\n\r\0\x0B;");
    $cleanSQL .= $districtsStatement . ";\n\n";
    
    // Add counties
    $startPos = strpos($sqlContent, 'INSERT INTO `counties`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $countiesStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    $countiesStatement = rtrim($countiesStatement, " \t\n\r\0\x0B;");
    $cleanSQL .= $countiesStatement . ";\n\n";
    
    // Add subcounties
    $startPos = strpos($sqlContent, 'INSERT INTO `sub_counties`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $subcountiesStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    $subcountiesStatement = rtrim($subcountiesStatement, " \t\n\r\0\x0B;");
    $cleanSQL .= $subcountiesStatement . ";\n\n";
    
    // Add parishes
    $startPos = strpos($sqlContent, 'INSERT INTO `parishes`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $parishesStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    $parishesStatement = rtrim($parishesStatement, " \t\n\r\0\x0B;");
    $cleanSQL .= $parishesStatement . ";\n\n";
    
    // Add villages
    $startPos = strpos($sqlContent, 'INSERT INTO `villages`');
    $nextInsertPos = strpos($sqlContent, 'INSERT INTO `', $startPos + 1);
    $villagesStatement = substr($sqlContent, $startPos, $nextInsertPos - $startPos);
    $villagesStatement = rtrim($villagesStatement, " \t\n\r\0\x0B;");
    $cleanSQL .= $villagesStatement . ";\n\n";
    
    // Write the clean SQL file
    file_put_contents('/Applications/XAMPP/xamppfiles/htdocs/sunfuel/geographic_data_clean.sql', $cleanSQL);
    echo "✅ Created clean SQL file\n";
    
    // Import the data
    echo "Step 3: Importing geographic data...\n";
    $output = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' sunfuel < /Applications/XAMPP/xamppfiles/htdocs/sunfuel/geographic_data_clean.sql 2>&1");
    
    if (empty($output)) {
        echo "✅ Geographic data imported successfully!\n";
        
        // Verify the import
        echo "Step 4: Verifying import...\n";
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
    unlink('/Applications/XAMPP/xamppfiles/htdocs/sunfuel/geographic_data_clean.sql');
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
