<?php
session_start();
include_once("../utils/dbaccess.php");
$dbAccess =  new DbAccess();

//die("am here");
$bodaDetails = array();
function checkNUll($total)
{
    if ($total == NULL) {
        return intval(0);
    } else {

        return  intval($total);
    }
}

$bodaUsersDetails = [];
$totalActiveBodaUsers  = $dbAccess->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "1"]);
$totalInActiveBodaUsers  = $dbAccess->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "0"]);
$totalDefaultedBodaUsers  = $dbAccess->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "2"]);
//$dbAccess->selectQuery("SELECT COUNT(bodaUserStatus) AS total FROM bodauser  WHERE  DATE(updated_at) = CURDATE() AND bodaUserStatus=2")[0]['total'];
$suspendedBodaUsers =  $dbAccess->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "3"]);


array_push($bodaDetails, array("data" => checkNUll($totalActiveBodaUsers)));
array_push($bodaDetails, array("data" => checkNUll($totalInActiveBodaUsers)));
array_push($bodaDetails, array("data" => checkNUll($totalDefaultedBodaUsers)));
array_push($bodaDetails, array("data" => checkNUll($suspendedBodaUsers)));


echo json_encode($bodaDetails);


