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
    
    // Get recent loan activity
    $sql = "SELECT 
                fl.createdAt as time,
                b.bodaUserName as customer,
                fl.loanAmount as amount,
                fs.fuelStationName as station,
                fl.status,
                CASE 
                    WHEN fl.status = 'paid' THEN 'Paid'
                    WHEN fl.status = 'active' AND fl.dueDate >= CURDATE() THEN 'Active'
                    WHEN fl.status = 'active' AND fl.dueDate < CURDATE() THEN 'Overdue'
                    WHEN fl.status = 'overdue' THEN 'Overdue'
                    ELSE 'Unknown'
                END as statusText
            FROM fuel_loans fl
            LEFT JOIN bodauser b ON fl.bodaUserId = b.bodaUserId
            LEFT JOIN fuelstation fs ON fl.fuelStationId = fs.fuelStationId
            ORDER BY fl.createdAt DESC
            LIMIT 20";
    
    $activities = $db->selectQuery($sql);
    
    // Format the data
    $formattedActivities = [];
    foreach ($activities as $activity) {
        $formattedActivities[] = [
            'time' => date('H:i', strtotime($activity['time'])),
            'customer' => $activity['customer'],
            'amount' => number_format($activity['amount']) . ' UGX',
            'station' => $activity['station'],
            'status' => $activity['status'],
            'statusText' => $activity['statusText']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'activities' => $formattedActivities
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error loading data: ' . $e->getMessage()
    ]);
}
?>
