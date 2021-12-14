<?php


//include_once("./utils/dbaccess.php");


class FuelStation extends DbAccess
{

    public function store($array)
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
                'fuelStationStatus' => '0'

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
