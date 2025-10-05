<?php
/**
 * Import Test Subcounties Data
 * This script will import some test subcounties data for WAKISO/KIRA MUNICIPALITY
 */

require_once 'utils/dbaccess.php';

echo "Import Test Subcounties Data\n";
echo "============================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Clear existing data
    echo "Step 1: Clearing existing subcounties data...\n";
    $dbAccess->conn->query("TRUNCATE TABLE sub_counties");
    echo "✅ Cleared existing data\n";
    
    // Insert test data for WAKISO/KIRA MUNICIPALITY
    echo "Step 2: Inserting test subcounties data...\n";
    
    $testData = [
        [
            'id' => 1039,
            'uuid' => 'da983913-87e9-4cbf-aaac-6bacfe894fd5',
            'districtCode' => '52',
            'countyCode' => '240',
            'subCountyCode' => '1',
            'subCountyName' => 'KIRA DIVISION',
            'deleted_at' => null,
            'created_at' => '2022-01-01 00:24:14',
            'updated_at' => '2023-11-13 14:35:37'
        ],
        [
            'id' => 1564,
            'uuid' => 'fecdc875-80a5-46f5-b54f-1025fdb46420',
            'districtCode' => '52',
            'countyCode' => '240',
            'subCountyCode' => '3',
            'subCountyName' => 'NAMUGONGO DIVISION',
            'deleted_at' => null,
            'created_at' => '2022-01-01 00:24:33',
            'updated_at' => '2023-11-13 14:35:40'
        ],
        [
            'id' => 4940,
            'uuid' => '2a099b49-1815-44df-8aac-4a3c7598afa7',
            'districtCode' => '52',
            'countyCode' => '240',
            'subCountyCode' => '1',
            'subCountyName' => 'KIRA WARD',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:21:57',
            'updated_at' => '2023-11-13 14:36:15'
        ],
        [
            'id' => 33265,
            'uuid' => '86b9fd96-5352-416b-b141-97c93d251879',
            'districtCode' => '52',
            'countyCode' => '240',
            'subCountyCode' => '1',
            'subCountyName' => 'KIRA',
            'deleted_at' => null,
            'created_at' => '2022-01-01 04:40:10',
            'updated_at' => '2023-11-13 14:39:59'
        ],
        [
            'id' => 50761,
            'uuid' => '5e208acc-d7d7-4edc-b5a6-08ebd0ef6b2a',
            'districtCode' => '52',
            'countyCode' => '240',
            'subCountyCode' => '3',
            'subCountyName' => 'NAMUGONGO-BULOLI',
            'deleted_at' => null,
            'created_at' => '2022-01-01 05:02:55',
            'updated_at' => '2023-11-13 14:41:33'
        ]
    ];
    
    $inserted = 0;
    foreach ($testData as $data) {
        $sql = "INSERT INTO sub_counties (id, uuid, districtCode, countyCode, subCountyCode, subCountyName, deleted_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $dbAccess->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("issssssss", 
                $data['id'], 
                $data['uuid'], 
                $data['districtCode'], 
                $data['countyCode'], 
                $data['subCountyCode'], 
                $data['subCountyName'], 
                $data['deleted_at'], 
                $data['created_at'], 
                $data['updated_at']
            );
            
            if ($stmt->execute()) {
                $inserted++;
                echo "✅ Inserted: " . $data['subCountyName'] . "\n";
            } else {
                echo "❌ Failed to insert: " . $data['subCountyName'] . " - " . $stmt->error . "\n";
            }
            $stmt->close();
        } else {
            echo "❌ Failed to prepare statement: " . $dbAccess->conn->error . "\n";
        }
    }
    
    echo "Step 3: Import completed!\n";
    echo "✅ Total subcounties inserted: $inserted\n";
    
    // Verify the import
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM sub_counties");
    $row = $result->fetch_assoc();
    echo "✅ Verification: " . $row['count'] . " subcounties in database\n";
    
    // Test with WAKISO
    echo "\nStep 4: Testing with WAKISO...\n";
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM sub_counties WHERE districtCode = '52'");
    $row = $result->fetch_assoc();
    echo "✅ WAKISO subcounties: " . $row['count'] . "\n";
    
    // Test with KIRA MUNICIPALITY
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM sub_counties WHERE districtCode = '52' AND countyCode = '240'");
    $row = $result->fetch_assoc();
    echo "✅ KIRA MUNICIPALITY subcounties: " . $row['count'] . "\n";
    
    if ($row['count'] > 0) {
        echo "✅ SUCCESS! Test subcounties data has been imported!\n";
        
        // Show the imported subcounties
        $result = $dbAccess->conn->query("SELECT subCountyName FROM sub_counties WHERE districtCode = '52' AND countyCode = '240'");
        echo "\nKIRA MUNICIPALITY subcounties:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['subCountyName'] . "\n";
        }
    } else {
        echo "⚠️ KIRA MUNICIPALITY subcounties still not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
