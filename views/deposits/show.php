<?php
/**
 * Show Deposit Details
 * @author ThinkxSoftware
 */

if (!isset($_GET['id'])) {
    die("Error: No deposit ID provided. Please provide an ID parameter.");
}

$depositId = $_GET['id'];

include_once '../templates/SecurePageHeader.php';
include_once '../templates/Components.php';

if (!can('view-deposits')) {
    echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";
}

$depositDetails = $dbAccess->selectQuery("SELECT fuelstation.*, deposits.* FROM fuelstation 
    INNER JOIN deposits ON fuelstation.fuelStationId = deposits.fuelStationId 
    WHERE deposits.depositId = $depositId");

if (empty($depositDetails)) {
    die("Error: No deposit found with ID " . $depositId);
}

$deposit = $depositDetails[0];

startContent();

breadCrumbs(['title' => 'Deposit Details', 'sub_title' => 'Details #' . $deposit['depositId'], 'previous' => 'Deposits', 'previous_action' => './index.php']);
?>

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

<?php
endContent();
include_once '../templates/footer.php';
endPage();
