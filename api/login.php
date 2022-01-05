<?php
session_start();
require_once("../utils/dbaccess.php");
require_once("../utils/activityLogger.php");
require_once("../controllers/User.php");
require_once("../utils/helpers.php");
require_once("../controllers/RolesController.php");

$user = new User();
$roles =  new Roles();


//helper functions
$helpers  = new HelperFunctions();
$activity = new ActivityLogger();


if (isset($_POST['login'])) {
    //die("login");
    //die("login");
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($helpers->checkEmptyFields($email) != NULL || $helpers->checkEmptyFields($password) != NULL) {
        //header('location:home.php?empty=required');
        //$_SESSION['message'] = 'All fields aree Required';
        //header('location:index.php');
        die("All fields are Required");
    }
    //die("here");

    //check email
    $validatedEmail    = $helpers->checkEmail($email);
    if ($validatedEmail == NULL) {
        //header('location:home.php');
        die("Invalid Credentials");
    } else {
        ///$auth = $user->check_login($username, $password);
        //die("login");
        $auth = $user->check_login($validatedEmail, $password);



        if ($auth  == NULL) {
            //$_SESSION['message'] = 'Invalid username or password';
            die("Invalid Credentials");
        } else {

            $activity->logActivity($auth['name'], "login", "Logged in sucessfully", $auth['email'], $auth['gender']);
            $userArray = array();
            array_push($userArray, array("name" => $auth['name']));
            array_push($userArray, array("email" => $auth['email']));
            array_push($userArray, array("gender" => $auth['gender']));
            array_push($userArray, array("phoneNumber" => $auth['phoneNumber']));
            array_push($userArray, array("roleId" => $auth['roleId']));
            array_push($userArray, array("dp" => $auth['profilePicture']));

            echo json_encode($userArray);

            //return $auth;
            // $_SESSION['user'] = $auth['name'];
            // $_SESSION['userId'] = $auth['adminId'];
            // $_SESSION['roleId'] = $auth['roleId'];
            // $_SESSION['gender'] = $auth['gender'];
            // $_SESSION['email'] = $auth['email'];

            //get permissions
            //$permissions = $roles->getSpecificPermissions($auth['roleId']);
            //$_SESSION['roles'] = $permissions;



            //header('location:views/home.php');
        }
    }
} else {
     die ("Invalid Credentials");
}
