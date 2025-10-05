<?php
/**
 * Float Management Dashboard
 * Shows real-time fuel station float status
 * @author ThinkxSoftware
 */

// Start session manually
session_start();

// Include database access
require_once("../../utils/dbaccess.php");
$dbAccess = new DbAccess();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Float Dashboard - SunShine Financial Services</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="../../plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../dist/css/sunshine-theme.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <style>
        .small-box {
            border-radius: 10px;
            position: relative;
            display: block;
            margin-bottom: 20px;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }
        .small-box > .inner {
            padding: 10px;
        }
        .small-box > .small-box-footer {
            position: relative;
            text-align: center;
            padding: 3px 0;
            color: #fff;
            color: rgba(255,255,255,0.8);
            display: block;
            z-index: 10;
            background: rgba(0,0,0,0.1);
            text-decoration: none;
        }
        .small-box .icon {
            transition: all .3s linear;
            position: absolute;
            top: -10px;
            right: 10px;
            z-index: 0;
            font-size: 90px;
            color: rgba(0,0,0,0.15);
        }
        .bg-info { background-color: #17a2b8 !important; }
        .bg-success { background-color: #28a745 !important; }
        .bg-warning { background-color: #ffc107 !important; }
        .bg-danger { background-color: #dc3545 !important; }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="../dashboard/" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="index.php" class="nav-link">Deposits</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <img src="../../dist/img/logo.png" alt="SunShine Financial Services" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">SunShine Financial Services</span>
            </a>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="../../dist/img/logo.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">System Administrator</a>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
                        <li class="nav-item">
                            <a href="../dashboard/" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../users/index.php" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../territories/index.php" class="nav-link">
                                <i class="nav-icon fas fa-map"></i>
                                <p>Territories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../fuelstation/index.php" class="nav-link">
                                <i class="nav-icon fas fa-gas-pump"></i>
                                <p>Fuel Stations</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../stage/index.php" class="nav-link">
                                <i class="nav-icon fas fa-road"></i>
                                <p>Stages</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../bodauser/index.php" class="nav-link">
                                <i class="nav-icon fas fa-motorcycle"></i>
                                <p>Boda Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php" class="nav-link">
                                <i class="nav-icon fas fa-wallet"></i>
                                <p>Deposits</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../packages/index.php" class="nav-link">
                                <i class="nav-icon fas fa-box"></i>
                                <p>Packages</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Float Dashboard</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="index.php">Deposits</a></li>
                                <li class="breadcrumb-item active">Float Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    
                    <!-- Summary Cards Row -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?php 
                                    $stations = $dbAccess->select("fuelstation", "", "", "ORDER BY fuelStationId ASC");
                                    echo count($stations); 
                                    ?></h3>
                                    <p>Total Stations</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-gas-pump"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>shs <?php 
                                    $totalFloat = 0;
                                    foreach ($stations as $station) {
                                        $totalFloat += $station['currentAmount'];
                                    }
                                    echo number_format($totalFloat, 0); 
                                    ?></h3>
                                    <p>Total Float</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-wallet"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?php 
                                    $lowFloatStations = 0;
                                    foreach ($stations as $station) {
                                        if ($station['currentAmount'] < 100000) {
                                            $lowFloatStations++;
                                        }
                                    }
                                    echo $lowFloatStations;
                                    ?></h3>
                                    <p>Low Float Stations</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><?php 
                                    $criticalStations = 0;
                                    foreach ($stations as $station) {
                                        if ($station['currentAmount'] < 50000) {
                                            $criticalStations++;
                                        }
                                    }
                                    echo $criticalStations;
                                    ?></h3>
                                    <p>Critical Stations</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Float Status Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Fuel Station Float Status</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="refresh">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                        <a href="create.php" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus"></i> Make Deposit
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="floatTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Station ID</th>
                                                    <th>Station Name</th>
                                                    <th>Merchant Code</th>
                                                    <th>Bank Account</th>
                                                    <th>Total Deposits</th>
                                                    <th>Current Float</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($stations as $station) {
                                                    // Get total deposits for this station
                                                    $deposits = $dbAccess->select("deposits", ["amount"], ["fuelStationId" => $station['fuelStationId']]);
                                                    $totalDeposits = 0;
                                                    foreach ($deposits as $deposit) {
                                                        $totalDeposits += $deposit['amount'];
                                                    }
                                                    
                                                    $currentFloat = $station['currentAmount'];
                                                    $status = $currentFloat > 100000 ? 'success' : ($currentFloat > 50000 ? 'warning' : 'danger');
                                                    $statusText = $currentFloat > 100000 ? 'Good' : ($currentFloat > 50000 ? 'Low' : 'Critical');
                                                    
                                                    echo "<tr>";
                                                    echo "<td><span class='badge badge-primary'>" . $station['fuelStationId'] . "</span></td>";
                                                    echo "<td>" . $station['fuelStationName'] . "</td>";
                                                    echo "<td><span class='badge badge-info'>" . $station['merchantCode'] . "</span></td>";
                                                    echo "<td>" . $station['bankName'] . "<br><small>" . $station['accountNumber'] . "</small></td>";
                                                    echo "<td><span class='badge badge-success'>shs " . number_format($totalDeposits, 0) . "</span></td>";
                                                    echo "<td><span class='badge badge-" . $status . "'>shs " . number_format($currentFloat, 0) . "</span></td>";
                                                    echo "<td><span class='badge badge-" . $status . "'>" . $statusText . "</span></td>";
                                                    echo "<td>";
                                                    echo "<a href='create.php' class='btn btn-primary btn-sm me-1'>Add Deposit</a>";
                                                    echo "<a href='float_details.php?station=" . $station['fuelStationId'] . "' class='btn btn-info btn-sm'>Details</a>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2025 <a href="#">SunShine Financial Services</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.js"></script>
    
    <script>
    $(document).ready(function() {
        $('#floatTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "order": [[5, "desc"]], // Sort by current float descending
            "pageLength": 25,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "language": {
                "search": "Search stations:",
                "lengthMenu": "Show _MENU_ stations per page",
                "info": "Showing _START_ to _END_ of _TOTAL_ stations",
                "infoEmpty": "No stations found",
                "infoFiltered": "(filtered from _MAX_ total stations)"
            }
        }).buttons().container().appendTo('#floatTable_wrapper .col-md-6:eq(0)');
        
        // Auto-refresh every 30 seconds
        setInterval(function() {
            location.reload();
        }, 30000);
    });
    </script>
</body>
</html>