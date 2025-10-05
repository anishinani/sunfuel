<?php
/**
 * Debug version of showReceipt.php
 * @author ThinkxSoftware
 */
include_once '../templates/SecurePageHeader.php';
include_once '../templates/Components.php';

// Temporarily bypass authentication for debugging
// if(!can('view-deposit-receipts')) header('Location:../Errors/unAuthorized.php'); 

startContent();

if (!isset($_GET['id'])) {
    echo "Error: No ID parameter provided";
    exit();
}

$depositId = $_GET['id'];
echo "Debug: Deposit ID = " . $depositId . "<br>";

$depositDetails = $dbAccess->selectQuery("SELECT fuelstation.*, deposits.* FROM fuelstation 
    INNER JOIN deposits ON fuelstation.fuelStationId = deposits.fuelStationId 
    WHERE deposits.depositId = $depositId");

echo "Debug: Query executed. Results count: " . count($depositDetails) . "<br>";

if (empty($depositDetails)) {
    echo "Error: No deposit found with ID " . $depositId;
    exit();
}

$deposit = $depositDetails[0];
echo "Debug: Deposit found for station: " . $deposit['fuelStationName'] . "<br>";

breadCrumbs(['title' => 'Deposit Receipt', 'sub_title' => 'Deposit Receipt', 'previous' => 'Deposits', 'previous_action' => './index']);

?>

<div class="row">
    <div class="col-12">
        <div class="container rounded bg-white mt-5 mb-5">
            <div class="row">
                <div class="col-md-3 border-right">
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        <?php if ($deposit['receipt']): ?>
                        <img class="mt-1" width="250px" src="<?= "images/" . $deposit['receipt']; ?>">
                        <?php else: ?>
                        <div class="alert alert-info">No receipt image available</div>
                        <?php endif; ?>
                        <span class="font-weight-bold">Receipt Photo</span>
                        <span class="text-black-50"><?= $deposit['fuelStationName'] ?></span>
                    </div>
                </div>
                <div class="col-md-5 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right"><?= $deposit['fuelStationName'] ?> Details</h4>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="labels">Name</label>
                                <input type="text" disabled class="form-control" value="<?= $deposit['fuelStationName'] ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="labels">Deposited Amount</label>
                                <input type="text" disabled class="form-control" value="shs <?= number_format($deposit['amount'], 0) ?>">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="labels">Deposited By</label>
                                <input type="text" class="form-control" disabled value="<?= $deposit['depositedBy'] ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Deposited On</label>
                                <input type="text" class="form-control" disabled value="<?= $deposit['created_at'] ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Total Amount Since Initial Deposit</label>
                                <input type="text" class="form-control" disabled value="shs <?= number_format($deposit['totalAmount'], 0) ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="labels">Current Amount</label>
                                <input type="text" class="form-control" disabled value="shs <?= number_format($deposit['currentAmount'], 0) ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 py-5">
                        <div class="col-md-12">
                            <label class="labels">Bank Name</label>
                            <input type="text" class="form-control" disabled value="<?= $deposit['bankName'] ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Bank Branch</label>
                            <input type="text" class="form-control" disabled value="<?= $deposit['bankBranch'] ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Account Name</label>
                            <input type="text" class="form-control" disabled value="<?= $deposit['accountName'] ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="labels">Account Number</label>
                            <input type="text" class="form-control" disabled value="<?= $deposit['accountNumber'] ?>">
                        </div>
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
?>
