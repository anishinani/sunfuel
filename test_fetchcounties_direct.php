<?php
/**
 * Test fetchcounties.php directly
 */

// Simulate POST data for Kampala (ID 49)
$_POST['district'] = 49;
$_POST['action'] = 'fetch';

// Capture output
ob_start();
include 'views/fuelstation/fetchcounties.php';
$output = ob_get_clean();

echo "Testing fetchcounties.php with Kampala ID (49):\n";
echo "===============================================\n";
echo "Output: " . $output . "\n";

// Try to decode as JSON
$decoded = json_decode($output, true);
if ($decoded !== null) {
    echo "\nDecoded JSON:\n";
    print_r($decoded);
    
    if (is_array($decoded) && count($decoded) > 0) {
        echo "\nCounties found: " . count($decoded) . "\n";
        foreach ($decoded as $county) {
            echo "- {$county['countyName']}\n";
        }
    }
} else {
    echo "\n❌ Output is not valid JSON\n";
}
?>
