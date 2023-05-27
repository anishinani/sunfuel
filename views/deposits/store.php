<?php

try {
    session_start();


    require_once("../../utils/dbaccess.php");

    require_once("../../utils/activityLogger.php");
    require_once("../../utils/helpers.php");
    require_once("../../controllers/DepositController.php");



    //$helpers =  new HelperFunctions();
    $dbAccess =  new DbAccess();
    $deposit = new Deposit();
    $helpers =  new HelperFunctions();
    $activity = new ActivityLogger();
    //unset($_SESSION['errors']);



    if (isset($_POST['amount'])) {

        $_SESSION['errors'] = array();

        //check errors and clean o
        foreach ($_POST as $key => $value) {
            if ($key == 'addDeposit') {
                continue;
            } else {
                if ($helpers->checkEmptyFields($value) != NULL) {
                    array_push($_SESSION['errors'],   $key . " " . $helpers->checkEmptyFields($value));
                } else {
                    $dbAccess->clean($value);
                }
            }
        }

        $frontPhoto = $_FILES["receipt"]["name"];
        $tempFrontPhoto = $_FILES["receipt"]["tmp_name"];
        $photoTwo =  time() . str_replace(" ", "_", $frontPhoto);

        //die($photoTwo);

        if (move_uploaded_file($tempFrontPhoto, "images/" . $photoTwo)) {
        } else {
            //die("Failed to move image");
            $_SESSION['success'] = "Wrong image format not supported";
            echo json_encode(array("message" => "failure",  "data" => "Wrong image format not supported"));
            //header("Location:index.php");
        }
        //store images

        //check session array
        if (count($_SESSION['errors'])) {

            echo json_encode(array("message"=>"failure", "data" => "Oops there was an error"));
        } else {
            unset($_SESSION['errors']);

            if ($deposit->store($_POST, $photoTwo)) {
                // $activity->logActivity(
                //     $_SESSION['user'],
                //     "Added deposit",
                //     "Deposit added sucessfully",
                //     $_SESSION['email'],
                //     $_SESSION['gender']
                // );

                //redirect
                $_SESSION['success'] = "Deposit Successfully";
                //header("Location:index.php");
                echo json_encode(array("message" => "success", "data" => "Deposited Successfully"));
                //redirect
            } else {
                //die("Oops there was an error");
                echo json_encode(array("message" => "failure",  "data" => "Oops there was an error"));
            }
        }
        //check session array



    }
} catch (\Throwable $th) {
    //throw $th;
    echo json_encode(array("message" => "failure",  "data" => $th->getMessage()));
}
