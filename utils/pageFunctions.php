<?php
/***
 * @method showAction 
 * creates the markup of the actions depending on the roles one posses
 * 
 * */ 

function showActions2 ($row , array $actions , $key = 'id'){

    if(empty($_SESSION) || !isset($_SESSION['permissions']) || empty($_SESSION['permissions'])) return "";

    $roles = $_SESSION['permissions'];

    $html = "<div class='d-flex justify-content-between align-items-center'>";

    foreach($actions as $action){

        if(in_array($action['permission'] , $roles)){

            if($action['type'] == "edit"){
            $html .= ' <form action="edit.php?id="' . $row[$key] . '"" method="get">
            <button type="submit" name="update"  value="' . $row[$key] . '"
            class="btn btn-info btn-sm editbtn btn-primary" ><i class="fas fa-edit"></i> Edit</button>
    
            </form>';
            }

            if($action['type'] == "delete"){
                $html .= ' <form action="delete.php?id="' . $row[$key] . '"" method="post">
                <button type="submit" name="delete"  value="' . $row[$key] . '"
                class="btn btn-info btn-sm btn-danger" ><i class="fas fa-trash"></i>  Delete</button>
                </form>';
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

    $data = $dbAccess->select("users",['*']);

    $html = "";

    if($as == 'options') {

        foreach($data as $district){ 

            $patch = $district['adminId'] ==  $code ? "selected" : ""; 

            $html .= "<option  ".$patch."  value=".$district["adminId"].">".$district["name"]."</option>";}
    
        return  $html;
    }

    if($as == 'listItem'){

        $patch = $district['adminId'] ==  $code ? "font-weight-bold" : ""; 

        foreach($data as $district) $html .= "<li  class='".$patch."' >".$district["name"]."</li>";
    
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


function getDistrictsSelected(array $codes ){

    require_once "../../utils/dbaccess.php";

    $dbAccess = new DbAccess;

    $un_allocated =  unlocatedDistrictsCodes();

    $un_allocated = array_filter($un_allocated , function($array) use($codes) {
        return ( !in_array($array['districtCode'] , $codes)); 
    });

    $html = "";

    if(count($un_allocated) == 1){
      $data = $dbAccess->selectQuery("SELECT * FROM districts  WHERE districtCode IS NOT ".$data[0]['districtCode']);
    }else if(count($un_allocated) > 1){
        $selected = [];

        foreach($un_allocated as $dc) $selected[] = $dc['districtCode'];
    
    
        $data = $dbAccess->selectQuery(" SELECT * FROM districts  WHERE districtCode NOT IN (".implode(",",$selected).") ");
    

    }
   
    foreach($data as $district){
        $patch = in_array($district['districtCode'],$codes) ? "selected" : "";

        $html .= "<option  ".$patch."   value=".$district["districtCode"].">".$district["districtName"]."</option>";
    }
    
    return  $html;

}