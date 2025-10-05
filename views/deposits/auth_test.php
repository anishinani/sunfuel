<?php
session_start();
echo "Session started<br>";

if (isset($_SESSION['user'])) {
    echo "User logged in: " . $_SESSION['user'] . "<br>";
} else {
    echo "No user in session<br>";
}

if (isset($_SESSION['permissions'])) {
    echo "Permissions: " . implode(', ', $_SESSION['permissions']) . "<br>";
} else {
    echo "No permissions in session<br>";
}

// Test the can function
include_once '../templates/Components.php';

if (function_exists('can')) {
    echo "can() function exists<br>";
    if (can('view-deposit-receipts')) {
        echo "User CAN view deposit receipts<br>";
    } else {
        echo "User CANNOT view deposit receipts<br>";
    }
} else {
    echo "can() function does not exist<br>";
}

echo "Auth test completed.";
?>
