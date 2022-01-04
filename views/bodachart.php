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
//die("here");
$bodaUsersDetails = [];
$totalActiveBodaUsers  = $dbAccess->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "1"]);
$totalInActiveBodaUsers  = $dbAccess->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "0"]);
$totalDefaultedBodaUsers  = $dbAccess->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "2"]);
//$dbAccess->selectQuery("SELECT COUNT(bodaUserStatus) AS total FROM bodauser  WHERE  DATE(updated_at) = CURDATE() AND bodaUserStatus=2")[0]['total'];
$suspendedBodaUsers =  $dbAccess->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "3"]);


array_push($bodaDetails, array("total" => checkNUll($totalActiveBodaUsers)));
array_push($bodaDetails, array("total" => checkNUll($totalInActiveBodaUsers)));
array_push($bodaDetails, array("total" => checkNUll($totalDefaultedBodaUsers)));
array_push($bodaDetails, array("total" => checkNUll($suspendedBodaUsers)));

//array_push($bodaDetails, 1);

//echo $bodaDetails[0] . "," . $bodaDetails[1] . "," . $bodaDetails[2] . "," . $bodaDetails[3];


echo json_encode($bodaDetails);

//var_dump($bodaDetails);



// if (isset($_POST['fetch'])) {
//     echo "something";
// } else {
//     echo "nothing";
// }
