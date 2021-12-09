<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Boda User</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href=" ../../plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href=" ../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href=" ../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href=" ../../plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href=" ../../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href=" ../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href=" ../../plugins/daterangepaicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href=" ../../plugins/summernote/summernote-bs4.min.css">
    <style>
        .style_button {
            background: #1c478e !important;
            color: #fff;
            width: 100% !important;
            border: none !important;
            height: 40px !important;
            cursor: pointer;
            border-radius: 10px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php
        include_once("../navbar/navbar.php");
        include_once("../sidebar/sidebar.php");
        include("../../utils/dbaccess.php");
        include("../../utils/activityLogger.php");
        $activity =  new ActivityLogger();
        $dbAccess =  new DbAccess();

        //select from db by id
        if (isset($_GET['update'])) {
            $id = $_GET["update"];
            //die("The id is " . $id);
            $results  = $dbAccess->select("bodauser", "", ["bodaUserId" => $id]);
            //var_dump($results[0]['fuelStationName']);

            $fuelstation  =  $dbAccess->select("fuelstation", ["fuelStationId", "fuelStationName"]);
            $stage  =  $dbAccess->select("stage", ["stageId", "stageName"]);

            //current stage
            $currentStage   =  $dbAccess->select("stage", ["stageId", "stageName"], ["stageId" => $results[0]["stageId"]]);

            //current fuel station
            $currentfuelStaion =   $dbAccess->select("fuelstation", ["fuelStationId", "fuelStationName"], ["fuelStationId" => $results[0]["fuelStationId"]]);
        }
        ?>



        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Dashboard</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Boda User</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- error part -->
                    <?php if (isset($_SESSION['errors'])) { ?>

                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                <?php
                                foreach ($_SESSION['errors'] as $key => $value) {
                                    echo "<li>" . $value . "</li>";
                                }
                                ?>
                            </ul>
                        </div>

                        <!--error part-->
                    <?php }

                    ?>
                    <div class="row">
                        <!--form add user -->
                        <div class="register-box m-auto col-md-8">
                            <div class="card card-outline card-primary">

                                <div class="card-body">
                                    <p class="login-box-msg">Edit Boda user</p>
                                    <form method="POST" action="./update.php">
                                        <div class="form-group mb-3">
                                            <label for="">Boad User Names</label>
                                            <input type="text" name="name" required class="form-control" placeholder="enter  names " value="<?= $results[0]["bodaUserName"] ?>" />

                                        </div>
                                        <!--address-->
                                        <div class="form-group mb-3">
                                            <label for="">NIN Number</label>
                                            <input type="text" name="nin" required class="form-control" placeholder="enter valid nin number" value="<?= $results[0]["bodaUserNIN"] ?>" />
                                        </div>
                                        <!--address-->
                                        <!--person-->
                                        <div class="form-group mb-3">
                                            <label for=""> Boda Number</label>
                                            <input type="text" name="bodaNumber" required class="form-control" placeholder="enter boda name" value="<?= $results[0]["bodaUserBodaNumber"] ?>" />

                                        </div>
                                        <!--person-->

                                        <!--phone-->
                                        <div class="form-group mb-3">
                                            <label for=""> Phone Number</label>
                                            <input type="text" name="phoneNumber" required class="form-control" placeholder="enter phone number " value="<?= $results[0]["bodaUserPhoneNumber"] ?>" />
                                        </div>
                                        <!---phone-->


                                        <!--fuel station-->
                                        <div class="form-group mb-3">
                                            <div class="form-group">
                                                <label for="my-select">Fuel Station</label>
                                                <select id="my-select" class="form-control" name="fuelStationId">
                                                    <option disabled selected value="<?= $currentfuelStaion[0]["fuelStationId"]; ?>"><?= $currentfuelStaion[0]["fuelStationName"]; ?></option>
                                                    <?php
                                                    for ($i = 0; $i < count($fuelstation); $i++) {
                                                    ?>
                                                        <option value="<?= $fuelstation[$i]["fuelStationId"] ?>">
                                                            <?= $fuelstation[$i]["fuelStationName"] ?></option>

                                                    <?php } ?>
                                                </select>
                                            </div>

                                        </div>
                                        <!--fuel station-->

                                        <!--stage-->
                                        <div class="form-group mb-3">
                                            <div class="form-group">
                                                <label for="my-select">Stage</label>
                                                <select id="my-select" class="form-control" name="stageId">
                                                    <option value="<?= $currentStage[0]["stageId"] ?>" selected><?= $currentStage[0]["stageName"] ?></option>
                                                    <?php
                                                    for ($i = 0; $i < count($stage); $i++) {
                                                    ?>
                                                        <option value="<?= $stage[$i]["stageId"] ?>">
                                                            <?= $stage[$i]["stageName"] ?></option>

                                                    <?php } ?>
                                                </select>
                                            </div>

                                        </div>
                                        <!--stage-->

                                        <input type="hidden" name="id" value="<?= $id; ?>" />
                                        <!-- /.col -->
                                        <div class="col-12">
                                            <button type="submit" class="style_button" name="addBodaUser">Update Boda User</button>
                                        </div>
                                        <!-- /.col -->
                                </div>


                                </form>

                            </div>
                            <!-- /.form-box -->
                        </div><!-- /.card -->
                    </div>
                    <!-- /.register-box -->

                    <!--form add user-->
                </div>
                <!-- /.row -->
                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->
                    <section class="col-lg-7 connectedSortable">


                    </section>
                    <!-- right col -->
                </div>
                <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include_once("../footer/footer.php"); ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src=" ../../plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src=" ../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="../../plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src=" ../../plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src=" ../../plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src=" ../../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src=" ../../plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src=" ../../plugins/moment/moment.min.js"></script>
    <script src=" ../../plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src=" ../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src=" ../../plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src=" ../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src=" ../../dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src=" ../../dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src=" ../../dist/js/pages/dashboard.js"></script>
</body>

</html>