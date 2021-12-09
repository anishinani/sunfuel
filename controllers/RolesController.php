<?php
class Roles extends DbAccess
{
    public function store($array)
    {
        $name = $array['name'];
        $permission =  $array['permissions'];
        var_dump($permission);

        // return $this->insert(
        //     "roles",
        //     [
        //         'roleName' => $name,

        //     ]
        // );
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
