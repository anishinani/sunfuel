<?php
// Simple test to check JSON response
ob_start();

try {
    session_start();
    include("../../utils/dbaccess.php");
    $dbAccess = new DbAccess();
    
    $output = array(
        'draw' => 1,
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => array()
    );
    
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode($output);
    
} catch (\Throwable $th) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Test error: ' . $th->getMessage()]);
}
?>
