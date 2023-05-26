<?php

 try {
require_once '../../utils/session.php';
include_once("../../utils/dbaccess.php");
$dbAccess = new DbAccess();

$boda_details =  $dbAccess->select('bodauser',[], ['bodaUserStatus'=>'2']);

if(count($boda_details)){
     foreach($boda_details as $details){
        //update the stage to become inactive
        $stage_update = $dbAccess->update("stage", ["stageStatus" => '0'], ["stageId" => $details['stageId']]);
        if($stage_update){
            $dbAccess->update("bodauser", ['bodaUserStatus' => '0'], ["stageId" => $details['stageId']]);
        }
        else{
            var_dump("Stage not updated");
        }
     }

}
else{
    var_dump("No boda users found with a pending payment");
}

 } catch (\Throwable $th) {
    //throw $th;
     var_dump($th);
     die("an error ocurred");
 }



