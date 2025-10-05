<?php
/**
 * Test connection with socket path
 */

echo "Testing database connection with socket...\n";

// Test with socket path
echo "Test: Connection with socket path\n";
try {
    $socket = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';
    $conn = new mysqli('localhost', 'root', '!Log19tan88', 'sunfuel', 3306, $socket);
    
    if ($conn->connect_error) {
        echo "❌ Connection failed: " . $conn->connect_error . "\n";
    } else {
        echo "✅ Connection with socket successful\n";
        
        // Test a simple query
        $result = $conn->query("SELECT COUNT(*) as count FROM districts");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "✅ Query successful: " . $row['count'] . " districts found\n";
        } else {
            echo "❌ Query failed: " . $conn->error . "\n";
        }
        
        $conn->close();
    }
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}

// Test with 127.0.0.1 instead of localhost
echo "\nTest: Connection with 127.0.0.1\n";
try {
    $conn = new mysqli('127.0.0.1', 'root', '!Log19tan88', 'sunfuel');
    
    if ($conn->connect_error) {
        echo "❌ Connection failed: " . $conn->connect_error . "\n";
    } else {
        echo "✅ Connection with 127.0.0.1 successful\n";
        
        // Test a simple query
        $result = $conn->query("SELECT COUNT(*) as count FROM districts");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "✅ Query successful: " . $row['count'] . " districts found\n";
        } else {
            echo "❌ Query failed: " . $conn->error . "\n";
        }
        
        $conn->close();
    }
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
}

echo "\nConnection test completed.\n";
?>
