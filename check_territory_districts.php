<?php
/**
 * Check Territory Districts Mapping
 */

require_once 'utils/dbaccess.php';

echo "<h2>🔍 Checking Territory Districts</h2>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background-color:#f2f2f2;}</style>";

try {
    $dbAccess = new DbAccess();
    echo "<p class='success'>✅ Connected to database</p>";
    
    // Get all territory districts
    $territory_districts = $dbAccess->select("territory_districts", "", [], "ORDER BY id");
    
    echo "<h3>All Territory Districts (what the form uses):</h3>";
    echo "<table>";
    echo "<tr><th>ID</th><th>District Name</th></tr>";
    
    foreach ($territory_districts as $td) {
        $highlight = ($td['districtName'] == 'KAMPALA') ? 'style="background-color: #ffffcc;"' : '';
        echo "<tr $highlight>";
        echo "<td>{$td['id']}</td>";
        echo "<td>{$td['districtName']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Find Kampala specifically
    $kampala_territory = $dbAccess->select("territory_districts", "", ["districtName" => "KAMPALA"]);
    if (count($kampala_territory) > 0) {
        $kampala_territory_id = $kampala_territory[0]['id'];
        echo "<p class='success'>✅ Kampala in territory_districts has ID: <strong>$kampala_territory_id</strong></p>";
        
        // Now check what counties should be returned for this ID
        echo "<h3>Testing: What happens when form sends ID $kampala_territory_id?</h3>";
        
        // Get the district name from territory_districts
        $territory_district = $dbAccess->select("territory_districts", "", ["id" => $kampala_territory_id]);
        
        if (count($territory_district) > 0) {
            $district_name = $territory_district[0]["districtName"];
            echo "<p>Step 1: territory_districts ID $kampala_territory_id = '$district_name'</p>";
            
            // Find the matching district in the districts table
            $district = $dbAccess->select("districts", "", ["districtName" => $district_name]);
            
            if (count($district) > 0) {
                $district_id = $district[0]["id"];
                echo "<p>Step 2: Found '$district_name' in districts table with ID: $district_id</p>";
                
                // Get counties for this district
                $counties = $dbAccess->select("counties", "", ["districtCode" => $district_id]);
                echo "<p>Step 3: Counties for '$district_name' (ID $district_id): " . count($counties) . "</p>";
                
                if (count($counties) > 0) {
                    echo "<ul>";
                    foreach ($counties as $county) {
                        echo "<li>{$county['countyName']}</li>";
                    }
                    echo "</ul>";
                }
            } else {
                echo "<p class='error'>❌ '$district_name' not found in districts table</p>";
            }
        }
    } else {
        echo "<p class='error'>❌ Kampala not found in territory_districts</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
