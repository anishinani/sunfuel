<?php
session_start();


require_once("../../utils/dbaccess.php");
require_once("../../utils/activityLogger.php");
require_once("../../utils/helpers.php");
require_once('../../controllers/User.php');
require_once("../../utils/pin.php");
require_once("../../utils/mailer/mailer.php");


//$helpers =  new HelperFunctions();
$dbAccess =  new DbAccess();
$helpers =  new HelperFunctions();
$user =  new User();
$activity = new ActivityLogger();
$pin = new pin();
$mailer =  new MyMail();
//unset($_SESSION['errors']);



if (isset($_POST['addUser'])) {


    $_SESSION['errors'] = array();

    //check errors and clean o
    foreach ($_POST as $key => $value) {
        if ($key == 'addUser') {
            continue;
        } else {
            if ($helpers->checkEmptyFields($value) != NULL) {
                array_push($_SESSION['errors'],   $key . " " . $helpers->checkEmptyFields($value));
            } else {
                $dbAccess->clean($value);
            }
        }
    }
    //check if email exists
    $emailExists = $dbAccess->select("users", ['email'], ["email" => $_POST['email']]);


    if (count($emailExists)) {
        $_SESSION['error'] = "email already exists";
        array_push($_SESSION['errors'], "Email already exists Please use a different email");
        header("Location:create.php");
    }


    //check session array
    $hashedPass  = $pin->hashPass($_POST['email']);
    //check session array
    if (count($_SESSION['errors'])) {

        header("Location:create.php");
    } else {
        unset($_SESSION['errors']);
        if ($user->store($_POST, $hashedPass)) {
            $email =  $_POST['email'];
            $localLink = "localhost/creditpluswebapp/views/auth/setPassword.php?token=$hashedPass";
            $serverLink = "https://boda.creditplus.ug/creditpluswebapp/views/auth/setPassword.php?token=$hashedPass";
            $linkToSend = "";
            $ip_address = $_SERVER['REMOTE_ADDR'];

            if ($ip_address == '::1') {

                $linkToSend = $localLink;
            } else {

                $linkToSend = $serverLink;
            }
            // var_dump($linkToSend);
            // die("here");

            //send email
            $message = "<P>You have been registered successfully to set your password <a href=" . $linkToSend . ">click here</a></P> ";
            $mailer->sendMail("CreditPlus", $_POST['email'], "Registered Successfully", $message);
            $activity->logActivity(
                $_SESSION['user'],
                "Registered user ",
                "user registered in sucessfully",
                $_SESSION['email'],
                $_SESSION['gender']
            );

            //redirect
            $_SESSION['success'] = "user Added Successfully";
            header("Location:index.php");
            //redirect
        } else {
            //die("Oops there was an error");
            $_SESSION['success'] = "An error occured !Please try again";
            header("Location:create.php");
        }
    }
} else {
    //die("not set");
    $_SESSION['success'] = "Something went wrong Please contact Support";
    header("Location:create.php");
}
