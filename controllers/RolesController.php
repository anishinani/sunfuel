<?php
class Roles extends DbAccess
{
    public function store($array)
    {
        $name = $array['name'];
        $permissions =  $array['permissions'];
        // var_dump($permissions);
        // die("here in roles");

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
        $id = $array["roleId"];
        $name = $array['name'];
        $permissions =  $array['permissions'];

        $selectRoles =  $this->select("rolepermissionids", ['permissionId'], ['roleId' => $id]);
        $newPermissionArray = array();

        // foreach ($permissions as $key => $permission) {
        //     array_push($newPermissionArray, $permission);
        // }




        //die($name);

        //select role
        $role = $this->select("roles", ['roleName'], ["roleId" => $id])[0]["roleName"];
        //die($role);
        //delete user from rolepermissiontable
        $this->delete("DELETE FROM rolepermissionids WHERE roleId = $id ");
        //delete user from rolepermission table
        if ($role == $name) {
            //die("no updates");
            foreach ($permissions as $key => $permission) {
                $this->insert('rolepermissionids', [
                    "permissionId" => $permission,
                    "roleId" => $id
                ]);
            }
            return true;
        } else {

            //update roles table
            $roles = $this->update("roles", ["roleName" => $name], ["roleId" => $id]);

            if ($roles) {
                foreach ($permissions as $key => $permission) {
                    $this->insert('rolepermissionids', [
                        "permissionId" => $permission,
                        "roleId" => $id
                    ]);
                }
                return true;
            } else {
                return false;
            }

            //update rolepermissiontable

        }
    }
}
