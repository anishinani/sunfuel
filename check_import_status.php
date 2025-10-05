<?php
/**
 * Check Import Status
 * This script checks the current status of the geographic data import
 */

require_once 'utils/dbaccess.php';

echo "<h2>Import Status Check</h2>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background-color:#f2f2f2;}</style>";

try {
    $dbAccess = new DbAccess();
    echo "<p class='success'>✅ Database connection successful</p>";
    
    // Check which tables exist and have data
    $tables = [
        'districts' => 'Districts',
        'counties' => 'Counties', 
        'sub_counties' => 'Sub Counties',
        'parishes' => 'Parishes',
        'villages' => 'Villages',
        'territory_districts' => 'Territory Districts (for form)'
    ];
    
    echo "<h3>Current Status</h3>";
    echo "<table>";
    echo "<tr><th>Table</th><th>Status</th><th>Record Count</th><th>Sample Data</th></tr>";
    
    foreach ($tables as $table => $description) {
        try {
            $data = $dbAccess->select($table);
            $count = count($data);
            
            if ($count > 0) {
                echo "<tr class='success'>";
                echo "<td>$description</td>";
                echo "<td>✅ Has data</td>";
                echo "<td>$count records</td>";
                
                // Show sample data
                $sample = array_slice($data, 0, 2);
                $sampleText = "";
                foreach ($sample as $row) {
                    if (isset($row['districtName'])) {
                        $sampleText .= $row['districtName'] . ", ";
                    } elseif (isset($row['countyName'])) {
                        $sampleText .= $row['countyName'] . ", ";
                    } elseif (isset($row['subCountyName'])) {
                        $sampleText .= $row['subCountyName'] . ", ";
                    } elseif (isset($row['parishName'])) {
                        $sampleText .= $row['parishName'] . ", ";
                    } elseif (isset($row['villageName'])) {
                        $sampleText .= $row['villageName'] . ", ";
                    }
                }
                $sampleText = rtrim($sampleText, ", ");
                echo "<td>$sampleText</td>";
            } else {
                echo "<tr class='warning'>";
                echo "<td>$description</td>";
                echo "<td>⚠️ Empty</td>";
                echo "<td>0 records</td>";
                echo "<td>No data</td>";
            }
            echo "</tr>";
            
        } catch (Exception $e) {
            echo "<tr class='error'>";
            echo "<td>$description</td>";
            echo "<td>❌ Does not exist</td>";
            echo "<td>N/A</td>";
            echo "<td>Error: " . $e->getMessage() . "</td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
    
    // Check if the form will work
    echo "<h3>Form Compatibility Check</h3>";
    try {
        $districts = $dbAccess->select("territory_districts");
        if (count($districts) > 0) {
            echo "<p class='success'>✅ Form should work - territory_districts has " . count($districts) . " records</p>";
            echo "<p><a href='views/fuelstation/create.php' target='_blank'>Test the form now</a></p>";
        } else {
            echo "<p class='error'>❌ Form will not work - territory_districts is empty</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ territory_districts table issue: " . $e->getMessage() . "</p>";
    }
    
    // Recommendations
    echo "<h3>Next Steps</h3>";
    if (count($districts) == 0) {
        echo "<div class='warning'>";
        echo "<p><strong>⚠️ Import may still be running or failed</strong></p>";
        echo "<p><strong>Options:</strong></p>";
        echo "<ul>";
        echo "<li><a href='import_real_geographic_data.php' target='_blank'>Try import again</a></li>";
        echo "<li><a href='run_import.php' target='_blank'>Use web interface</a></li>";
        echo "<li>Check browser console for any JavaScript errors</li>";
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div class='success'>";
        echo "<p><strong>🎉 Import appears successful!</strong></p>";
        echo "<p>Your fuel station form should now work with real Uganda data.</p>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}
?>
