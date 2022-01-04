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

$stageDetails = [];


//stages
$totalActiveStages  = $dbAccess->countRows("stage", "stageStatus", ["stageStatus", "1"]);
$totalInActiveStages  = $dbAccess->countRows("stage", "stageStatus", ["stageStatus", "0"]);
$totalDefaultStages  = $dbAccess->countRows("stage", "stageStatus", ["stageStatus", "2"]);
$suspendedStages  = $dbAccess->countRows("stage", "stageStatus", ["stageStatus", "3"]);
//stages

array_push($stageDetails, array("data" => checkNUll($totalActiveStages)));
array_push($stageDetails, array("data" => checkNUll($totalInActiveStages)));
array_push($stageDetails, array("data" => checkNUll($totalDefaultStages)));
array_push($stageDetails, array("data" => checkNUll($suspendedStages)));


echo json_encode($stageDetails);
