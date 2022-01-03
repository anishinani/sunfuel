<?php
session_start();
include_once("../../utils/dbaccess.php");

$dbAccesss = new DbAccess();

if (isset($_POST['action'])) {
    $parishCode =  $dbAccesss->clean($_POST['parish']);
    

    $counties =  $dbAccesss->select("villages", "", ['parishCode' => $parishCode]);


    echo  json_encode($counties);
} else {
    echo "not sent";
}
