<?php
/**
 * store-feature
 * creates a new feature with in the application and shows the directory where features  are stored.
 * @author ThinkXSoftware
 * */ 

/*************/
// WARNING: file should be commented out or given file access permissions on the server
/**************/ 

require_once '../../utils/session.php';


// if(strtolower(trim($_SERVER['REQUEST_METHOD'])) == 'post') session_destroy(); header($fallback);

require_once '../../utils/dbaccess.php';

require_once '../../controllers/access/AccessController.php';

require_once '../../utils/helpers.php';

if(empty($_POST) && !isset($_POST['name']) && !isset($_POST['icon'])){

    $_SESSION['error'] = "Invalid request parameters";

    header("Location:create-module.php");
}

$_SESSION['errors'] = array();

$helpers =  new HelperFunctions();

$dbAccess = new DbAccess();
//check errors and clean o
foreach ($_POST as $key => $value) {
    if ($key == 'addModule') {
        continue;
    } else {
        if ($helpers->checkEmptyFields($value) != NULL) {
            $_SESSION['errors'][$key]  =  $key ." ". $helpers->checkEmptyFields($value);
        } else {
            $dbAccess->clean($value);
        }
    }
}

if(count($_SESSION['errors']) > 0) {

    $_SESSION['error'] = "Check Some of your fields";

    header("Location:create-module.php");
}

$accessController = new AccessController();

$data = array(
    'name' => $_POST['name'],
    'icon' => $_POST['icon'],
    'description' => $_POST['description']
);

 if($accessController->createModule($data)){

    $_SESSION['success'] = "module was created successfully";

    header("Location:create-module.php");
}else{
    $_SESSION['success'] = "Operation was unsuccessful";

    header("Location:create-module.php");
}
 