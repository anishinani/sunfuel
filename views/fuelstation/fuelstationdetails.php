<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location:/creditpluswebapp/index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Credit Plus |FuelStation</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/creditpluswebapp/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="/creditpluswebapp/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/creditpluswebapp/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="/creditpluswebapp/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/creditpluswebapp/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="/creditpluswebapp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/creditpluswebapp/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="/creditpluswebapp/plugins/summernote/summernote-bs4.min.css">


</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php
        if (isset($_POST['details'])) {

            $id = $_POST['id'];
            $_SESSION["fuelDetailsId"] =  $id;
            //die($id);
        } else {
            die("not sent");
        }

        include_once("../navbar/navbar.php");
        include_once("../sidebar.php");
        include_once("../../utils/dbaccess.php");
        //SELECT bodaUserId FROM bodauser WHERE DATE(updated_at) = CURDATE();
        $dbAccess =  new DbAccess();

        $details = $dbAccess->select('fuelstation', "", ['fuelstationId' => $id])[0];

        $fuelStationName = $dbAccess->select("fuelstation", ["fuelStationName"], ["fuelStationId" => $id])[0]['fuelStationName'];
        $totalBorrowers =  $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total
          FROM bodauser WHERE DATE(updated_at) = CURDATE() AND bodaUserStatus=2 AND fuelStationId=$id")[0]["total"];

        $totalActiveBodaUsers  = $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total
        FROM bodauser WHERE bodaUserStatus=1 AND fuelStationId=$id")[0]["total"];

        $totalInActiveBodaUsers  =  $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total
        FROM bodauser WHERE bodaUserStatus=0 AND fuelStationId=$id")[0]["total"];
        //$dbAccess->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "0"]);
        $totalDefaultedBodaUsers  =  $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total
        FROM bodauser WHERE bodaUserStatus=2 AND fuelStationId=$id")[0]["total"];
        //die($totalInActiveBodaUsers);


        $totalActiveStages  = $dbAccess->selectQuery("SELECT COUNT(stageId) AS total FROM stage 
        INNER JOIN fuelstation ON fuelstation.fuelStationId = stage.fuelStationId 
         WHERE  stage.fuelStationId=$id AND stage.stageStatus=1;")[0]['total'];

        $totalInActiveStages  = $dbAccess->selectQuery("SELECT COUNT(stageId) AS total FROM stage 
         INNER JOIN fuelstation ON fuelstation.fuelStationId = stage.fuelStationId 
          WHERE  stage.fuelStationId=$id AND stage.stageStatus=0;")[0]['total'];

        $totalDefaultStages  = $dbAccess->selectQuery("SELECT COUNT(stageId) AS total FROM stage 
         INNER JOIN fuelstation ON fuelstation.fuelStationId = stage.fuelStationId 
          WHERE  stage.fuelStationId=$id AND stage.stageStatus=2;")[0]['total'];

             


        //fuel consumption
        $expectedFuelPerDay = $totalActiveBodaUsers * 15000;
        //die($expectedFuelPerDay);
        $expectedAmountRecoveredPerDay =  ($totalBorrowers * 1000) + $expectedFuelPerDay;
        $expectedCrossProfit = $expectedAmountRecoveredPerDay - $expectedFuelPerDay;





        ?>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?= $fuelStationName ?></h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active"><?= $fuelStationName ?></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="container-fluid">
                        <div class="">


                            <div class="col-sm-12">
                                <div class="statistics-details d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="statistics-title">Total Amount</p>
                                        <h3 class="rate-percentage"><?= "shs " . number_format($details['totalAmount']) ?></h3>

                                    </div>
                                    <div>
                                        <p class="statistics-title">Current Amount</p>
                                        <h3 class="rate-percentage"><?= "shs " . number_format($details['currentAmount']) ?></h3>
                                    </div>
                                    <div>
                                        <p class="statistics-title">Bank Name</p>
                                        <h3 class="rate-percentage"><?= $details['bankName'] ?></h3>
                                    </div>
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">Bank Branch</p>
                                        <h3 class="rate-percentage"><?= $details['bankBranch'] ?></h3>
                                    </div>
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">Account Name</p>
                                        <h3 class="rate-percentage"><?= $details['AccName'] ?></h3>
                                    </div>
                                    <div class="d-none d-md-block">
                                        <p class="statistics-title">Account Number</p>
                                        <h3 class="rate-percentage"><?= $details['AccNumber'] ?></h3>

                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- Small boxes (Stat box) -->
                    <div class="row">



                        <!-- ./col -->
                        <div class="col-lg-3 col-6">

                            <!-- small box -->
                            <a href="#">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?= number_format($expectedFuelPerDay, 1) ?></h3>

                                        <p>Total Expexted Fuel Per Day</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>

                                </div>
                            </a>

                        </div>


                        <!--col-->
                        <div class="col-lg-3 col-6">
                            <a href="/creditpluswebapp/views/fuelstation/activeOnEachStage.php?stationname=<?=$fuelStationName?>&data=activebodausers">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?= $totalActiveBodaUsers ?></h3>

                                        <p>Total Active Boda Users</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>

                                </div>
                            </a>

                        </div>
                        <!--col-->
                        <!--col-->
                        <div class="col-lg-3 col-6">
                            <a href="/creditpluswebapp/views/fuelstation/activeOnEachStage.php?stationname=<?=$fuelStationName?>&data=inactivebodausers">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?= $totalInActiveBodaUsers ?></h3>

                                        <p>Total Inactive Active Boda Users</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>

                                </div>
                            </a>

                        </div>
                        <!--col-->
                        <!--col-->
                        <div class="col-lg-3 col-6">
                            <a href="/creditpluswebapp/views/fuelstation/activeOnEachStage.php?stationname=<?=$fuelStationName?>&data=defaultedbodausers">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?= $totalDefaultedBodaUsers ?></h3>

                                        <p>Total Defaulted Boda Users</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>

                                </div>
                            </a>

                        </div>
                        <!--col-->
                        <!--col-->
                        <div class="col-lg-3 col-6">
                            <a href="/creditpluswebapp/views/stage/indexone.php?name=<?=$fuelStationName?>&data=activestages">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?= $totalActiveStages ?></h3>

                                        <p>Total Active Stages</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>

                                </div>
                            </a>

                        </div>
                        <!--col-->
                        <!--col-->
                        <div class="col-lg-3 col-6">
                            <a href="/creditpluswebapp/views/stage/indexone.php?name=<?=$fuelStationName?>&data=inactivestages">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?= $totalInActiveStages ?></h3>

                                        <p>Total InActive Stages</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>

                                </div>
                            </a>

                        </div>
                        <!--col-->
                        <!--col-->
                        <div class="col-lg-3 col-6">
                            <a href="/creditpluswebapp/views/stage/indexone.php?name=<?=$fuelStationName?>&data=defaultedstages">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?= $totalDefaultStages ?></h3>

                                        <p>Total Defaulted Stages</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>

                                </div>
                            </a>

                        </div>
                        <!--col-->

                        <!-- /.row -->
                        <!-- Main row -->
                        <div class="row">

                        </div>
                        <!-- /.row (main row) -->
                    </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
            <!-- <div class="container-fluid">
                <div class="container rounded bg-white mt-5 mb-5">


                    <div class="col-sm-12">
                        <div class="statistics-details d-flex align-items-center justify-content-between">
                            <div>
                                <p class="statistics-title">Bounce Rate</p>
                                <h3 class="rate-percentage">32.53%</h3>
                                <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>-0.5%</span></p>
                            </div>
                            <div>
                                <p class="statistics-title">Page Views</p>
                                <h3 class="rate-percentage">7,682</h3>
                                <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+0.1%</span></p>
                            </div>
                            <div>
                                <p class="statistics-title">New Sessions</p>
                                <h3 class="rate-percentage">68.8</h3>
                                <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
                            </div>
                            <div class="d-none d-md-block">
                                <p class="statistics-title">Avg. Time on Site</p>
                                <h3 class="rate-percentage">2m:35s</h3>
                                <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
                            </div>
                            <div class="d-none d-md-block">
                                <p class="statistics-title">New Sessions</p>
                                <h3 class="rate-percentage">68.8</h3>
                                <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
                            </div>
                            <div class="d-none d-md-block">
                                <p class="statistics-title">Avg. Time on Site</p>
                                <h3 class="rate-percentage">2m:35s</h3>
                                <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
                            </div>
                        </div>
                    </div>
                </div>


            </div> -->
        </div>
    </div>
    <!-- /.content-wrapper -->
    <?php
    include_once("../footer/footer.php");
    ?>


    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="/creditpluswebapp/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="/creditpluswebapp/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge(' uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="/creditpluswebapp/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="/creditpluswebapp/plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="/creditpluswebapp/plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="/creditpluswebapp/plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="/creditpluswebapp/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="/creditpluswebapp/plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="/creditpluswebapp/plugins/moment/moment.min.js"></script>
    <script src="/creditpluswebapp/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="/creditpluswebapp/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="/creditpluswebapp/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="/creditpluswebapp/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/creditpluswebapp/dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="/creditpluswebapp/dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="/creditpluswebapp/dist/js/pages/dashboard.js"></script>

</body>

</html>