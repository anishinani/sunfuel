<?php


//include_once("./utils/dbaccess.php");


class Package extends DbAccess
{

    public function store($array)
    {
        $name = $array['name'];
        $amount = $array['amount'];

        return $this->insert(
            "package",
            [
                'packageName' => $name,
                'packageAmount' => $amount,
                'packageStatus' => 1

            ]
        );
    }



    public function updateInfo($array)
    {
        $name = $array['name'];
        $address = $array['amount'];
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
