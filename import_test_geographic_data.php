<?php
/**
 * Import Test Geographic Data
 * This script will import test data for the specific example: KAMPALA → KAWEMPE DIVISION → KAWEMPE DIVISION → MAKERERE II → ZONE A
 */

require_once 'utils/dbaccess.php';

echo "Import Test Geographic Data\n";
echo "===========================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Clear existing data
    echo "Step 1: Clearing existing geographic data...\n";
    $dbAccess->conn->query("TRUNCATE TABLE sub_counties");
    $dbAccess->conn->query("TRUNCATE TABLE parishes");
    $dbAccess->conn->query("TRUNCATE TABLE villages");
    echo "✅ Cleared existing data\n";
    
    // Insert test data for KAMPALA/KAWEMPE DIVISION
    echo "Step 2: Inserting test geographic data...\n";
    
    // Subcounties for KAMPALA/KAWEMPE DIVISION
    $subcountiesData = [
        [
            'id' => 926,
            'uuid' => '89ff227c-6e7c-43fd-a50b-7855a10eaa00',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'subCountyName' => 'KAWEMPE DIVISION',
            'deleted_at' => null,
            'created_at' => '2022-01-01 00:24:10',
            'updated_at' => '2023-11-13 14:35:36'
        ]
    ];
    
    // Parishes for KAMPALA/KAWEMPE DIVISION/KAWEMPE DIVISION
    $parishesData = [
        [
            'id' => 6461,
            'uuid' => 'd36fd4f1-72fb-4ac0-90a7-d50620aa8ef2',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '8',
            'parishName' => 'MAKERERE II',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:35:35',
            'updated_at' => '2023-11-13 14:36:25'
        ]
    ];
    
    // Villages for MAKERERE II
    $villagesData = [
        [
            'id' => 68521,
            'uuid' => '30e36dfc-8196-4dae-8dca-944729476b09',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '8',
            'villageCode' => '1',
            'villageName' => 'ZONE A',
            'deleted_at' => null,
            'created_at' => '2022-01-01 05:29:26',
            'updated_at' => '2023-11-13 14:43:09'
        ],
        [
            'id' => 68524,
            'uuid' => '581bfb30-677c-4dfa-b39b-081b2cd4b853',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '8',
            'villageCode' => '2',
            'villageName' => 'ZONE B',
            'deleted_at' => null,
            'created_at' => '2022-01-01 05:29:26',
            'updated_at' => '2023-11-13 14:43:09'
        ],
        [
            'id' => 68528,
            'uuid' => '287b52b0-f304-4031-924a-6e411602deb2',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '8',
            'villageCode' => '3',
            'villageName' => 'ZONE D',
            'deleted_at' => null,
            'created_at' => '2022-01-01 05:29:26',
            'updated_at' => '2023-11-13 14:43:09'
        ],
        [
            'id' => 68539,
            'uuid' => 'cc0bcb06-95c3-4906-ba96-88892dc9f52f',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '8',
            'villageCode' => '4',
            'villageName' => 'ZONE C',
            'deleted_at' => null,
            'created_at' => '2022-01-01 05:29:26',
            'updated_at' => '2023-11-13 14:43:09'
        ]
    ];
    
    // Insert subcounties
    $inserted = 0;
    foreach ($subcountiesData as $data) {
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
                echo "✅ Inserted subcounty: " . $data['subCountyName'] . "\n";
            } else {
                echo "❌ Failed to insert subcounty: " . $data['subCountyName'] . " - " . $stmt->error . "\n";
            }
            $stmt->close();
        }
    }
    
    // Insert parishes
    $inserted = 0;
    foreach ($parishesData as $data) {
        $sql = "INSERT INTO parishes (id, uuid, districtCode, countyCode, subCountyCode, parishCode, parishName, deleted_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $dbAccess->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("isssssssss", 
                $data['id'], 
                $data['uuid'], 
                $data['districtCode'], 
                $data['countyCode'], 
                $data['subCountyCode'], 
                $data['parishCode'], 
                $data['parishName'], 
                $data['deleted_at'], 
                $data['created_at'], 
                $data['updated_at']
            );
            
            if ($stmt->execute()) {
                $inserted++;
                echo "✅ Inserted parish: " . $data['parishName'] . "\n";
            } else {
                echo "❌ Failed to insert parish: " . $data['parishName'] . " - " . $stmt->error . "\n";
            }
            $stmt->close();
        }
    }
    
    // Insert villages
    $inserted = 0;
    foreach ($villagesData as $data) {
        $sql = "INSERT INTO villages (id, uuid, districtCode, countyCode, subCountyCode, parishCode, villageCode, villageName, deleted_at, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $dbAccess->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("issssssssss", 
                $data['id'], 
                $data['uuid'], 
                $data['districtCode'], 
                $data['countyCode'], 
                $data['subCountyCode'], 
                $data['parishCode'], 
                $data['villageCode'], 
                $data['villageName'], 
                $data['deleted_at'], 
                $data['created_at'], 
                $data['updated_at']
            );
            
            if ($stmt->execute()) {
                $inserted++;
                echo "✅ Inserted village: " . $data['villageName'] . "\n";
            } else {
                echo "❌ Failed to insert village: " . $data['villageName'] . " - " . $stmt->error . "\n";
            }
            $stmt->close();
        }
    }
    
    echo "Step 3: Import completed!\n";
    
    // Test the specific example
    echo "\nStep 4: Testing the specific example...\n";
    echo "KAMPALA → KAWEMPE DIVISION → KAWEMPE DIVISION → MAKERERE II → ZONE A\n";
    
    // Test subcounties
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM sub_counties WHERE districtCode = '12' AND countyCode = '35'");
    $row = $result->fetch_assoc();
    echo "✅ Subcounties for KAMPALA/KAWEMPE DIVISION: " . $row['count'] . "\n";
    
    // Test parishes
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM parishes WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1'");
    $row = $result->fetch_assoc();
    echo "✅ Parishes for KAMPALA/KAWEMPE DIVISION/KAWEMPE DIVISION: " . $row['count'] . "\n";
    
    if ($row['count'] > 0) {
        $result = $dbAccess->conn->query("SELECT parishName FROM parishes WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1'");
        echo "Parishes found:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['parishName'] . "\n";
        }
    }
    
    // Test villages
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM villages WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1' AND parishCode = '8'");
    $row = $result->fetch_assoc();
    echo "✅ Villages for MAKERERE II: " . $row['count'] . "\n";
    
    if ($row['count'] > 0) {
        $result = $dbAccess->conn->query("SELECT villageName FROM villages WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1' AND parishCode = '8'");
        echo "Villages found:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['villageName'] . "\n";
        }
    }
    
    echo "\n✅ SUCCESS! Test geographic data has been imported!\n";
    echo "The complete hierarchy is now available:\n";
    echo "KAMPALA → KAWEMPE DIVISION → KAWEMPE DIVISION → MAKERERE II → ZONE A\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
