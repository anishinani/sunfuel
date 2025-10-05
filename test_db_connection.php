<?php
/**
 * Test Database Connection
 * This script tests the database connection and shows what's available
 */

echo "<h2>Database Connection Test</h2>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;}</style>";

// Test different connection configurations
$configs = [
    ['host' => 'localhost', 'user' => 'root', 'pass' => '', 'db' => 'sunfuel'],
    ['host' => 'localhost', 'user' => 'root', 'pass' => '!Log19tan88', 'db' => 'sunfuel'],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => '', 'db' => 'sunfuel'],
    ['host' => '127.0.0.1', 'user' => 'root', 'pass' => '!Log19tan88', 'db' => 'sunfuel']
];

foreach ($configs as $i => $config) {
    echo "<h3>Test " . ($i + 1) . ": {$config['host']} / {$config['user']} / " . (empty($config['pass']) ? 'no password' : 'with password') . "</h3>";
    
    try {
        $conn = new mysqli($config['host'], $config['user'], $config['pass'], $config['db']);
        
        if ($conn->connect_error) {
            echo "<p class='error'>❌ Connection failed: " . $conn->connect_error . "</p>";
        } else {
            echo "<p class='success'>✅ Connection successful!</p>";
            
            // Test if we can query
            $result = $conn->query("SHOW TABLES");
            if ($result) {
                $tables = [];
                while ($row = $result->fetch_array()) {
                    $tables[] = $row[0];
                }
                echo "<p>Tables found: " . implode(', ', $tables) . "</p>";
                
                // Check if our geographic tables exist
                $geo_tables = ['districts', 'counties', 'sub_counties', 'parishes', 'villages', 'territory_districts'];
                $existing_geo = array_intersect($geo_tables, $tables);
                
                if (count($existing_geo) > 0) {
                    echo "<p class='success'>✅ Geographic tables found: " . implode(', ', $existing_geo) . "</p>";
                    
                    // Check data in territory_districts (what the form uses)
                    if (in_array('territory_districts', $existing_geo)) {
                        $result = $conn->query("SELECT COUNT(*) as count FROM territory_districts");
                        if ($result) {
                            $row = $result->fetch_assoc();
                            echo "<p>territory_districts has {$row['count']} records</p>";
                            
                            if ($row['count'] > 0) {
                                echo "<p class='success'>🎉 Your form should work! <a href='views/fuelstation/create.php' target='_blank'>Test it here</a></p>";
                            } else {
                                echo "<p class='warning'>⚠️ territory_districts is empty - form won't work yet</p>";
                            }
                        }
                    }
                } else {
                    echo "<p class='warning'>⚠️ No geographic tables found</p>";
                }
            }
            
            $conn->close();
            break; // Stop on first successful connection
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ Exception: " . $e->getMessage() . "</p>";
    }
}

echo "<h3>Recommendations</h3>";
echo "<ul>";
echo "<li>Make sure XAMPP is running (Apache and MySQL)</li>";
echo "<li>Check phpMyAdmin at <a href='http://127.0.0.1/phpmyadmin' target='_blank'>http://127.0.0.1/phpmyadmin</a></li>";
echo "<li>If database doesn't exist, create it first</li>";
echo "<li>If tables don't exist, run the import process</li>";
echo "</ul>";
?>
