<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Deposit</title>

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

        .none {
            display: none;
        }

        #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.75) url(/creditpluswebapp/dist/img/loader.gif) no-repeat center center;
            z-index: 10000;
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
        include_once("../../utils/dbaccess.php");
        include("../sidebar.php");
        $dbAccess =  new DbAccess();
        $results  =  $dbAccess->select("fuelstation", ["fuelStationId", "fuelStationName"]);
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
                                <li class="breadcrumb-item active">Deposits</li>
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


                        <!--errors-->
                        <!--errors-->
                    <?php } ?>
                    <div id="noMerchantCode">

                    </div>
                    <div class="row">
                        <!--form add user -->
                        <div class="register-box m-auto col-md-8">
                            <div class="card card-outline card-primary">

                                <div class="card-body">
                                    <p class="login-box-msg">Add New Deposit</p>

                                    <div class="form-group mb-3">
                                        <label for="">Enter Merchant Code</label>
                                        <input type="text" name="amount" id="merchant" required class="form-control" placeholder="enter merchant code" />

                                    </div>

                                    <form method="POST" id="form" enctype="multipart/form-data">



                                        <!--bankname-->
                                        <div class="form-group mb-3">
                                            <label for="">Bank Name</label>
                                            <input type="text" name="bankname" id="bankname" disabled required class="form-control" placeholder="enter bank anmes" />

                                        </div>
                                        <!--banknames-->
                                        <!--Bank Branch-->
                                        <div class="form-group mb-3">
                                            <label for="">Bank Branch</label>
                                            <input type="text" name="bankbranch" disabled id="bankbranch" required class="form-control" placeholder="enter station Bank Branch" />
                                        </div>
                                        <!--Bank Branch-->
                                        <!--Account Name-->
                                        <div class="form-group mb-3">
                                            <label for="">Account Name</label>
                                            <input type="text" name="accountname" id="accountname" disabled required class="form-control" placeholder="enter station Account Name" />
                                        </div>
                                        <!--Account Name-->
                                        <!--Account Number-->
                                        <div class="form-group mb-3">
                                            <label for="">Account Number</label>
                                            <input type="text" name="accountnumber" id="accountnumber" disabled required class="form-control" placeholder="enter station Account Number" />
                                        </div>
                                        <!--Account Number-->
                                        <!--fuel station-->
                                        <div class="form-group mb-3">
                                            <div class="form-group">
                                                <label for="my-select">Fuel Station</label>
                                                <input type="text" name="stationname" id="station" disabled required class="form-control" placeholder="station name" />

                                            </div>
                                            <input class="form-control" type="hidden" name="stationId" id="stationId">

                                        </div>
                                        <!--fuel station-->

                                        <div class="form-group mb-3">
                                            <label for="">Amount</label>
                                            <input type="text" name="amount" id="amount" disabled required class="form-control" placeholder="enter amount" />

                                        </div>

                                        <!--person-->
                                        <div class="form-group mb-3">
                                            <label for="">Deposited By</label>
                                            <input type="text" name="name" id="depositedBy" disabled required class="form-control" placeholder="enter names" />

                                        </div>
                                        <!--person-->


                                        <!--Receipt-->
                                        <div class="form-group mb-3">
                                            <label for=""> Upload Receipt</label>
                                            <input type="file" name="receipt" id="file" disabled required class="form-control" accept="image/*" />
                                        </div>
                                        <!--Receipt-->




                                        <!-- /.col -->
                                        <div class="col-12">

                                            <button type="submit" class="style_button" name="addDeposit" id="save">Confirm Deposit</button>
                                            <!-- <img src="/creditpluswebapp/dist/img/loader.gif" width="80px" height="80px" /> -->
                                        </div>
                                        <!-- /.col -->
                                </div>

                                </form>
                                <div class="co1-12"></div>
                                <!-- <button id="button">Register Agent</button> -->

                            </div>



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
    <script src="text/javascript">
        const spinner = $('#loader');
        $(function() {
            $("#button").click(function() {
                //spinner.show();
                alert("clicked");
            })
        })
    </script>

    <script>
        $(document).ready(() => {
            $('#merchant').keyup(() => {
                //alert("here");
                var merchantCode = $("#merchant").val();
                //alert(merchantCode)
                //fetch stations

                if (merchantCode == "") {
                    $("#noMerchantCode").addClass("none");
                }
                $.ajax({
                    url: "fetchstation.php",
                    method: 'post',
                    dataType: "json",
                    data: {
                        action: "fetch",
                        merchantCode: merchantCode
                    },
                    beforeSend: function() {

                        //$("#fuelStationId").html('<option disabled selected>select fuel station</option>');

                    },
                    success: function(data) {
                        // $("#fuelStationId").attr('disabled', false);
                        $.each(data, function(key, value) {
                            if (value == "invalidMerchantCode") {
                                $("#noMerchantCode").removeClass("none");
                                //alert("here");
                                $("#noMerchantCode").html('<div class="alert alert-danger"><strong > Whoops! </strong> Invalid Merchant Code.<br><br></div>');
                                $("#bankname").val("");
                                $("#bankbranch").val("");
                                $("#accountname").val("");
                                $("#accountnumber").val("");
                                $("#stationId").val("");
                                $("#station").val("");
                                $("amount").attr("disabled", true);
                                $("#depositedBy").attr("disabled", true);
                                $("#file").attr("disabled", true)
                            } else {
                                //alert("here");
                                $("#noMerchantCode").addClass("none");
                                console.log(value);
                                //$("#fuelStationId").append('<option value=' + value.fuelStationId + '>' + value.fuelStationName + '</option>');
                                $("#bankname").val(value.bankName);
                                $("#bankbranch").val(value.bankName);
                                $("#accountname").val(value.bankBranch);
                                $("#accountnumber").val(value.AccName);
                                $("#stationId").val(value.fuelStationId);
                                $("#station").val(value.fuelStationName);
                                $("#amount").attr("disabled", false);
                                $("#depositedBy").attr("disabled", false);
                                $("#file").attr("disabled", false)

                            }
                        });

                    }

                })
                //fetch stations
            });
        });
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
                    // $(form).find('span.error-text').text('');
                    $("#save").html("saving...")
                    $("#save").attr("disabled", true);
                },
                success: function(data) {
                    //alert(data);
                    if (data == "success") {
                        //alert("true");
                        location.href = "./index.php"

                    } else {
                        alert("some thing went wrong!! please try again");
                    }
                    $("#save").html("Confirm Payment")
                    $("#save").attr("disabled", false);
                }
            });
        });
    </script>
</body>

</html>