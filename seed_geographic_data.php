<?php
/**
 * Geographic Data Seeder
 * This script populates the database with Uganda's geographic data
 * Run this script once to set up the geographic tables and data
 */

require_once 'utils/dbaccess.php';

try {
    $dbAccess = new DbAccess();
    
    echo "Starting geographic data seeding...\n";
    
    // Read and execute migration files in order
    $migrationFiles = [
        'migrations/021_create_county_table.sql',
        'migrations/022_create_subcounty_table.sql', 
        'migrations/023_create_parishes_table.sql',
        'migrations/024_create_villages_table.sql',
        'migrations/025_seed_uganda_geographic_data.sql'
    ];
    
    foreach ($migrationFiles as $file) {
        if (file_exists($file)) {
            echo "Executing: $file\n";
            $sql = file_get_contents($file);
            
            // Split SQL into individual statements
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($statements as $statement) {
                if (!empty($statement) && !preg_match('/^--/', $statement)) {
                    try {
                        $dbAccess->execute($statement);
                        echo "✓ Executed statement successfully\n";
                    } catch (Exception $e) {
                        echo "⚠ Warning: " . $e->getMessage() . "\n";
                    }
                }
            }
        } else {
            echo "⚠ File not found: $file\n";
        }
    }
    
    echo "\n✅ Geographic data seeding completed!\n";
    echo "You can now use the fuel station registration form with populated geographic data.\n";
    
    // Display summary
    echo "\n📊 Data Summary:\n";
    
    $territories = $dbAccess->select("territories");
    echo "- Territories: " . count($territories) . "\n";
    
    $districts = $dbAccess->select("territory_districts");
    echo "- Districts: " . count($districts) . "\n";
    
    $counties = $dbAccess->select("county");
    echo "- Counties: " . count($counties) . "\n";
    
    $subcounties = $dbAccess->select("subcounty");
    echo "- Subcounties: " . count($subcounties) . "\n";
    
    $parishes = $dbAccess->select("parishes");
    echo "- Parishes: " . count($parishes) . "\n";
    
    $villages = $dbAccess->select("villages");
    echo "- Villages: " . count($villages) . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Please check your database connection and try again.\n";
}
?>
