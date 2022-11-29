<?php
session_start();
include_once("../../utils/dbaccess.php");
include_once("../../controllers/User.php");
include_once("../../utils/helpers.php");
include_once("../../utils/mailer/mailer.php");
include_once("../../utils/pin.php");
include_once("../../utils/activityLogger.php");

$helpers = new HelperFunctions();
$dbAccess =  new DbAccess();
$mailer = new MyMail();
$pin = new pin();
$activity = new ActivityLogger();
if (isset($_POST['request'])) {

    $validatedEmail    = $helpers->checkEmail($_POST['email']);
    $hashedPass  = $pin->hashPass($validatedEmail);



    if ($validatedEmail == NULL) {
        $_SESSION['requestPasswordError'] = "wrong email format";
        header("Location:/creditpluswebapp/forgotpassword.php");
    } else {
        //check if email exists
        $emailExists = $dbAccess->select("users", ["email", "adminId", "name", "gender"], ["email" => $validatedEmail]);
        if (count($emailExists)) {

            //update
            $updated = $dbAccess->update("administrators", ["setPassword" => $hashedPass], ["adminId" => $emailExists[0]["adminId"]]);
            // var_dump("we are here");
            // var_dump($updated);
            // die($updated);
            //update

            $localLink = "localhost/creditpluswebapp/views/auth/setPassword.php?token=$hashedPass";
            $serverLink = "http://app.creditplus.ug/creditpluswebapp/views/auth/setPassword.php?token=$hashedPass";
            $linkToSend = "";
            $ip_address = $_SERVER['REMOTE_ADDR'];

            if ($ip_address == '::1') {

                $linkToSend = $localLink;
            } else {

                $linkToSend = $serverLink;
            }

            //send email
            $message = "<P>We received a notification that you forgot your password   <a href=" . $linkToSend . ">click here to reset password</a></P> ";
            $mailer->sendMail("CreditPlus", $_POST['email'], "Password Recovery", $message);
            $activity->logActivity(
                $emailExists[0]['name'],
                "Forgot password request ",
                "Request sent  sucessfully",
                $emailExists[0]['email'],
                $emailExists[0]['gender']
            );
            $_SESSION['requestPasswordSuccess'] = "A recovery password link has been sent to your email";
            header("Location:/creditpluswebapp/forgotpassword.php");
        } else {
            $_SESSION['requestPasswordError'] = "Email doesnot exist";
            header("Location:/creditpluswebapp/forgotpassword.php");
        }
    }
} else {
    header("Location:/creditpluswebapp/index.php");
}
