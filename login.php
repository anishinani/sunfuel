<?php
//start session
session_start();

require_once("utils/dbaccess.php");
require_once('controllers/User.php');
require_once("utils/activityLogger.php");

$user = new User();
//helper functions
$helpers  = new HelperFunctions();

$activity = new ActivityLogger();


if (isset($_POST['login'])) {

	$email = $_POST['email'];
	$password = $_POST['password'];

	if ($helpers->checkEmptyFields($email) || $helpers->checkEmptyFields($password)) {
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
			$_SESSION['roleId'] = $auth['role'];
			$_SESSION['gender'] = $auth['gender'];
			$_SESSION['email'] = $auth['email'];
			$value = $activity->logActivity($_SESSION['user'], "login", "Logged in sucessfully", $_SESSION['email'], $_SESSION['gender']);
			//var_dump($value);
			die($value);
			//header('location:home.php');
		}
	}
}

// else{
// 	$_SESSION['message'] = 'You need to login first';
// 	header('location:index.php');
// }
