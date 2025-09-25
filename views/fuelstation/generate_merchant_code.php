<?php
/**
 * Generate Merchant Code for Fuel Station
 * Returns a 6-digit incremental merchant code
 */

include_once '../../utils/dbaccess.php';

header('Content-Type: application/json');

try {
    $dbAccess = new DbAccess();
    
    // Get the highest existing merchant code
    $sql = "SELECT MAX(CAST(merchantCode AS UNSIGNED)) as maxCode FROM fuelstation WHERE merchantCode IS NOT NULL AND merchantCode REGEXP '^[0-9]+$'";
    $result = $dbAccess->selectQuery($sql);
    
    $nextCode = 1;
    if (!empty($result) && isset($result[0]['maxCode']) && $result[0]['maxCode'] !== null) {
        $nextCode = $result[0]['maxCode'] + 1;
    }
    
    // Ensure the code is at least 6 digits
    $merchantCode = str_pad($nextCode, 6, '0', STR_PAD_LEFT);
    
    // Check if this code already exists (safety check)
    $checkSql = "SELECT COUNT(*) as count FROM fuelstation WHERE merchantCode = '$merchantCode'";
    $checkResult = $dbAccess->selectQuery($checkSql);
    
    if ($checkResult[0]['count'] > 0) {
        // If code exists, find the next available one
        do {
            $nextCode++;
            $merchantCode = str_pad($nextCode, 6, '0', STR_PAD_LEFT);
            $checkResult = $dbAccess->selectQuery($checkSql);
        } while ($checkResult[0]['count'] > 0);
    }
    
    echo json_encode([
        'success' => true,
        'merchantCode' => $merchantCode
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
