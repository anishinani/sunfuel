<?php


//include_once("./utils/dbaccess.php");


class FuelStation extends DbAccess
{

    public function store($array, $front, $back)
    {
        $name = $array['name'];
        $address = $array['address'];
        $person = $array['person'];


        $phone = $array['phoneNumber'];
        return $this->insert(
            "fuelstation",
            [
                'fuelStationName' => strtoupper($name),
                'fuelStationAddress' => strtoupper($address),
                'fuelStationContactPerson' => strtoupper($person),
                'fuelStationContactPhone' => $phone,
                'fuelStationStatus' => '0',
                'frontIDPhoto' => $front,
                'backIDPhoto' => $back,
                'bankName' => strtoupper($array['bankname']),
                'bankBranch' => strtoupper($array['bankbranch']),
                'AccName' => strtoupper($array['accountname']),
                'AccNumber' => $array['accountnumber']


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


            ],
            ["fuelStationId" => $array['id']]
        );
    }
}
