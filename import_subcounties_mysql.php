<?php
/**
 * Import Subcounties Data using MySQL command line
 */

echo "Import Subcounties Data using MySQL command line\n";
echo "===============================================\n";

try {
    // Clear existing data
    echo "Step 1: Clearing existing subcounties data...\n";
    $output = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' -e \"USE sunfuel; TRUNCATE TABLE sub_counties;\" 2>&1");
    echo "✅ Cleared existing data\n";
    
    // Extract subcounties data from the original SQL file
    echo "Step 2: Extracting subcounties data...\n";
    $sqlFile = '/Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp/u367101322_uganda.sql';
    $output = shell_exec("cd /Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp && grep -A 100000 'INSERT INTO \`sub_counties\`' u367101322_uganda.sql | grep -B 100000 'INSERT INTO \`parishes\`' | sed '\$d' > /Applications/XAMPP/xamppfiles/htdocs/sunfuel/subcounties_temp.sql 2>&1");
    
    if (file_exists('/Applications/XAMPP/xamppfiles/htdocs/sunfuel/subcounties_temp.sql')) {
        echo "✅ Extracted subcounties data\n";
        
        // Import the data
        echo "Step 3: Importing subcounties data...\n";
        $output = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' sunfuel < /Applications/XAMPP/xamppfiles/htdocs/sunfuel/subcounties_temp.sql 2>&1");
        
        if (empty($output)) {
            echo "✅ Subcounties data imported successfully!\n";
            
            // Verify the import
            echo "Step 4: Verifying import...\n";
            $result = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' -e \"USE sunfuel; SELECT COUNT(*) as count FROM sub_counties;\" 2>&1");
            echo "Result: $result\n";
            
            // Test with WAKISO
            $result = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' -e \"USE sunfuel; SELECT COUNT(*) as count FROM sub_counties WHERE districtCode = '52';\" 2>&1");
            echo "WAKISO subcounties: $result\n";
            
            // Test with KIRA MUNICIPALITY
            $result = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' -e \"USE sunfuel; SELECT COUNT(*) as count FROM sub_counties WHERE districtCode = '52' AND countyCode = '240';\" 2>&1");
            echo "KIRA MUNICIPALITY subcounties: $result\n";
            
        } else {
            echo "❌ Failed to import subcounties data: $output\n";
        }
        
        // Clean up
        unlink('/Applications/XAMPP/xamppfiles/htdocs/sunfuel/subcounties_temp.sql');
        
    } else {
        echo "❌ Failed to extract subcounties data\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
