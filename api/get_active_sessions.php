<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once '../utils/dbaccess.php';

// Check if user is logged in as admin
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'super_admin'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

try {
    $db = new DbAccess();
    
    // Get active USSD sessions
    $sql = "SELECT COUNT(*) as count 
            FROM ussd_sessions 
            WHERE status = 'active' 
            AND expiresAt > NOW()";
    
    $result = $db->selectQuery($sql);
    $count = !empty($result) ? $result[0]['count'] : 0;
    
    echo json_encode([
        'success' => true,
        'count' => $count
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error loading data: ' . $e->getMessage()
    ]);
}
?>
