<?php

class AccessController extends DbAccess{

    /**
     * @method createModule
     * creates the module in the application
     * */ 
    function createModule(array $data){

        $insert = $this->insert("modules",$data);

        return $insert;
        
    }

    /**
     * @method createfeature
     * creates the feature with its permission in the system
     * **/ 
    function createFeature(array $data){

        $insert = $this->insert("features", $data);

        return $insert;
    }

    /**
     * @method createRole
     * creates the role in the system 
     * 
     * **/
    function createRole(array $data){

        $insert = $this->insert("roles", $data);

        return $insert;
    } 
    /**
     * @method assignRolePermission
     * assigns permission to the role
     * **/ 
    function assignRolePermission(array $data){

        // if role permission already exists then  fail
        if($this->roleHasPermission($data['feature_id'] , $data['role_id'])) return false;

        $insert = $this->insert("role_permissions", $data);

        return $insert;
    }


    function  getAllowedModules($role){
        
        $sql = 'SELECT module_id FROM role_modules where role_id ='.$role;

        $res = $this->selectQuery($sql);

        $modules = [];

        foreach($res as $r) $modules[] = $r['module_id'];

        return $modules;

    }

    public function roleHasPermission($feature_id , $role_id){

        $result = $this->selectQuery('select count(id) as total from role_permissions where feature_id ='. $feature_id . ' and role_id = '.$role_id . ' and status = 1' );

        return ($result[0]['total'] > 0);
    }
    /**
     * @method getUserPermissions
     * get the permissions of an admin
     * */ 
    function getUserPermissions($role){


        $query = 'select permission   from role_permissions inner join features on role_permissions.feature_id = features.id where role_permissions.role_id';

        $permissions =  $this->selectQuery($query ."=".$role);

        $perm  = [];

        foreach($permissions as $p) $perm[] = $p['permission'];
 
        return ['permissions' => $perm , 'modules'=> $this->getAllowedModules($role) ];
    }
    /**
     * @method getRoles 
     * gets the list of roles
     * **/ 
    function getRoles($id = null){

        if(!is_null($id)){
            $role = $this->select('roles',['id' , 'name'] , ['id' => $id]);
            return !empty($role)? $role[0] : null;
        }
        return $this->select("roles",['id' , 'name']);

    }
    /**
     * @method getModules
     * getting modules in the system
     * 
     * */ 
    function getModules(){

        $modules = $this->select("modules",['id' , 'name', 'icon']);

        $data = array();

        foreach($modules as $module){
            $data[] = array(
                "id" => $module['id'],
                "name" => $module['name'],
                'icon' => $module['icon'],
                'features' => $this->getModuleFeatures($module['id'])
            );
        }

        return $data;
    }
    

    /**
     * @method getModuleFeatures
     * getting features of the module
     * */ 

    function getModuleFeatures($module_id){

        return $this->select('features', ['id','name' , 'permission' , 'action'] , ['module_id' => $module_id]);
    }







}

