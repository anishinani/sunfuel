<?php
 include_once("../../utils/dbaccess.php");
 try {
  if(isset($_POST['activate'])){
    $dbAccess =  new DbAccess();
    $dbAccess->update("stage", ["stageStatus" => "1"], ["stageId" => $_POST['id']]);
    $_SESSION['success'] = "Stage has been activated successfully";
    header("Location:index.php");
  }
 } catch (\Throwable $th) {
    //throw $th;
    var_dump($th->getMessage());
    die("an error ocuured");
 }