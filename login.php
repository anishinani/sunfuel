<?php
//start session
session_start();

require_once("utils/dbaccess.php");
require_once('controllers/User.php');
require_once("utils/activityLogger.php");
require_once("utils/helpers.php");
require_once("controllers/RolesController.php");

$user = new User();
$roles =  new Roles();
//helper functions
$helpers  = new HelperFunctions();



$activity = new ActivityLogger();


if (isset($_POST['login'])) {

	$email = $_POST['email'];
	$password = $_POST['password'];

	if ($helpers->checkEmptyFields($email) != NULL || $helpers->checkEmptyFields($password) != NULL) {
		header('location:home.php?empty=required');
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
			header('location:index.php?invalid=true');
		} else {
			$_SESSION['user'] = $auth['name'];
			$_SESSION['userId'] = $auth['adminId'];
			$_SESSION['roleId'] = $auth['roleId'];
			$_SESSION['gender'] = $auth['gender'];
			$_SESSION['email'] = $auth['email'];

			//get permissions
			$permissions = $roles->getSpecificPermissions($auth['roleId']);
			$_SESSION['roles'] = $permissions;

			$value = $activity->logActivity($_SESSION['user'], "login", "Logged in sucessfully", $_SESSION['email'], $_SESSION['gender']);

			header('location:views/home.php');
		}
	}
}

// else{
// 	$_SESSION['message'] = 'You need to login first';
// 	header('location:index.php');
// }
