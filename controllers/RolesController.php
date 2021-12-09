<?php
class Roles extends DbAccess
{
    public function store($array)
    {
        $name = $array['name'];
        $permissions =  $array['permissions'];
        //var_dump($permission);

        if ($this->insert(
            "roles",
            [
                'roleName' => $name,

            ]
        )) {
            $myrole = $this->select("roles", ["roleId"], ['roleName' => $name]);
            foreach ($permissions as $key => $permission) {
                $this->insert('rolepermissionids', [
                    "permissionId" => $permission,
                    "roleId" => $myrole[0]["roleId"]
                ]);
            }
            return true;
        } else {
            return false;
        }
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
