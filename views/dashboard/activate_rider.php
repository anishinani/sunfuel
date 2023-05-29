<?php
 include_once("../../utils/dbaccess.php");
 try {
  if(isset($_POST['activate'])){
    $dbAccess =  new DbAccess();
    $dbAccess->update("bodauser", ["bodaUserStatus" => "1"], ["bodaUserId" => $_POST['id']]);
    $_SESSION['success'] = "Boda User has been activated successfully";
    echo "<script>Boda user has been updated successfully</script>";
    header("Location:index.php");
  }
 } catch (\Throwable $th) {
    //throw $th;
    var_dump($th->getMessage());
    die("an error ocuured");
 }
 //0782 9869 75