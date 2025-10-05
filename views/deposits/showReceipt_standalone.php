<?php
/**
 * Standalone version of showReceipt.php that works without authentication
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

// Get deposit details
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Receipt - SunShine Financial Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .receipt-container { 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            overflow: hidden;
        }
        .header { 
            background: linear-gradient(135deg, #FF6B35, #E55A2B); 
            color: white; 
            padding: 30px; 
            text-align: center;
        }
        .receipt-image { 
            max-width: 100%; 
            height: auto; 
            border-radius: 10px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .info-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .badge-custom {
            font-size: 14px;
            padding: 8px 12px;
        }
        .btn-custom {
            background: linear-gradient(135deg, #FF6B35, #E55A2B);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="receipt-container">
            <div class="header">
                <h2><i class="fas fa-receipt"></i> Deposit Receipt</h2>
                <p class="mb-0">SunShine Financial Services</p>
                <small>Transaction ID: #<?= $deposit['depositId'] ?></small>
            </div>
            
            <div class="row p-4">
                <div class="col-md-4">
                    <div class="info-card text-center">
                        <h5><i class="fas fa-image text-primary"></i> Receipt Image</h5>
                        <?php if ($deposit['receipt']): ?>
                        <img src="images/<?= $deposit['receipt'] ?>" class="receipt-image" alt="Deposit Receipt">
                        <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No receipt image available
                        </div>
                        <?php endif; ?>
                        <p class="mt-3"><strong><?= $deposit['fuelStationName'] ?></strong></p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="info-card">
                        <h5><i class="fas fa-info-circle text-success"></i> Deposit Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Deposit ID:</strong></td>
                                <td><span class="badge bg-primary badge-custom">#<?= $deposit['depositId'] ?></span></td>
                            </tr>
                            <tr>
                                <td><strong>Amount:</strong></td>
                                <td><span class="badge bg-success badge-custom">shs <?= number_format($deposit['amount'], 0) ?></span></td>
                            </tr>
                            <tr>
                                <td><strong>Deposited By:</strong></td>
                                <td><?= $deposit['depositedBy'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Date:</strong></td>
                                <td><?= date('Y-m-d H:i:s', strtotime($deposit['created_at'])) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Description:</strong></td>
                                <td><?= $deposit['description'] ?: 'No description provided' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="info-card">
                        <h5><i class="fas fa-gas-pump text-info"></i> Fuel Station Details</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Station:</strong></td>
                                <td><?= $deposit['fuelStationName'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Merchant Code:</strong></td>
                                <td><span class="badge bg-info badge-custom"><?= $deposit['merchantCode'] ?></span></td>
                            </tr>
                            <tr>
                                <td><strong>Bank:</strong></td>
                                <td><?= $deposit['bankName'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Branch:</strong></td>
                                <td><?= $deposit['bankBranch'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Account:</strong></td>
                                <td><?= $deposit['accountName'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Account #:</strong></td>
                                <td><code><?= $deposit['accountNumber'] ?></code></td>
                            </tr>
                            <tr>
                                <td><strong>Current Float:</strong></td>
                                <td><span class="badge bg-success badge-custom">shs <?= number_format($deposit['currentAmount'], 0) ?></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="text-center p-4 border-top bg-light">
                <a href="index.php" class="btn btn-custom me-2">
                    <i class="fas fa-arrow-left"></i> Back to Deposits
                </a>
                <button onclick="window.print()" class="btn btn-outline-primary">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
                <a href="float_dashboard.php" class="btn btn-outline-info ms-2">
                    <i class="fas fa-chart-line"></i> Float Dashboard
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
