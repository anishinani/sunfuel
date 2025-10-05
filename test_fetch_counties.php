<?php
/**
 * Test Fetch Counties
 * This simulates what the form is doing when you select Kampala
 */

echo "<h2>🧪 Testing Fetch Counties</h2>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;}</style>";

// Simulate the POST request that the form makes
$_POST['action'] = 'fetch';
$_POST['district'] = '1'; // Assuming Kampala has ID 1 in territory_districts

echo "<p>Simulating POST request with district ID: 1</p>";

// Include the fetchcounties.php file
ob_start();
include 'views/fuelstation/fetchcounties.php';
$output = ob_get_clean();

echo "<h3>Raw Output from fetchcounties.php:</h3>";
echo "<pre>" . htmlspecialchars($output) . "</pre>";

// Try to decode the JSON
$data = json_decode($output, true);
if ($data !== null) {
    echo "<h3>Decoded JSON Data:</h3>";
    echo "<p>Number of counties: " . count($data) . "</p>";
    
    if (count($data) > 0) {
        echo "<table border='1' style='border-collapse:collapse;'>";
        echo "<tr><th>ID</th><th>County Code</th><th>County Name</th><th>District Code</th></tr>";
        foreach ($data as $county) {
            echo "<tr>";
            echo "<td>" . (isset($county['id']) ? $county['id'] : 'N/A') . "</td>";
            echo "<td>" . (isset($county['countyCode']) ? $county['countyCode'] : 'N/A') . "</td>";
            echo "<td>" . (isset($county['countyName']) ? $county['countyName'] : 'N/A') . "</td>";
            echo "<td>" . (isset($county['districtCode']) ? $county['districtCode'] : 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "<p class='error'>❌ Failed to decode JSON. Raw output: " . htmlspecialchars($output) . "</p>";
}

// Let's also test with different district IDs
echo "<h3>Testing with different district IDs:</h3>";

for ($i = 1; $i <= 5; $i++) {
    $_POST['district'] = $i;
    
    ob_start();
    include 'views/fuelstation/fetchcounties.php';
    $output = ob_get_clean();
    
    $data = json_decode($output, true);
    if ($data !== null && count($data) > 0) {
        echo "<p>District ID $i: " . count($data) . " counties - " . $data[0]['countyName'] . " (and " . (count($data)-1) . " more)</p>";
    } else {
        echo "<p>District ID $i: No counties or error</p>";
    }
}
?>
