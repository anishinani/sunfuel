<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create new station</title>

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

        .form-control {
            text-transform: uppercase !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php
        include_once("../navbar/navbar.php");
        include("../sidebar.php");
        include_once("../../utils/dbaccess.php");
        $dbAccess = new DbAccess();


        $districts  =  $dbAccess->select("districts");
        // $county  =  $dbAccess->select("county");
        // $subcounty =  $dbAccess->select("subcounty");
        // $parishes =  $dbAccess->select("parishes");
        // $villages =  $dbAccess->select("villages");



        $stage  =  $dbAccess->select("stage", ["stageId", "stageName"]);
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
                                <li class="breadcrumb-item active">fuel station</li>
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
                                    <p class="login-box-msg">Register a new station</p>
                                    <form method="POST" action="" enctype="multipart/form-data" id="form">


                                        <!--district-->
                                        <div class="form-group">
                                            <label for="my-select">Station District</label>
                                            <select id="districts" class="form-control" name="district">
                                                <option selected disabled>select district</option>
                                                <?php
                                                for ($i = 0; $i < count($districts); $i++) {
                                                ?>
                                                    <option value="<?= $districts[$i]["districtCode"] ?>">
                                                        <?= $districts[$i]["districtName"] ?></option>

                                                <?php } ?>
                                            </select>
                                        </div>
                                        <!--district-->

                                        <!--count-->
                                        <div class="form-group">
                                            <label for="my-select">Station County</label>
                                            <select id="county" class="form-control" disabled name="county">
                                                <option disabled selected>select county</option>

                                            </select>
                                        </div>
                                        <!--count-->
                                        <!--subcounty-->
                                        <div class="form-group">
                                            <label for="subcounty">Station Subcounty</label>
                                            <select id="subcounty" class="form-control" disabled name="subcounty">
                                                <option>select sub county</option>

                                            </select>
                                        </div>
                                        <!--subcounty-->
                                        <!--parish-->
                                        <div class="form-group">
                                            <label for="my-select">Station Parish</label>
                                            <select id="parish" class="form-control" disabled name="parish">
                                                <option selected disabled>select parish</option>

                                            </select>
                                        </div>
                                        <!--parish-->
                                        <!--village-->
                                        <div class="form-group">
                                            <label for="my-select">Station Village</label>
                                            <select id="village" class="form-control" disabled name="village">
                                                <option selected disabled>select village</option>

                                            </select>
                                        </div>
                                        <!--village-->
                                        <div class="form-group mb-3">
                                            <label for="">Station Name</label>
                                            <input type="text" name="name" required class="form-control" placeholder="enter station name" />

                                        </div>
                                        <!--address-->
                                        <div class="form-group mb-3">
                                            <label for="">Station Address</label>
                                            <input type="text" name="address" required class="form-control" placeholder="enter station address" />
                                        </div>
                                        <!--address-->
                                        <!--bank name-->
                                        <div class="form-group mb-3">
                                            <label for="">Bank Name</label>
                                            <input type="text" name="bankname" required class="form-control" placeholder="enter station bank name" />
                                        </div>
                                        <!--bank name-->
                                        <!--Bank Branch-->
                                        <div class="form-group mb-3">
                                            <label for="">Bank Branch</label>
                                            <input type="text" name="bankbranch" required class="form-control" placeholder="enter station Bank Branch" />
                                        </div>
                                        <!--Bank Branch-->
                                        <!--Account Name-->
                                        <div class="form-group mb-3">
                                            <label for="">Account Name</label>
                                            <input type="text" name="accountname" required class="form-control" placeholder="enter station Account Name" />
                                        </div>
                                        <!--Account Name-->
                                        <!--Account Number-->
                                        <div class="form-group mb-3">
                                            <label for="">Account Number</label>
                                            <input type="text" name="accountnumber" required class="form-control" placeholder="enter station Account Number" />
                                        </div>
                                        <!--Account Number-->

                                        <!--person-->
                                        <div class="form-group mb-3">
                                            <label for="">Contact Person</label>
                                            <input type="text" name="person" required class="form-control" placeholder="enter person name" />

                                        </div>
                                        <!--person-->
                                        <!--person-->
                                        <div class="form-group mb-3">
                                            <label for="">Contact Person NIN </label>
                                            <input type="text" name="nin" required class="form-control" placeholder="enter person nin number" />

                                        </div>
                                        <!--person-->

                                        <!--phone-->
                                        <div class="form-group mb-3">
                                            <label for=""> Contact Phone Number</label>
                                            <input type="text" name="phoneNumber" required class="form-control" placeholder="enter phone number name" />
                                        </div>
                                        <!---phone-->
                                        <!--front photo-->
                                        <div class="form-group mb-3">
                                            <label for=""> Contact Person Front ID Photo</label>
                                            <input type="file" name="frontPhoto" required class="form-control" accept="image/*" />
                                        </div>
                                        <!--front photo-->

                                        <!--back photo-->
                                        <div class="form-group mb-3">
                                            <label for="">Contact Person Back ID Photo</label>
                                            <input type="file" name="backPhoto" required class="form-control" accept="image/*" />
                                        </div>
                                        <!--back photo-->

                                        <!-- /.col -->
                                        <div class="col-12">
                                            <button type="submit" class="style_button" name="addStation" id="save">save fuel station</button>
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

    <script>
        //alert("here")
        $(document).ready(function() {
            //alert("here");

            $("#districts").change(function() {
                let district = $("#districts").val();
                //alert(district);
                //alert("changed")
                $.ajax({
                    url: "./fetchcounties.php",
                    method: "post",

                    data: {
                        district: district,
                        action: "fetch"
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $("#county").html('<option disabled selected>select county</option>');
                    },
                    success: function(data) {
                        $("#county").attr('disabled', false);
                        $("#village").attr('disabled', true);
                        $("#subcounty").attr('disabled', true);
                        $("#parish").attr('disabled', true);

                        $.each(data, function(key, value) {
                            $("#county").append('<option value=' + value.countyCode + '>' + value.countyName + '</option>');
                        });
                    }

                })
            })
        })
    </script>

    <script>
        $(document).ready(function() {
            $("#county").change(function() {
                let subcounty = $("#county").val();
                //alert(subcounty);
                $.ajax({
                    url: "fetchsubcounties.php",
                    method: 'post',
                    dataType: "json",
                    data: {
                        action: "fetch",
                        subcounty: subcounty
                    },
                    beforeSend: function() {
                        $("#subcounty").html('<option disabled selected>select sub county</option>');
                    },
                    success: function(data) {
                        $("#subcounty").attr('disabled', false);
                        //console.log(data);
                        //alert(data);
                        //$("#subcounty").append('<option value=' + value.subCountyCode + '>' + value.subCountyName + '</option>');
                        $.each(data, function(key, value) {
                            $("#subcounty").append('<option value=' + value.subCountyCode + '>' + value.subCountyName + '</option>');
                        });
                    }

                })

            })
        })
    </script>

    <script>
        $(document).ready(function() {
            $("#subcounty").change(function() {
                let parish = $("#subcounty").val();
                //alert(parish);
                $.ajax({
                    url: "fetchparishes.php",
                    method: 'post',
                    dataType: "json",
                    data: {
                        action: "fetch",
                        parish: parish
                    },
                    beforeSend: function() {
                        $("#parish").html('<option disabled selected>select parish</option>');
                    },
                    success: function(data) {
                        $("#parish").attr('disabled', false);
                        $.each(data, function(key, value) {
                            $("#parish").append('<option value=' + value.parishCode + '>' + value.parishName + '</option>');
                        });
                    }

                })

            })
        })
    </script>

    <script>
        $(document).ready(function() {
            $("#parish").change(function() {
                let parish = $("#parish").val();


                $.ajax({
                    url: "fetchvillages.php",
                    method: 'post',
                    dataType: "json",
                    data: {
                        action: "fetch",
                        parish: parish
                    },
                    beforeSend: function() {
                        $("#village").html('<option disabled selected>select villages</option>');
                    },
                    success: function(data) {
                        //salert(data);
                        $("#village").attr('disabled', false);
                        $.each(data, function(key, value) {
                            $("#village").append('<option value=' + value.villageCode + '>' + value.villageName + '</option>');
                        });
                    }

                })

            })
        })
    </script>

    <script>
        $('#form').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: "./store.php",
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                // dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $(form).find('span.error-text').text('');
                    $("#save").html("saving...")
                    $("#save").attr("disabled", true);
                },
                success: function(data) {
                    //alert(data);
                    if (data == "success") {
                        //alert("true");
                        location.href = "./index.php"

                    } else {
                        alert("some thing went wrong");
                    }
                    $("#save").html("Save Fuel Station")
                    $("#save").attr("disabled", false);
                }
            });
        });
    </script>
</body>

</html>