<?php

class Stage extends DbAccess
{

    public function store($array)
    {
        $name = $array['name'];
        $address = $array['address'];
        $person = $array['person'];
        $email = $array['email'];
        $phone = $array['phoneNumber'];

        return $this->insert("fuelstation", [
            'fuelStationName' => $name,
            'fuelStationAddress' => $address,
            'fuelStationContactPerson' => $person,
            'fuelStationContactPhone' => $phone,
            'fuelStationContactEmail' => $email,
            'fuelStationStatus' => "on"

        ]);
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
                'fuelStationStatus' => "on"

            ],
            ["fuelStationId" => $_POST['id']]
        );
    }
}
