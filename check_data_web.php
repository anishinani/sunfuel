<?php
/**
 * Check Data via Web Interface
 * This will work in the browser where $_SERVER is available
 */

require_once 'utils/dbaccess.php';

echo "<h2>🔍 Checking Geographic Data</h2>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background-color:#f2f2f2;}</style>";

try {
    $dbAccess = new DbAccess();
    echo "<p class='success'>✅ Connected to database</p>";
    
    // Check what's in territory_districts
    echo "<h3>Territory Districts (Form Data):</h3>";
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
    
    // Check what's in districts table
    echo "<h3>Districts Table (Imported Data):</h3>";
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
    
    // Find Kampala in both tables
    echo "<h3>Kampala District Mapping:</h3>";
    
    $kampala_territory = $dbAccess->select("territory_districts", "", ["districtName" => "KAMPALA"]);
    if (count($kampala_territory) > 0) {
        $kampala_territory_id = $kampala_territory[0]['id'];
        echo "<p>✅ Kampala in territory_districts: ID = $kampala_territory_id</p>";
    }
    
    $kampala_district = $dbAccess->select("districts", "", ["districtName" => "KAMPALA"]);
    if (count($kampala_district) > 0) {
        $kampala_district_id = $kampala_district[0]['id'];
        echo "<p>✅ Kampala in districts table: ID = $kampala_district_id</p>";
        
        // Check counties for this Kampala
        $counties = $dbAccess->select("counties", "", ["districtCode" => $kampala_district_id]);
        echo "<p>✅ Counties for Kampala (ID $kampala_district_id): " . count($counties) . "</p>";
        
        if (count($counties) > 0) {
            echo "<ul>";
            foreach (array_slice($counties, 0, 10) as $county) {
                echo "<li>{$county['countyName']}</li>";
            }
            if (count($counties) > 10) {
                echo "<li>... and " . (count($counties) - 10) . " more</li>";
            }
            echo "</ul>";
        }
    }
    
    // Test the current fetchcounties logic
    echo "<h3>Testing Current Fetch Logic:</h3>";
    echo "<p>If territory_districts ID 1 = KAMPALA, what counties should be returned?</p>";
    
    $test_territory = $dbAccess->select("territory_districts", "", ["id" => 1]);
    if (count($test_territory) > 0) {
        $test_district_name = $test_territory[0]["districtName"];
        echo "<p>Territory ID 1 = '$test_district_name'</p>";
        
        $test_district = $dbAccess->select("districts", "", ["districtName" => $test_district_name]);
        if (count($test_district) > 0) {
            $test_district_id = $test_district[0]["id"];
            echo "<p>Found '$test_district_name' in districts table with ID: $test_district_id</p>";
            
            $test_counties = $dbAccess->select("counties", "", ["districtCode" => $test_district_id]);
            echo "<p>Counties for '$test_district_name': " . count($test_counties) . "</p>";
            
            if (count($test_counties) > 0) {
                echo "<ul>";
                foreach (array_slice($test_counties, 0, 5) as $county) {
                    echo "<li>{$county['countyName']}</li>";
                }
                echo "</ul>";
            }
        }
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
