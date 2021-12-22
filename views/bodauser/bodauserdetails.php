<?php
session_start();
$_SESSION['bool'] =  true;
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Boda Users</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->

    <link rel="stylesheet" href="/creditpluswebapp/plugins/fontawesome-free/css/all.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/creditpluswebapp/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/creditpluswebapp/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/creditpluswebapp/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/creditpluswebapp/dist/css/adminlte.min.css">
    <style>
        .style_button {
            background: #657836 !important;
            color: #fff;
            width: 100% !important;
            border: none !important;
            height: 40px !important;
            cursor: pointer;
            border-radius: 10px;
        }

        .farmer__filter {
            display: flex !important;
            align-items: center !important;
            justify-content: space-evenly !important;
        }

        .platform {
            display: none !important;
        }

        .content-wrapper {
            position: relative !important;
        }

        .image__remove {
            position: absolute !important;
            right: 30px !important;
            top: 10px !important;
            cursor: pointer;
        }

        #removeAlert {
            margin-top: 10px !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <?php
        include("../../utils/dbaccess.php");
        include("../navbar/navbar.php");
        include("../sidebar.php");

        $dbAccess =  new DbAccess();
        if (isset($_GET['bodadetails'])) {
            $bodaId =  $_GET['bodadetails'];
            //die($bodaId);

            $bodaDetails =  $dbAccess->select("bodauser", "", ["bodaUserId" => $bodaId]);
            //var_dump($bodaDetails);
        } else {
            //die("not sent");
        }

        ?>
        <!-- Main Sidebar Container -->


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!--any wrong info-->

            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert alert-success m-4" id="removeAlert">
                    <p><?= $_SESSION['success']; ?></p>
                    <img src="../../dist/img/remove.png" class="image__remove" alt="cross image" height="20px" width="20px">

                </div>

            <?php }
            unset($_SESSION['success']);

            ?>

            <!--any wrong info-->

            <!-- /.card -->
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Boda Users</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../bodauser/index.php">Home</a></li>
                                <li class="breadcrumb-item active">Boda Users</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">

                            <div class="container rounded bg-white mt-5 mb-5">
                                <div class="row">
                                    <div class="col-md-3 border-right">
                                        <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                                            <img class=" mt-1" width="250px" src="<?= "images/" . $bodaDetails[0]['bodaUserFrontPhoto']; ?>"><span class=" font-weight-bold">Front ID Photo</span>
                                            <span class="text-black-50"><?= $bodaDetails[0]['bodaUserName'] ?></span><span> </span>
                                        </div>
                                        <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                                            <img class=" mt-1" width="250px" src="<?= "images/" . $bodaDetails[0]['bodaUserBackPhoto']; ?>">
                                            <span class=" font-weight-bold">Back IDPhoto</span>
                                            <span class="text-black-50"><?= $bodaDetails[0]['bodaUserName'] ?></span><span> </span>
                                        </div>
                                    </div>
                                    <div class="col-md-5 border-right">
                                        <div class="p-3 py-5">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h4 class="text-right"> <?= $bodaDetails[0]['bodaUserName'] ?> Details</h4>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6"><label class="labels">Name</label><input type="text" disabled class="form-control" placeholder="first name" value="<?= $bodaDetails[0]['bodaUserName'] ?>"></div>
                                                <div class="col-md-6"><label class="labels">NIN Number</label><input type="text" disabled class="form-control" placeholder="first name" value="<?= $bodaDetails[0]['bodaUserNIN'] ?>"></div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12"><label class="labels">Mobile Number</label>
                                                    <input type="text" class="form-control" disabled placeholder="enter phone number" value="<?= $bodaDetails[0]['bodaUserPhoneNumber'] ?>">
                                                </div>
                                                <div class="col-md-12"><label class="labels">Other Number</label>
                                                    <input type="text" class="form-control" disabled value="<?= $bodaDetails[0]['alternativePhotoNumber'] ?>">
                                                </div>
                                                <div class="col-md-12"><label class="labels">Boda Number</label>
                                                    <input type="text" class="form-control" disabled value="<?= $bodaDetails[0]['bodaUserBodaNumber'] ?>">
                                                </div>

                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6"><label class="labels">Role</label><input type="text" class="form-control" disabled value="<?= $bodaDetails[0]['bodaUserRole'] ?>"></div>
                                                <div class="col-md-6"><label class="labels">Stage</label><input type="text" class="form-control" value="<?= $dbAccess->select("stage", ['stageName'], ['stageId' => $bodaDetails[0]['stageId']])[0]['stageName']
                                                                                                                                                        ?>" disabled></div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 py-5">

                                            <div class="col-md-12"><label class="labels">Fuel Station</label>
                                                <input type="text" class="form-control" disabled value="<?= $dbAccess->select("fuelstation", ['fuelStationName'], ['fuelStationId' => $bodaDetails[0]['fuelStationId']])[0]['fuelStationName'] ?>">
                                            </div> <br>
                                            <div class="col-md-12"><label class="labels">Boda User Status</label>
                                                <?php
                                                if ($bodaDetails[0]["bodaUserStatus"] == 0) {
                                                ?>
                                                    <form action="activateBoda.php" method="post">
                                                        <input type="hidden" name="id" value="<?= $bodaDetails[0]["bodaUserId"]; ?>" />
                                                        <input type="hidden" name="stageId" value="<?= $bodaDetails[0]["stageId"] ?>" />
                                                        <button type="submit" name="activate" class="btn btn-info btn-sm editbtn">Activate</button>
                                                    </form>
                                                <?php } else { ?>
                                                    <form action="deactivateBoda.php" method="post">
                                                        <input type="hidden" name="id" value="<?= $bodaDetails[0]["bodaUserId"]; ?>" />
                                                        <button type="submit" name="deactivate" class="btn btn-danger btn-sm editbtn">DeActivate</button>
                                                    </form>

                                                <?php } ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
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
    <!-- Bootstrap 4 -->

    <script src="/creditpluswebapp/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="/creditpluswebapp/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="/creditpluswebapp/plugins/jszip/jszip.min.js"></script>
    <script src="/creditpluswebapp/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="/creditpluswebapp/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="/creditpluswebapp/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/creditpluswebapp/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="/creditpluswebapp/dist/js/demo.js"></script>
    <!-- Page specific script -->

    <!--hide alert--->
    <script type="text/javascript">
        $(function() {
            $('.image__remove').click(function() {
                //alert('clicked')
                // $("#content-wrap").addClass('platform');
                $("#removeAlert").addClass('platform');

            })
        })
    </script>
    <!--hide alert-->
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "fnCreatedRow": function(nRow, aData, iDataIndex) {
                    $(nRow).attr('id', aData[0]);
                },
                'serverSide': 'true',
                'processing': 'true',
                'paging': 'true',
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                'order': [],
                'ajax': {
                    'url': './serverside.php',
                    'type': 'post',
                },
                "columnDefs": [{
                    'target': [5],
                    'orderable': false,
                }]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>


</body>

</html>