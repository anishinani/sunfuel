<?php
session_start();
include_once("../utils/dbaccess.php");
include_once("../controllers/LoansCalc.php");
$dbAccess =  new DbAccess();
$loanCalc =  new LaonsCalc();

//die("am here");
$expectedDetails = array();

$totalActiveBodaUsers  = $loanCalc->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "1"]);
$expectedFuelPerDay = $loanCalc->expectedFuelPerDay($totalActiveBodaUsers);
$totalAmount = $loanCalc->getTotalAmountLoans();


function checkNUll($total)
{
    if ($total == NULL) {
        return intval(0);
    } else {

        return  intval($total);
    }
}
//echo $totalAmount;
//die("here");

array_push($expectedDetails, array("data" => checkNull($expectedFuelPerDay)));
array_push($expectedDetails, array("data" => checkNull($totalAmount)));

//var_dump($expectedDetails);

echo json_encode($expectedDetails);
