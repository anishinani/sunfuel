<?php
/**
 * Quick Fix for Geographic Data
 * Simple script to fix the data import issue
 */

// Set up basic environment for CLI
if (!isset($_SERVER['REMOTE_ADDR'])) {
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
}

require_once 'utils/dbaccess.php';

echo "🔧 Quick Fix for Geographic Data\n";
echo "================================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Check if we have the right data structure
    echo "\n📊 Checking current data...\n";
    
    // Check districts
    $districts = $dbAccess->select("districts", "", [], "LIMIT 5");
    echo "Districts found: " . count($districts) . "\n";
    
    // Check Kampala specifically
    $kampala = $dbAccess->select("districts", "", ["districtName" => "KAMPALA"]);
    if (count($kampala) > 0) {
        $kampala_id = $kampala[0]['id'];
        echo "✅ Kampala found with ID: $kampala_id\n";
        
        // Check counties for Kampala
        $counties = $dbAccess->select("counties", "", ["districtCode" => $kampala_id]);
        echo "✅ Counties for Kampala: " . count($counties) . "\n";
        
        if (count($counties) > 0) {
            echo "First few counties:\n";
            foreach (array_slice($counties, 0, 5) as $county) {
                echo "  - {$county['countyName']}\n";
            }
        }
    } else {
        echo "❌ Kampala district not found\n";
    }
    
    // Check territory_districts
    $territory_districts = $dbAccess->select("territory_districts", "", [], "LIMIT 5");
    echo "\nTerritory districts found: " . count($territory_districts) . "\n";
    
    if (count($territory_districts) > 0) {
        echo "First few territory districts:\n";
        foreach ($territory_districts as $td) {
            echo "  - ID: {$td['id']}, Name: {$td['districtName']}\n";
        }
    }
    
    echo "\n✅ Data check completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
