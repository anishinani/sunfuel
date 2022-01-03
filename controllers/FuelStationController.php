<?php


//include_once("./utils/dbaccess.php");


class FuelStation extends DbAccess
{

    public function store($array, $front, $back)
    {
        $name = $array['name'];
        $address = $array['address'];
        $person = $array['person'];

        //generate merchant code
        $lastId = $this->selectQuery("SELECT fuelStationId FROM fuelstation ORDER BY fuelStationId DESC LIMIT 1")[0]['fuelStationId'];


        if ($lastId == NULL) {
            $lastId = 1;
        }
        $merchantCode  = $_POST['district'] . $_POST['county'] . $_POST['subcounty'] . $_POST['parish'] . $_POST['village'] . $lastId;


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
}
