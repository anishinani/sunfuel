<?php


//include_once("./utils/dbaccess.php");


class FuelStation extends DbAccess
{

    public function store($array)
    {
        $name = $array['name'];
        $address = $array['address'];
        $person = $array['person'];
        $email = $array['email'];
        $phone = $array['phoneNumber'];
        $result = $this->insert(
            "fuelstation",
            [
                'fuelStationName' => $name,
                'fuelStationAddress' => $address,
                'fuelStationContactPerson' => $person,
                'fuelStationContactPhone' => $phone,
                'fuelStationContactEmail' => $email,
                'fuelStationStatus' => '0'

            ]
        );
    }



    public function updateInfo($array)
    {
        $name = $array['name'];
        $address = $array['address'];
        $person = $array['person'];
        $email = $array['email'];
        $phone = $array['phoneNumber'];
        return $this->update(
            "fuelstation",
            [
                'fuelStationName' => $name,
                'fuelStationAddress' => $address,
                'fuelStationContactPerson' => $person,
                'fuelStationContactPhone' => $phone,
                'fuelStationContactEmail' => $email,
                'fuelStationStatus' => "active"

            ],
            ["fuelStationId" => $array['id']]
        );
    }
}
