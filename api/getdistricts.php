<?php
session_start();
require_once("../utils/dbaccess.php");
//require_once("../utils/activityLogger.php");
$dbAccess = new DbAccess();
echo json_encode($dbAccess->select("districts"));
