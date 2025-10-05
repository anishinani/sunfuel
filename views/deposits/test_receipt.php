<?php
// Simple test to debug showReceipt.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing showReceipt.php...<br>";

// Test database connection
require_once("../../utils/dbaccess.php");
$dbAccess = new DbAccess();

echo "Database connection: OK<br>";

// Test query
$depositId = 1;
$depositDetails = $dbAccess->selectQuery("SELECT fuelstation.*, deposits.* FROM fuelstation 
    INNER JOIN deposits ON fuelstation.fuelStationId = deposits.fuelStationId 
    WHERE deposits.depositId = $depositId");

echo "Query executed. Results count: " . count($depositDetails) . "<br>";

if (!empty($depositDetails)) {
    echo "Deposit found: " . $depositDetails[0]['fuelStationName'] . "<br>";
    echo "Amount: " . $depositDetails[0]['amount'] . "<br>";
    echo "Receipt: " . $depositDetails[0]['receipt'] . "<br>";
} else {
    echo "No deposit found!<br>";
}

echo "Test completed.";
?>
