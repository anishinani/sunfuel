<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Stage</title>

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
        include_once("../sidebar.php");
        include("../../utils/dbaccess.php");
        include("../../utils/activityLogger.php");
        $activity =  new ActivityLogger();
        $dbAccess =  new DbAccess();

        //select from db by id
        if (isset($_GET['update'])) {
            $id = $_GET["update"];
            //die("The id is " . $id);
            $results  = $dbAccess->select("stage", "", ["stageId" => $id]);

            //join
            //$sql = "SELECT fuelStationName, fuelStationId";
            $currentStation = $dbAccess->select("fuelstation", "", ["fuelStationId" => $results[0]['fuelStationId']]);
            //var_dump($currentStation[0]['fuelStationName']);
            //join
            //var_dump($results[0]['fuelStationName']);
            $fuelStations  =  $dbAccess->select("fuelstation", ["fuelStationId", "fuelStationName"]);

            $bodaUsers  = $dbAccess->select("bodauser", ['bodaUserId', "bodaUserName"], ["bodaUserRole" => "Chairman"]);
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
                                <li class="breadcrumb-item active">Stage</li>
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
                                    <p class="login-box-msg">Edit Stage</p>
                                    <form method="POST" action="./update.php">


                                        <div class="form-group mb-3">
                                            <label for="">Stage Name</label>
                                            <input type="text" name="name" required class="form-control" placeholder="enter stage name" value="<?= $results[0]['stageName']; ?>" />

                                        </div>

                                        <!--fuel station-->
                                        <div class="form-group mb-3">
                                            <div class="form-group">
                                                <label for="my-select">Fuel Station</label>
                                                <select id="my-select" class="form-control" name="fuelStationId">
                                                    <option selected value="<?= $currentStation[0]["fuelStationId"]; ?>"><?= $currentStation[0]["fuelStationName"]; ?></option>
                                                    <?php
                                                    for ($i = 0; $i < count($fuelStations); $i++) {
                                                    ?>
                                                        <option value="<?= $fuelStations[$i]["fuelStationId"] ?>">
                                                            <?= $fuelStations[$i]["fuelStationName"] ?></option>

                                                    <?php } ?>
                                                </select>
                                            </div>

                                        </div>
                                        <!--fuel station-->
                                        <!--select chairman-->
                                        <div class="form-group mb-3">
                                            <div class="form-group">
                                                <label for="my-select">Select Chairman</label>
                                                <select id="my-select" class="form-control" name="chairman">
                                                    <option disabled selected>Select Chairman</option>
                                                    <?php
                                                    for ($i = 0; $i < count($bodaUsers); $i++) {
                                                    ?>
                                                        <option value="<?= $bodaUsers[$i]["bodaUserId"] ?>">
                                                            <?= $bodaUsers[$i]["bodaUserName"] ?></option>

                                                    <?php } ?>
                                                </select>
                                            </div>

                                        </div>
                                        <!--select chairman-->


                                        <!--hidden-->
                                        <input type="hidden" name="id" value="<?= $_GET['update']; ?>" />
                                        <!--hidden-->
                                        <!-- /.col -->
                                        <div class="col-12">
                                            <button type="submit" class="style_button" name="addStage">Update Stage </button>
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