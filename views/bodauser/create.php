<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create new boda user</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href=" /creditpluswebapp/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="/creditpluswebapp/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/creditpluswebapp/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href=" /creditpluswebapp/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href=" /creditpluswebapp/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href=" /creditpluswebapp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href=" /creditpluswebapp/plugins/daterangepaicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href=" /creditpluswebapp/plugins/summernote/summernote-bs4.min.css">
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

        .showAlternative {
            display: none;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php
        include_once("../navbar/navbar.php");
        include_once("../sidebar.php");
        include("../../utils/dbaccess.php");

        $dbAccess =  new DbAccess();

        $results  =  $dbAccess->select("fuelstation", ["fuelStationId", "fuelStationName"]);
        $stage  =  $dbAccess->select("stage", ["stageId", "stageName"]);

        //modify results

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

                    unset($_SESSION['errors']);
                    ?>
                    <div class="row">
                        <!--form add user -->
                        <div class="register-box m-auto col-md-8">
                            <div class="card card-outline card-primary">

                                <div class="card-body">
                                    <p class="login-box-msg">Register a new boda user</p>
                                    <form method="POST" action="./store.php" enctype="multipart/form-data">


                                        <div class="form-group mb-3">
                                            <label for="">Boad User Names</label>
                                            <input type="text" name="name" required class="form-control" placeholder="enter  names " />

                                        </div>
                                        <!--address-->
                                        <div class="form-group mb-3">
                                            <label for="">NIN Number</label>
                                            <input type="text" name="nin" required class="form-control" placeholder="enter valid nin number" />
                                        </div>
                                        <!--address-->
                                        <!--person-->
                                        <div class="form-group mb-3">
                                            <label for=""> Boda Number</label>
                                            <input type="text" name="bodaNumber" required class="form-control" placeholder="enter boda number" />

                                        </div>
                                        <!--person-->

                                        <!--phone-->
                                        <div class="form-group mb-3">
                                            <label for=""> Phone Number</label>
                                            <input type="text" name="phoneNumber" required class="form-control" placeholder="enter phone number " />
                                        </div>
                                        <!---phone-->

                                        <!--role-->
                                        <div class="form-group">
                                            <label for="my-select">Select Role</label>
                                            <select class="form-control" name="role" id="role">
                                                <option disabled selected>select role</option>
                                                <option value="Chairman">Stage Chairman</option>
                                                <option value="BodaUser">Boda User</option>
                                            </select>
                                        </div>


                                        <!--phone-->
                                        <div class="form-group mb-3 showAlternative" id="another">
                                            <label for=""> Alternative Phone Number</label>
                                            <input type="text" name="anotherNumber" class="form-control" placeholder="enter another number " />
                                        </div>
                                        <!---phone-->


                                        <!--role-->

                                        <!--front photo-->
                                        <div class="form-group mb-3">
                                            <label for=""> Front NIN Photo</label>
                                            <input type="file" name="frontPhoto" required class="form-control" accept="image/*" />
                                        </div>
                                        <!--front photo-->

                                        <!--back photo-->
                                        <div class="form-group mb-3">
                                            <label for=""> Back NIN Photo</label>
                                            <input type="file" name="backPhoto" required class="form-control" accept="image/*" />
                                        </div>
                                        <!--back photo-->


                                        <!--fuel station-->
                                        <div class="form-group mb-3">
                                            <div class="form-group">
                                                <label for="my-select">Fuel Station</label>
                                                <select id="my-select" class="form-control" name="fuelStationId">
                                                    <option disabled selected>select station</option>
                                                    <?php
                                                    for ($i = 0; $i < count($results); $i++) {
                                                    ?>
                                                        <option value="<?= $results[$i]["fuelStationId"] ?>">
                                                            <?= $results[$i]["fuelStationName"] ?></option>

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
                                                    <option disabled selected>choose stage</option>
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
                                        <!-- /.col -->
                                        <div class="col-12">
                                            <button type="submit" class="style_button" name="addBodaUser">Register Boda User</button>
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
    <script src="/creditpluswebapp/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src=" /creditpluswebapp/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src=" /creditpluswebapp/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="/creditpluswebapp/plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src=" /creditpluswebapp/plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src=" /creditpluswebapp/plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src=" /creditpluswebapp/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src=" /creditpluswebapp/plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src=" /creditpluswebapp/plugins/moment/moment.min.js"></script>
    <script src=" /creditpluswebapp/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src=" /creditpluswebapp/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src=" /creditpluswebapp/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src=" /creditpluswebapp/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src=" /creditpluswebapp/dist/js/adminlte.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src=" /creditpluswebapp/dist/js/demo.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src=" /creditpluswebapp/dist/js/pages/dashboard.js"></script>
    <script type="text/javascript">
        $(function() {
            $("#role").change(function() {
                //alert("clicked");
                let role = $("#role").val();
                //alert(role);
                if (role == "Chairman") {
                    $(".showAlternative").removeClass("showAlternative")
                } else {
                    //alert("not true");
                    $("#another").addClass("showAlternative")
                }
            })
        })
    </script>
</body>



</html>