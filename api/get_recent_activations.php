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

// Check if user is logged in as fuel agent
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'agent') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

try {
    $db = new DbAccess();
    $agentId = $_SESSION['user_id'];
    
    // Get recent activations for this agent's station
    $sql = "SELECT 
                fac.activationCode as code,
                fac.usedAt as time,
                b.bodaUserName as customer,
                fac.fuelAmount as amount,
                fac.status
            FROM fuel_activation_codes fac
            LEFT JOIN bodauser b ON fac.bodaUserId = b.bodaUserId
            LEFT JOIN fuelagent fa ON fac.fuelStationId = fa.stationId
            WHERE fa.fuelAgentId = {$agentId}
            AND fac.status = 'used'
            ORDER BY fac.usedAt DESC
            LIMIT 10";
    
    $activations = $db->selectQuery($sql);
    
    // Format the data
    $formattedActivations = [];
    foreach ($activations as $activation) {
        $formattedActivations[] = [
            'code' => $activation['code'],
            'time' => date('H:i', strtotime($activation['time'])),
            'customer' => $activation['customer'],
            'amount' => number_format($activation['amount']) . ' UGX',
            'status' => $activation['status']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'activations' => $formattedActivations
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error loading data: ' . $e->getMessage()
    ]);
}
?>
