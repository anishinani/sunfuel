<?php
//start session
session_start();

require_once("utils/dbaccess.php");
require_once('controllers/User.php');
require_once("utils/activityLogger.php");
require_once("utils/helpers.php");
require_once "controllers/access/AccessController.php";

$user = new User();
$accessController =  new AccessController();
//helper functions
$helpers  = new HelperFunctions();



$activity = new ActivityLogger();


if (isset($_POST['login'])) {

	$email = $_POST['email'];
	$password = $_POST['password'];

	if ($helpers->checkEmptyFields($email) != NULL || $helpers->checkEmptyFields($password) != NULL) {
		//header('location:home.php?empty=required');
		$_SESSION['message'] = 'All fields required';
		header('location:index.php');
	}

	//check email
	$validatedEmail	= $helpers->checkEmail($email);
	if ($validatedEmail == NULL) {
		header('location:home.php');
	} else {
		///$auth = $user->check_login($username, $password);
		$auth = $user->check_login($validatedEmail, $password);

		if ($auth  == NULL) {
			$_SESSION['message'] = 'Invalid username or password';
			header('location:index.php');
		} else {
			$_SESSION['user'] = $auth['name'];
			$_SESSION['gender'] = $auth['gender'];
			$_SESSION['email'] = $auth['email'];
			$_SESSION['auth'] = $auth['adminId'];


			//get permissions
			$permissions = $accessController->getUserPermissions($auth['roleId']);
            $_SESSION['permissions'] = $permissions['permissions'];
			$_SESSION['modules'] = $permissions['modules'];


			$value = $activity->logActivity($_SESSION['user'], "login", "Logged in successfully", $_SESSION['email'], $_SESSION['gender']);

			$_SESSION['success'] = 'Welcome Back ! '.$auth['name'];

			header('location:views/dashboard/');
		}
	}
}

// else{
// 	$_SESSION['message'] = 'You need to login first';
// 	header('location:index.php');
// }
