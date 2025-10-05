<?php
/**
 * Show Deposit Details
 * @author ThinkxSoftware
 */

// Start session manually
session_start();

// Include database access
require_once("../../utils/dbaccess.php");
$dbAccess = new DbAccess();

// Check if ID is provided
if (!isset($_GET['id'])) {
    die("Error: No deposit ID provided. Please provide an ID parameter.");
}

$depositId = $_GET['id'];

// Get deposit details with fuel station info
$depositDetails = $dbAccess->selectQuery("SELECT fuelstation.*, deposits.* FROM fuelstation 
    INNER JOIN deposits ON fuelstation.fuelStationId = deposits.fuelStationId 
    WHERE deposits.depositId = $depositId");

if (empty($depositDetails)) {
    die("Error: No deposit found with ID " . $depositId);
}

$deposit = $depositDetails[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Deposit Details - SunShine Financial Services</title>
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
                            <a href="index.php" class="nav-link active">
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
                            <h1 class="m-0">Deposit Details</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="index.php">Deposits</a></li>
                                <li class="breadcrumb-item active">Details #<?= $deposit['depositId'] ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-receipt"></i> Deposit Information
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Deposit ID:</strong></td>
                                                    <td><span class="badge badge-primary">#<?= $deposit['depositId'] ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Amount:</strong></td>
                                                    <td><span class="badge badge-success" style="font-size: 16px;">
                                                        shs <?= number_format($deposit['amount'], 0) ?>
                                                    </span></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Deposited By:</strong></td>
                                                    <td><?= $deposit['depositedBy'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Deposit Date:</strong></td>
                                                    <td><?= date('Y-m-d H:i:s', strtotime($deposit['created_at'])) ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Description:</strong></td>
                                                    <td><?= $deposit['description'] ?: 'No description provided' ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Receipt:</strong></td>
                                                    <td>
                                                        <?php if ($deposit['receipt']): ?>
                                                        <a href="images/<?= $deposit['receipt'] ?>" target="_blank" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i> View Receipt
                                                        </a>
                                                        <?php else: ?>
                                                        <span class="text-muted">No receipt uploaded</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-gas-pump"></i> Fuel Station Details
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Station Name:</strong></td>
                                            <td><?= $deposit['fuelStationName'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Merchant Code:</strong></td>
                                            <td><span class="badge badge-info"><?= $deposit['merchantCode'] ?></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Location:</strong></td>
                                            <td><?= $deposit['fuelStationLocation'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Bank Details:</strong></td>
                                            <td>
                                                <?= $deposit['bankName'] ?><br>
                                                <?= $deposit['bankBranch'] ?><br>
                                                <strong>Account:</strong> <?= $deposit['accountName'] ?><br>
                                                <strong>Number:</strong> <?= $deposit['accountNumber'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Current Float:</strong></td>
                                            <td><span class="badge badge-success">
                                                shs <?= number_format($deposit['currentAmount'], 0) ?>
                                            </span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-cogs"></i> Actions
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <a href="showReceipt.php?id=<?= $deposit['depositId'] ?>" class="btn btn-primary btn-block mb-2">
                                        <i class="fas fa-receipt"></i> View Receipt
                                    </a>
                                    <a href="float_details.php?station=<?= $deposit['fuelStationId'] ?>" class="btn btn-info btn-block mb-2">
                                        <i class="fas fa-chart-line"></i> Station Float Details
                                    </a>
                                    <a href="index.php" class="btn btn-secondary btn-block">
                                        <i class="fas fa-arrow-left"></i> Back to Deposits
                                    </a>
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
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.js"></script>
</body>
</html>