<?php
/**
 * Debug Counties Issue
 * Let's see what's actually happening with the data
 */

require_once 'utils/dbaccess.php';

echo "<h2>🔍 Debugging Counties Issue</h2>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background-color:#f2f2f2;}</style>";

try {
    $dbAccess = new DbAccess();
    echo "<p class='success'>✅ Connected to database</p>";
    
    // Step 1: Check what's in territory_districts for Kampala
    echo "<h3>Step 1: Territory Districts for Kampala</h3>";
    $territory_districts = $dbAccess->select("territory_districts", "", ["districtName" => "KAMPALA"]);
    if (count($territory_districts) > 0) {
        foreach ($territory_districts as $td) {
            echo "<p>Territory District ID: {$td['id']} - {$td['districtName']}</p>";
        }
    } else {
        echo "<p class='error'>❌ No Kampala in territory_districts</p>";
    }
    
    // Step 2: Check what's in districts table for Kampala
    echo "<h3>Step 2: Districts Table for Kampala</h3>";
    $districts = $dbAccess->select("districts", "", ["districtName" => "KAMPALA"]);
    if (count($districts) > 0) {
        foreach ($districts as $district) {
            echo "<p>District ID: {$district['id']} - {$district['districtName']}</p>";
        }
    } else {
        echo "<p class='error'>❌ No Kampala in districts table</p>";
    }
    
    // Step 3: Check what counties exist for the first Kampala district
    if (count($districts) > 0) {
        $kampala_district_id = $districts[0]['id'];
        echo "<h3>Step 3: Counties for Kampala District ID: $kampala_district_id</h3>";
        
        $counties = $dbAccess->select("counties", "", ["districtCode" => $kampala_district_id]);
        if (count($counties) > 0) {
            echo "<p class='success'>✅ Found " . count($counties) . " counties for Kampala:</p>";
            echo "<table>";
            echo "<tr><th>ID</th><th>County Code</th><th>County Name</th><th>District Code</th></tr>";
            foreach ($counties as $county) {
                echo "<tr>";
                echo "<td>{$county['id']}</td>";
                echo "<td>{$county['countyCode']}</td>";
                echo "<td>{$county['countyName']}</td>";
                echo "<td>{$county['districtCode']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='error'>❌ No counties found for Kampala district ID: $kampala_district_id</p>";
        }
    }
    
    // Step 4: Let's see what counties exist in the database
    echo "<h3>Step 4: Sample Counties in Database</h3>";
    $all_counties = $dbAccess->select("counties", "", [], "LIMIT 20");
    if (count($all_counties) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>County Code</th><th>County Name</th><th>District Code</th></tr>";
        foreach ($all_counties as $county) {
            echo "<tr>";
            echo "<td>{$county['id']}</td>";
            echo "<td>{$county['countyCode']}</td>";
            echo "<td>{$county['countyName']}</td>";
            echo "<td>{$county['districtCode']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Step 5: Test the fetchcounties.php logic
    echo "<h3>Step 5: Testing fetchcounties.php Logic</h3>";
    if (count($territory_districts) > 0) {
        $territory_district_id = $territory_districts[0]['id'];
        echo "<p>Testing with territory_district_id: $territory_district_id</p>";
        
        // Simulate the fetchcounties.php logic
        $territory_district = $dbAccess->select("territory_districts", "", ["id" => $territory_district_id]);
        
        if (count($territory_district) > 0) {
            $district_name = $territory_district[0]["districtName"];
            echo "<p>District name from territory_districts: $district_name</p>";
            
            $district = $dbAccess->select("districts", "", ["districtName" => $district_name]);
            
            if (count($district) > 0) {
                $district_id = $district[0]["id"];
                echo "<p>District ID from districts table: $district_id</p>";
                
                $counties = $dbAccess->select("counties", "", ["districtCode" => $district_id]);
                echo "<p>Counties found: " . count($counties) . "</p>";
                
                if (count($counties) > 0) {
                    echo "<ul>";
                    foreach ($counties as $county) {
                        echo "<li>{$county['countyName']}</li>";
                    }
                    echo "</ul>";
                }
            } else {
                echo "<p class='error'>❌ No matching district found in districts table</p>";
            }
        }
    }
    
    // Step 6: Check if there are any counties with "ARINGA" or "BUNYA" in the name
    echo "<h3>Step 6: Searching for ARINGA and BUNYA</h3>";
    $aringa_counties = $dbAccess->select("counties", "", ["countyName" => "ARINGA NORTH"]);
    if (count($aringa_counties) > 0) {
        foreach ($aringa_counties as $county) {
            echo "<p>ARINGA NORTH found - District Code: {$county['districtCode']}</p>";
            
            // Find which district this belongs to
            $district = $dbAccess->select("districts", "", ["id" => $county['districtCode']]);
            if (count($district) > 0) {
                echo "<p>ARINGA NORTH belongs to district: {$district[0]['districtName']}</p>";
            }
        }
    }
    
    $bunya_counties = $dbAccess->select("counties", "", ["countyName" => "BUNYA"]);
    if (count($bunya_counties) > 0) {
        foreach ($bunya_counties as $county) {
            echo "<p>BUNYA found - District Code: {$county['districtCode']}</p>";
            
            // Find which district this belongs to
            $district = $dbAccess->select("districts", "", ["id" => $county['districtCode']]);
            if (count($district) > 0) {
                echo "<p>BUNYA belongs to district: {$district[0]['districtName']}</p>";
            }
        }
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
