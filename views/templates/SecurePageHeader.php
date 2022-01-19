<?php

require_once '../../utils/session.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CreditPlus Fuel System </title>
    <?php 
        // load relevant style in an optimal manner
        include_once '../templates/optimized_styles.php';
    ?>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <?php
        include_once '../../utils/dbaccess.php';
        include_once '../../controllers/access/AccessController.php';
        $accessController = new AccessController();
        $dbAccess = new DbAccess();
        include_once '../templates/navbar.php';
        include_once '../templates/sidebar.php';
        ?>
        <div class="content-wrapper">

        <?php include_once '../templates/flashMessages.php' ?>

     