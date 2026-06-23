<?php
/**
 * Deposit Receipt Page
 * @author ThinkxSoftware
 */

if (!isset($_GET['id'])) {
    die("Error: No deposit ID provided. Please provide an ID parameter.");
}

$depositId = $_GET['id'];

include_once '../templates/SecurePageHeader.php';
include_once '../templates/Components.php';

if (!can('view-deposit-receipts')) {
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

breadCrumbs(['title' => 'Deposit Receipt', 'sub_title' => 'Receipt #' . $deposit['depositId'], 'previous' => 'Deposits', 'previous_action' => './index.php']);
?>

<style>
    .receipt-image {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-receipt"></i> Receipt Details
                </h3>
                <div class="card-tools">
                    <button onclick="window.print()" class="btn btn-primary btn-sm">
                        <i class="fas fa-print"></i> Print Receipt
                    </button>
                    <a href="index.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Deposits
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
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
                        <h5><i class="fas fa-info-circle text-success"></i> Deposit Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Deposit ID:</strong></td>
                                <td><span class="badge badge-primary">#<?= $deposit['depositId'] ?></span></td>
                            </tr>
                            <tr>
                                <td><strong>Amount:</strong></td>
                                <td><span class="badge badge-success">shs <?= number_format($deposit['amount'], 0) ?></span></td>
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

                    <div class="col-md-4">
                        <h5><i class="fas fa-gas-pump text-info"></i> Fuel Station Details</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Station:</strong></td>
                                <td><?= $deposit['fuelStationName'] ?></td>
                            </tr>
                            <tr>
                                <td><strong>Merchant Code:</strong></td>
                                <td><span class="badge badge-info"><?= $deposit['merchantCode'] ?></span></td>
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
                                <td><span class="badge badge-success">shs <?= number_format($deposit['currentAmount'], 0) ?></span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
endContent();
include_once '../templates/footer.php';
endPage();
