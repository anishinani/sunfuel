<?php
// Simple test to see what's happening
echo "PHP is working<br>";
echo "Current time: " . date('Y-m-d H:i:s') . "<br>";

// Test database connection
try {
    require_once("../../utils/dbaccess.php");
    $dbAccess = new DbAccess();
    echo "Database connection: OK<br>";
    
    // Test query
    $result = $dbAccess->selectQuery("SELECT COUNT(*) as count FROM deposits");
    echo "Deposits count: " . $result[0]['count'] . "<br>";
    
    // Test specific deposit
    $depositId = 1;
    $depositDetails = $dbAccess->selectQuery("SELECT fuelstation.*, deposits.* FROM fuelstation 
        INNER JOIN deposits ON fuelstation.fuelStationId = deposits.fuelStationId 
        WHERE deposits.depositId = $depositId");
    
    if (!empty($depositDetails)) {
        echo "Deposit found: " . $depositDetails[0]['fuelStationName'] . "<br>";
        echo "Amount: " . $depositDetails[0]['amount'] . "<br>";
    } else {
        echo "No deposit found<br>";
    }
    
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "<br>";
}

echo "Test completed successfully!";
?>
