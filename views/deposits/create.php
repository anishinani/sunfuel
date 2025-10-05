<?php
/**
 * Create Deposit Page
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
    <title>Create Deposit - SunShine Financial Services</title>
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
    <style>
        .form-control:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }
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
                            <h1 class="m-0">Create New Deposit</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard/">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="index.php">Deposits</a></li>
                                <li class="breadcrumb-item active">Create</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-plus-circle"></i> Deposit Information
                                    </h3>
                                    <div class="card-tools">
                                        <a href="index.php" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-arrow-left"></i> Back to Deposits
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form id="depositForm" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="merchantCode" class="form-label">Merchant Code</label>
                                                    <input type="text" class="form-control" id="merchantCode" name="merchantCode" 
                                                           placeholder="Enter merchant code" required>
                                                    <small class="form-text text-muted">Enter the fuel station merchant code</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="station" class="form-label">Fuel Station</label>
                                                    <input type="text" class="form-control" id="station" name="station" 
                                                           placeholder="Station name will appear here" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bankname" class="form-label">Bank Name</label>
                                                    <input type="text" class="form-control" id="bankname" name="bankname" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bankbranch" class="form-label">Bank Branch</label>
                                                    <input type="text" class="form-control" id="bankbranch" name="bankbranch" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="accountname" class="form-label">Account Name</label>
                                                    <input type="text" class="form-control" id="accountname" name="accountname" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="accountnumber" class="form-label">Account Number</label>
                                                    <input type="text" class="form-control" id="accountnumber" name="accountnumber" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Current Float Display -->
                                        <div class="form-group" id="currentFloatSection" style="display: none;">
                                            <label class="form-label">Current Float</label>
                                            <div class="alert alert-info" id="currentFloat">
                                                <strong>Current Float:</strong> <span id="floatAmount">shs 0</span>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="amount" class="form-label">Deposit Amount</label>
                                                    <input type="number" class="form-control" id="amount" name="amount" 
                                                           placeholder="Enter amount without commas" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="depositedBy" class="form-label">Deposited By</label>
                                                    <input type="text" class="form-control" id="depositedBy" name="depositedBy" 
                                                           placeholder="Enter depositor name" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3" 
                                                      placeholder="Enter deposit description (optional)"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="receipt" class="form-label">Receipt Image</label>
                                            <input type="file" class="form-control" id="receipt" name="receipt" accept="image/*">
                                            <small class="form-text text-muted">Upload receipt image (JPG, PNG, GIF)</small>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-save"></i> Create Deposit
                                            </button>
                                        </div>
                                    </form>
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
    
    <script>
    $(document).ready(function() {
        // Merchant code lookup
        $('#merchantCode').on('blur', function() {
            var merchantCode = $(this).val();
            if (merchantCode) {
                $.ajax({
                    url: './fetchstation.php',
                    method: 'POST',
                    data: { action: 'fetch', merchantCode: merchantCode },
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.length > 0) {
                            var station = response[0];
                            $('#station').val(station.fuelStationName);
                            $('#bankname').val(station.bankName);
                            $('#bankbranch').val(station.bankBranch);
                            $('#accountname').val(station.accountName);
                            $('#accountnumber').val(station.accountNumber);
                            $('#floatAmount').text('shs ' + new Intl.NumberFormat().format(station.currentAmount));
                            $('#currentFloatSection').show();
                        } else {
                            alert('Merchant code not found');
                            clearFields();
                        }
                    },
                    error: function() {
                        alert('Error fetching station details');
                        clearFields();
                    }
                });
            }
        });

        // Form submission
        $('#depositForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            formData.append('addDeposit', '1');
            
            $.ajax({
                url: './store.php',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.message === 'success') {
                        alert('Deposit created successfully!');
                        window.location.href = 'index.php';
                    } else {
                        alert('Error: ' + response.data);
                    }
                },
                error: function() {
                    alert('Error creating deposit');
                }
            });
        });

        function clearFields() {
            $('#station').val('');
            $('#bankname').val('');
            $('#bankbranch').val('');
            $('#accountname').val('');
            $('#accountnumber').val('');
            $('#currentFloatSection').hide();
        }
    });
    </script>
</body>
</html>