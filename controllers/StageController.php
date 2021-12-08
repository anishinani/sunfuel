<?php

class Stage extends DbAccess
{

    public function store($array)
    {
        $name = $array['name'];
        $address = $array['address'];
        $person = $array['person'];
        $phone = $array['phoneNumber'];
        $id = $array["fuelStationId"];

        return $this->insert(
            "stage",
            [
                'stageName' => $name,
                'stageContactAddress' => $address,
                'stageContactPerson' => $person,
                'stageContactPhoneNumber' => $phone,
                'fuelStationId' => $id,
                'stageStatus' => "Active"

            ]
        );
    }
    public function updateInfo($array)
    {
        $name = $array['name'];
        $address = $array['address'];
        $person = $array['person'];
        $id = $array["fuelStationId"];
        $phone = $array['phoneNumber'];
        //die($array['id']);
        return $this->update(
            "stage",
            [
                'stageName' => $name,
                'stageContactAddress' => $address,
                'stageContactPerson' => $person,
                'stageContactPhoneNumber' => $phone,
                'fuelStationId' => $id,
                'stageStatus' => "Active"

            ],
            ["stageId" => $array['id']]
        );
    }
}
