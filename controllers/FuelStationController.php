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
                'fuelStationName' => $name,
                'fuelStationAddress' => $address,
                'fuelStationContactPerson' => $person,
                'fuelStationContactPhone' => $phone,
                'fuelStationStatus' => '0',
                'frontIDPhoto' => $front,
                'backIDPhoto' => $back,
                'bankName' => $array['bankname'],
                'bankBranch' => $array['bankbranch'],
                'AccName' => $array['accountname'],
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
                'fuelStationName' => $name,
                'fuelStationAddress' => $address,
                'fuelStationContactPerson' => $person,
                'fuelStationContactPhone' => $phone,


            ],
            ["fuelStationId" => $array['id']]
        );
    }
}
