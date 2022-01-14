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
    <!-- Theme style -->
    <link rel="stylesheet" href=" /creditpluswebapp/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href=" /creditpluswebapp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href=" /creditpluswebapp/plugins/daterangepaicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href=" /creditpluswebapp/plugins/select2/css/select2.css">

    <link rel="stylesheet" href=" /creditpluswebapp/plugins/select2-bootstrap4-theme/select2-bootstrap4.css">
   



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

        .form-control {
            text-transform: uppercase !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php
        include_once("../navbar/navbar.php");
        include_once("../sidebar.php");
        include_once("../../utils/pageFunctions.php");


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
                                <li class="breadcrumb-item active">Territory</li>
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
                                    <p class="login-box-msg">Create New Territory</p>
                                <form method="POST" id="form">
                                    <div class="form-group">
                                        <label for="territoryName"> Territory Name</label>
                                        <input type="text" class="form-control" placeholder="Enter Territory Name" name="territoryName">
                                        <span id="territoryName_error" class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="territoryManager"> Territory Manager</label>
                                        <select class="form-control form-select" name="territoryManager">
                                            <option value="">Choose</option>
                                            <?= getUsers(); ?>
                                        </select>
                                        <span id="territoryManager_error" class="text-danger"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="territoryDistricts"> Territory Districts</label>
                                        <select id="select-districts" class="form-control form-select" name="territoryDistricts[]" multiple>
                                            <?= getDistricts(); ?>
                                        </select>
                                        <span id="territoryManager_error" class="text-danger"></span>
                                    </div>    
                                    <!-- /.col -->
                                    <div class="col-12 d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary btn-block py-1" name="addTerritory" id="save">Save Territory</button>
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
   
    <!-- jQuery Knob Chart -->
    <script src=" /creditpluswebapp/plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src=" /creditpluswebapp/plugins/moment/moment.min.js"></script>
    <script src=" /creditpluswebapp/plugins/daterangepicker/daterangepicker.js"></script>
    <script src=" /creditpluswebapp/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    
    <script src=" /creditpluswebapp/plugins/select2/js/select2.min.js"></script>

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
            $("#select-districts").select2();
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
                    $("#save").html("Save Territory")
                    $("#save").attr("disabled", false);
                }
            });
        });
    </script>
</body>



</html>