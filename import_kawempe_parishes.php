<?php
/**
 * Import All KAWEMPE DIVISION Parishes
 * This script will import all parishes for KAWEMPE DIVISION
 */

require_once 'utils/dbaccess.php';

echo "Import All KAWEMPE DIVISION Parishes\n";
echo "====================================\n";

try {
    $dbAccess = new DbAccess();
    echo "✅ Connected to database\n";
    
    // Clear existing parishes data
    echo "Step 1: Clearing existing parishes data...\n";
    $dbAccess->conn->query("TRUNCATE TABLE parishes");
    echo "✅ Cleared existing parishes data\n";
    
    // Insert all parishes for KAWEMPE DIVISION
    echo "Step 2: Inserting all KAWEMPE DIVISION parishes...\n";
    
    $parishesData = [
        [
            'id' => 2377,
            'uuid' => 'e7f45d43-bafe-4c33-9f95-499c998b647a',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '1',
            'parishName' => 'BWAISE I',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:15:11',
            'updated_at' => '2023-11-13 14:35:58'
        ],
        [
            'id' => 2378,
            'uuid' => '49ed34e1-7465-433a-b11f-e7cfad2993cb',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '1',
            'parishName' => 'BWAISE II',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:15:11',
            'updated_at' => '2023-11-13 14:35:58'
        ],
        [
            'id' => 2379,
            'uuid' => 'c5a0aec9-4954-49d3-a391-8c3d18dce977',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '2',
            'parishName' => 'BWAISE III',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:15:11',
            'updated_at' => '2023-11-13 14:35:58'
        ],
        [
            'id' => 3858,
            'uuid' => 'ba95e487-df4a-4424-9310-48b2d0bb2137',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '2',
            'parishName' => 'KANYANYA',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:21:19',
            'updated_at' => '2023-11-13 14:36:08'
        ],
        [
            'id' => 4399,
            'uuid' => 'c30ed394-3ee4-4f53-8523-e4587e30dd4f',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '4',
            'parishName' => 'KAWEMPE I',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:21:39',
            'updated_at' => '2023-11-13 14:36:11'
        ],
        [
            'id' => 4400,
            'uuid' => '7cda13ad-0d90-4000-86c3-e21c6156f27c',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '5',
            'parishName' => 'KAWEMPE II',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:21:39',
            'updated_at' => '2023-11-13 14:36:11'
        ],
        [
            'id' => 4463,
            'uuid' => '2f420b87-8c4e-4663-8e2f-8b42910b0484',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '6',
            'parishName' => 'KAZO-ANGOLA',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:21:41',
            'updated_at' => '2023-11-13 14:36:12'
        ],
        [
            'id' => 4808,
            'uuid' => '9cdabe0c-2ff7-459b-936c-2865f86d3a4f',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '7',
            'parishName' => 'KIKAYA',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:21:53',
            'updated_at' => '2023-11-13 14:36:14'
        ],
        [
            'id' => 5456,
            'uuid' => '606e0130-9f10-44b7-af73-f1a1dfac5269',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '3',
            'parishName' => 'KOMAMBOGA',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:22:19',
            'updated_at' => '2023-11-13 14:36:19'
        ],
        [
            'id' => 5798,
            'uuid' => 'fda5b2f1-4c25-42ea-8280-854ad36d3ced',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '8',
            'parishName' => 'KYEBANDO',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:22:33',
            'updated_at' => '2023-11-13 14:36:21'
        ],
        [
            'id' => 6460,
            'uuid' => '24a3fce2-9a0b-47a4-aeac-e4c7472eb4d8',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '7',
            'parishName' => 'MAKERERE I',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:35:35',
            'updated_at' => '2023-11-13 14:36:25'
        ],
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
        ],
        [
            'id' => 6462,
            'uuid' => '511b2fd2-56c1-4700-943e-120e7e3fda35',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '9',
            'parishName' => 'MAKERERE III',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:35:36',
            'updated_at' => '2023-11-13 14:36:25'
        ],
        [
            'id' => 6832,
            'uuid' => '24ce1ae5-f6eb-4d7e-ada7-f1e72813da60',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '9',
            'parishName' => 'MPERERWE',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:35:46',
            'updated_at' => '2023-11-13 14:36:28'
        ],
        [
            'id' => 6930,
            'uuid' => '78107715-06ce-40a9-9863-da4f52b1a928',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '10',
            'parishName' => 'MULAGO I',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:35:48',
            'updated_at' => '2023-11-13 14:36:28'
        ],
        [
            'id' => 6931,
            'uuid' => '1bbb1358-ec96-4958-ae52-5bd0751ec2c1',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '11',
            'parishName' => 'MULAGO II',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:35:48',
            'updated_at' => '2023-11-13 14:36:28'
        ],
        [
            'id' => 6932,
            'uuid' => '2a7db2a0-2f8f-41be-abf7-f33bc7a08e73',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '12',
            'parishName' => 'MULAGO III',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:35:48',
            'updated_at' => '2023-11-13 14:36:28'
        ],
        [
            'id' => 9865,
            'uuid' => 'c0fcd31b-adaf-47c9-92bc-dca8cb0f7e99',
            'districtCode' => '12',
            'countyCode' => '35',
            'subCountyCode' => '1',
            'parishCode' => '13',
            'parishName' => 'WANDEGEYA',
            'deleted_at' => null,
            'created_at' => '2022-01-01 01:41:40',
            'updated_at' => '2023-11-13 14:36:48'
        ]
    ];
    
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
    
    echo "Step 3: Import completed!\n";
    echo "✅ Total parishes inserted: $inserted\n";
    
    // Verify the import
    $result = $dbAccess->conn->query("SELECT COUNT(*) as count FROM parishes WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1'");
    $row = $result->fetch_assoc();
    echo "✅ Verification: " . $row['count'] . " parishes for KAWEMPE DIVISION\n";
    
    // List all parishes
    $result = $dbAccess->conn->query("SELECT parishName FROM parishes WHERE districtCode = '12' AND countyCode = '35' AND subCountyCode = '1' ORDER BY parishName");
    echo "\nAll KAWEMPE DIVISION parishes:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- " . $row['parishName'] . "\n";
    }
    
    echo "\n✅ SUCCESS! All KAWEMPE DIVISION parishes have been imported!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
