<?php


//include_once("./utils/dbaccess.php");


class FuelStation extends DbAccess
{

    public function store($array, $front, $back)
    {
        $name = $array['name'];
        $address = $array['address'];
        $person = $array['person'];

        //generate merchant code - 6 digit incremental
        $merchantCode = $array['merchantCode'] ?? $this->generateMerchantCode();

        //generate merchant code


        $phone = $array['phoneNumber'];
        return $this->insert(
            "fuelstation",
            [
                'fuelStationName' => strtoupper($name),
                'fuelStationAddress' => strtoupper($address),
                'fuelStationContactPerson' => strtoupper($person),
                'fuelStationContactPhone' => $phone,
                'fuelStationStatus' => '0',
                'NIN' => strtoupper($array['nin']),
                'frontIDPhoto' => $front,
                'backIDPhoto' => $back,
                'bankName' => strtoupper($array['bankname']),
                'bankBranch' => strtoupper($array['bankbranch']),
                'AccName' => strtoupper($array['accountname']),
                'AccNumber' => $array['accountnumber'],
                'merchantCode' => $merchantCode,
                'districtCode' => $_POST['district'],
                'countyCode' => $_POST['county'],
                'subCountyCode' => $_POST['subcounty'],
                'parishCode' => $_POST['parish'],
                'villageCode' => $_POST['village']


            ]
        );
    }



    public function updateInfo($array)
    {
        $name = $array['name'];
        $address = $array['address'];
        $person = $array['person'];

        $phone = $array['phoneNumber'];
        return $this->update(
            "fuelstation",
            [
                'fuelStationName' => strtoupper($name),
                'fuelStationAddress' => strtoupper($address),
                'fuelStationContactPerson' => strtoupper($person),
                'fuelStationContactPhone' => $phone,
                'bankName' => strtoupper($array['bankname']),
                'bankBranch' => strtoupper($array['bankbranch']),
                'AccName' => strtoupper($array['accountname']),
                'AccNumber' => $array['accountnumber']


            ],
            ["fuelStationId" => $array['id']]
        );
    }

    /**
     * Generate a 6-digit incremental merchant code
     */
    private function generateMerchantCode()
    {
        // Get the highest existing merchant code
        $sql = "SELECT MAX(CAST(merchantCode AS UNSIGNED)) as maxCode FROM fuelstation WHERE merchantCode IS NOT NULL AND merchantCode REGEXP '^[0-9]+$'";
        $result = $this->selectQuery($sql);
        
        $nextCode = 1;
        if (!empty($result) && isset($result[0]['maxCode']) && $result[0]['maxCode'] !== null) {
            $nextCode = $result[0]['maxCode'] + 1;
        }
        
        // Ensure the code is at least 6 digits
        return str_pad($nextCode, 6, '0', STR_PAD_LEFT);
    }
}
