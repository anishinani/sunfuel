<?php
/**
 * Database Diagnostic Script
 * This script checks the current state of the geographic tables
 */

require_once 'utils/dbaccess.php';

echo "<h2>Database Diagnostic Report</h2>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:8px;text-align:left;} th{background-color:#f2f2f2;}</style>";

try {
    $dbAccess = new DbAccess();
    echo "<p class='success'>✅ Database connection successful</p>";
    
    // Check if tables exist and have data
    $tables = [
        'territories' => 'Territories (Regions)',
        'territory_districts' => 'Districts',
        'county' => 'Counties',
        'subcounty' => 'Subcounties',
        'parishes' => 'Parishes',
        'villages' => 'Villages'
    ];
    
    echo "<h3>Table Status</h3>";
    echo "<table>";
    echo "<tr><th>Table</th><th>Description</th><th>Status</th><th>Record Count</th><th>Sample Data</th></tr>";
    
    foreach ($tables as $table => $description) {
        try {
            $data = $dbAccess->select($table);
            $count = count($data);
            
            if ($count > 0) {
                echo "<tr class='success'>";
                echo "<td>$table</td>";
                echo "<td>$description</td>";
                echo "<td>✅ Exists with data</td>";
                echo "<td>$count records</td>";
                
                // Show sample data
                $sample = array_slice($data, 0, 2);
                $sampleText = "";
                foreach ($sample as $row) {
                    if (isset($row['territoryName'])) {
                        $sampleText .= $row['territoryName'] . ", ";
                    } elseif (isset($row['districtName'])) {
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
                echo "<td>$table</td>";
                echo "<td>$description</td>";
                echo "<td>⚠️ Exists but empty</td>";
                echo "<td>0 records</td>";
                echo "<td>No data</td>";
            }
            echo "</tr>";
            
        } catch (Exception $e) {
            echo "<tr class='error'>";
            echo "<td>$table</td>";
            echo "<td>$description</td>";
            echo "<td>❌ Does not exist</td>";
            echo "<td>N/A</td>";
            echo "<td>Error: " . $e->getMessage() . "</td>";
            echo "</tr>";
        }
    }
    
    echo "</table>";
    
    // Check what the form is actually trying to load
    echo "<h3>Form Data Check</h3>";
    try {
        $districts = $dbAccess->select("territory_districts");
        echo "<p><strong>Districts for form:</strong> " . count($districts) . " records</p>";
        
        if (count($districts) > 0) {
            echo "<p class='success'>✅ Form should be able to load districts</p>";
            echo "<ul>";
            foreach (array_slice($districts, 0, 5) as $district) {
                echo "<li>ID: " . $district['id'] . " - " . $district['districtName'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p class='error'>❌ No districts found - form will be empty</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ Error loading districts: " . $e->getMessage() . "</p>";
    }
    
    // Recommendations
    echo "<h3>Recommendations</h3>";
    if (count($districts) == 0) {
        echo "<div class='warning'>";
        echo "<p><strong>⚠️ Action Required:</strong> The territory_districts table is empty.</p>";
        echo "<p><strong>Solution:</strong> Run the geographic data setup:</p>";
        echo "<ul>";
        echo "<li><a href='setup_geographic_data.php' target='_blank'>Run Setup (Web Interface)</a></li>";
        echo "<li>Or run: <code>php seed_geographic_data.php</code></li>";
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<p class='success'>✅ Database appears to be properly set up</p>";
        echo "<p>If the form is still not working, check:</p>";
        echo "<ul>";
        echo "<li>JavaScript console for errors</li>";
        echo "<li>Network tab for failed AJAX requests</li>";
        echo "<li>PHP error logs</li>";
        echo "</ul>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration in utils/dbaccess.php</p>";
}
?>
