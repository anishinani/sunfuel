<?php
class Roles extends DbAccess
{
    public function store($array)
    {

        $name = $array['name'];

        $permissions =  $array['permissions'];

        $id = $this->insert('roles',['name' => $name , 'created_by' => $_SESSION['auth']]);

        if(!is_int($id) || $id == 0  ) return false;

        foreach($array['modules'] as $module){
            $this->insert('role_modules',[
                'role_id' => $id,
                'module_id' => $module
            ]);
        }

        foreach($permissions as $permission){

           $assigned[] = $this->insert('role_permissions',[
                'role_id' => $id,
                'feature_id' => $permission,
                'created_by'=> $_SESSION['auth']
            ]);

        }



        return $id;
       
    }
    public function updateInfo($array)
    {
        $id = $array["roleId"];
        $name = $array['name'];
        $permissions =  $array['permissions'];
        $modules = $array['modules'];

        $update = $this->update("roles",["name" => $name],["id" => $id]);

        // delete all role modules

        $this->conn->query('delete from role_permissions where role_id ='.$id);

        $this->conn->query('delete from role_modules where role_id ='.$id);

        foreach($modules as $module){
            $this->insert('role_modules',[
                'role_id' => $id,
                'module_id' => $module
            ]);
        }

        foreach($permissions as $permission){

           $assigned[] = $this->insert('role_permissions',[
                'role_id' => $id,
                'feature_id' => $permission,
                'created_by'=> $_SESSION['auth']
            ]);

        }
        
        return $id;
    
    }

    //get all roles
    public function getAllPermissions()
    {
        $sql = "SELECT permissions.permissionName  FROM permissions 
        INNER JOIN rolepermissionids ON rolepermissionids.permissionId = permissions.permissionId
         INNER JOIN roles ON roles.roleId = rolepermissionids.roleId";
        $allRoles = $this->selectQuery($sql);
        return $allRoles;
    }

    public function getSpecificPermissions($id)
    {
        $sql = "SELECT permissions.permissionName  FROM permissions 
        INNER JOIN rolepermissionids ON rolepermissionids.permissionId = permissions.permissionId
         WHERE rolepermissionids.roleId=$id";
        $specificRoles =  $this->selectQuery($sql);
        $newModifiedPermissions = array();
        for ($i = 0; $i < count($specificRoles); $i++) {
            array_push($newModifiedPermissions, $specificRoles[$i]['permissionName']);
        }
        return $newModifiedPermissions;
    }
}
