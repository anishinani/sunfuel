<?php
/**
 * Check District Mapping
 * Let's see what district ID 1 actually is
 */

require_once 'utils/dbaccess.php';

echo "<h2>🔍 Checking District Mapping</h2>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background-color:#f2f2f2;}</style>";

try {
    $dbAccess = new DbAccess();
    echo "<p class='success'>✅ Connected to database</p>";
    
    // Check what district ID 1 is in territory_districts
    echo "<h3>Territory Districts (what the form uses):</h3>";
    $territory_districts = $dbAccess->select("territory_districts", "", [], "LIMIT 10");
    if (count($territory_districts) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>District Name</th></tr>";
        foreach ($territory_districts as $td) {
            echo "<tr>";
            echo "<td>{$td['id']}</td>";
            echo "<td>{$td['districtName']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Check what district ID 1 is in districts table
    echo "<h3>Districts Table (what counties reference):</h3>";
    $districts = $dbAccess->select("districts", "", [], "LIMIT 10");
    if (count($districts) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>District Code</th><th>District Name</th></tr>";
        foreach ($districts as $district) {
            echo "<tr>";
            echo "<td>{$district['id']}</td>";
            echo "<td>{$district['districtCode']}</td>";
            echo "<td>{$district['districtName']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Check what counties belong to district ID 1
    echo "<h3>Counties for District ID 1:</h3>";
    $counties = $dbAccess->select("counties", "", ["districtCode" => "1"]);
    if (count($counties) > 0) {
        echo "<p>Found " . count($counties) . " counties for district ID 1:</p>";
        echo "<ul>";
        foreach ($counties as $county) {
            echo "<li>{$county['countyName']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='error'>No counties found for district ID 1</p>";
    }
    
    // Find the actual Kampala district
    echo "<h3>Finding Kampala District:</h3>";
    $kampala_districts = $dbAccess->select("districts", "", ["districtName" => "KAMPALA"]);
    if (count($kampala_districts) > 0) {
        foreach ($kampala_districts as $kampala) {
            echo "<p>Kampala found - ID: {$kampala['id']}, Code: {$kampala['districtCode']}, Name: {$kampala['districtName']}</p>";
            
            // Check counties for this Kampala district
            $kampala_counties = $dbAccess->select("counties", "", ["districtCode" => $kampala['id']]);
            if (count($kampala_counties) > 0) {
                echo "<p>Counties for Kampala (ID {$kampala['id']}):</p>";
                echo "<ul>";
                foreach ($kampala_counties as $county) {
                    echo "<li>{$county['countyName']}</li>";
                }
                echo "</ul>";
            }
        }
    } else {
        echo "<p class='error'>No Kampala district found in districts table</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
