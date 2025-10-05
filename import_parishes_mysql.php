<?php
/**
 * Import Parishes Data using MySQL command line
 */

echo "Import Parishes Data using MySQL command line\n";
echo "============================================\n";

try {
    // Clear existing data
    echo "Step 1: Clearing existing parishes data...\n";
    $output = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' -e \"USE sunfuel; TRUNCATE TABLE parishes;\" 2>&1");
    echo "✅ Cleared existing parishes data\n";
    
    // Extract parishes data from the original SQL file
    echo "Step 2: Extracting parishes data...\n";
    $sqlFile = '/Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp/u367101322_uganda.sql';
    $output = shell_exec("cd /Applications/XAMPP/xamppfiles/htdocs/creditpluswebapp && grep -A 100000 'INSERT INTO \`parishes\`' u367101322_uganda.sql | grep -B 100000 'INSERT INTO \`villages\`' | sed '\$d' > /Applications/XAMPP/xamppfiles/htdocs/sunfuel/parishes_temp.sql 2>&1");
    
    if (file_exists('/Applications/XAMPP/xamppfiles/htdocs/sunfuel/parishes_temp.sql')) {
        echo "✅ Extracted parishes data\n";
        
        // Import the data
        echo "Step 3: Importing parishes data...\n";
        $output = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' sunfuel < /Applications/XAMPP/xamppfiles/htdocs/sunfuel/parishes_temp.sql 2>&1");
        
        if (empty($output)) {
            echo "✅ Parishes data imported successfully!\n";
            
            // Verify the import
            echo "Step 4: Verifying import...\n";
            $result = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' -e \"USE sunfuel; SELECT COUNT(*) as count FROM parishes;\" 2>&1");
            echo "Result: $result\n";
            
            // Test with KAWEMPE DIVISION
            $result = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' -e \"USE sunfuel; SELECT COUNT(*) as count FROM parishes WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1';\" 2>&1");
            echo "KAWEMPE DIVISION parishes: $result\n";
            
            if (strpos($result, '0') === false) {
                $result = shell_exec("/Applications/XAMPP/xamppfiles/bin/mysql -u root -p'!Log19tan88' -e \"USE sunfuel; SELECT parishName FROM parishes WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1' ORDER BY parishName;\" 2>&1");
                echo "KAWEMPE DIVISION parishes:\n$result\n";
            }
            
        } else {
            echo "❌ Failed to import parishes data: $output\n";
        }
        
        // Clean up
        unlink('/Applications/XAMPP/xamppfiles/htdocs/sunfuel/parishes_temp.sql');
        
    } else {
        echo "❌ Failed to extract parishes data\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
