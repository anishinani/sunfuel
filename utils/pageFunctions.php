<?php
/***
 * @method showAction 
 * creates the markup of the actions depending on the roles one posses
 * 
 * */ 

function showActions ($row , array $actions , $key = 'id'){

    if(empty($_SESSION) || !isset($_SESSION['roles']) || empty($_SESSION['roles'])) return "";

    $roles = $_SESSION['roles'];

    $html = "<div class='d-flex justify-content-between align-items-center'>";

    foreach($actions as $action){

        if(in_array($action['permission'] , $roles)){

            switch($action['type']){
                case 'edit':
                    $html .= "<a class='btn btn-info btn-sm' href='edit.php?id=".$row[$key]." >Edit</a>";
                break;
                case 'delete':
                    $html .= "<a class='btn delete-btn btn-info btn-sm' href='delete.php?id=".$row[$key]." >Delete</a>";
                break;
            }
        }
    }
    // closing the div element
    $html .= '</div>';

    return $html;
}

/***
 * @method getDistricts options
 * generates the options of the district
 * @param  $id  the district id
 * */
function  getDistricts( $as = 'options', $code=null , array $extras = null){


    $data = unAllocatedDistricts();

    $html = "";

    if($as == 'options') {

        foreach($data as $district) $html .= "<option value=".$district["districtCode"].">".$district["districtName"]."</option>";
    
        return  $html;
    }

    if($as == 'listItem'){

        foreach($data as $district) $html .= "<li>".$district["districtName"]."</li>";
    
        return  $html;
    }

    return $data;
}

/***
 * @method getUsers
 * access the users as an options or list or data array
 * 
 * ***/ 

 function getUsers($as="options",$code =null ){
    require_once '../../utils/dbaccess.php';

    $dbAccess = new DbAccess;

    $data = !is_null($id)? $dbAccess->select("administrators",array() , ['adminId' => $code ]) :$dbAccess->select("administrators",['*']);

    $html = "";
    if($as == 'options') {

        foreach($data as $district) $html .= "<option value=".$district["adminId"].">".$district["name"]."</option>";
    
        return  $html;
    }

    if($as == 'listItem'){

        foreach($data as $district) $html .= "<li>".$district["name"]."</li>";
    
        return  $html;
    }

    return $data;

 }


function unAllocatedDistricts(){

    require_once '../../utils/dbaccess.php';

    $dbAccess = new DbAccess;
    
    $data = unlocatedDistrictsCodes();

    if( empty($data) || count($data) == 0 ) return $dbAccess->select('districts');

    if(count($data) == 1) return $dbAccess->selectQuery("SELECT * FROM districts  WHERE districtCode IS NOT ".$data[0]['districtCode']);

    $selected = array();

    foreach($data as $dc) $selected[] = $dc['districtCode'];

    $districts = $dbAccess->selectQuery(" SELECT * FROM districts  WHERE districtCode NOT IN (".implode(",",$selected).") ");

    // var_dump($districts);
    // die;
    return $districts;


}

function unlocatedDistrictsCodes(){
    require_once '../../utils/dbaccess.php';

    $dbAccess = new DbAccess;

    $data = $dbAccess->select("territory_districts",["districtCode"]);

    return  $data;
}