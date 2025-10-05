<?php
/**
 * Float Details Page
 * Shows detailed float history for a specific fuel station
 * @author ThinkxSoftware
 */
include_once '../templates/SecurePageHeader.php';
include_once '../templates/Components.php';

if (!can('view-deposits')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";

$stationId = $_GET['station'] ?? 1;
$dbAccess = new DbAccess();

// Get station details
$station = $dbAccess->select("fuelstation", "", ["fuelStationId" => $stationId]);
if (empty($station)) {
    header("Location: float_dashboard.php");
    exit();
}
$station = $station[0];

// Get deposits for this station
$deposits = $dbAccess->select("deposits", "", ["fuelStationId" => $stationId], "ORDER BY created_at DESC");

// Get fuel consumption for this station
$consumption = $dbAccess->select("fuel_consumption", "", ["fuelStationId" => $stationId], "ORDER BY consumptionDate DESC");

startContent();

breadCrumbs(['title' => 'Float Details', 'sub_title' => $station['fuelStationName'], 'previous' => 'Float Dashboard', 'previous_action' => './float_dashboard.php']);

?>

<div class="row">
    <!-- Station Info Card -->
    <div class="col-md-4">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Station Information</h3>
            </div>
            <div class="card-body">
                <strong>Station Name:</strong><br>
                <?php echo $station['fuelStationName']; ?><br><br>
                
                <strong>Merchant Code:</strong><br>
                <span class="badge badge-info"><?php echo $station['merchantCode']; ?></span><br><br>
                
                <strong>Bank Details:</strong><br>
                <?php echo $station['bankName']; ?><br>
                <?php echo $station['bankBranch']; ?><br>
                <strong>Account:</strong> <?php echo $station['accountName']; ?><br>
                <strong>Number:</strong> <?php echo $station['accountNumber']; ?><br><br>
                
                <strong>Current Float:</strong><br>
                <span class="badge badge-success" style="font-size: 16px;">
                    shs <?php echo number_format($station['currentAmount'], 0); ?>
                </span>
            </div>
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="col-md-8">
        <div class="row">
            <div class="col-md-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>shs <?php 
                        $totalDeposits = 0;
                        foreach ($deposits as $deposit) {
                            $totalDeposits += $deposit['amount'];
                        }
                        echo number_format($totalDeposits, 0); 
                        ?></h3>
                        <p>Total Deposits</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>shs <?php 
                        $totalConsumed = 0;
                        foreach ($consumption as $consume) {
                            $totalConsumed += $consume['amount'];
                        }
                        echo number_format($totalConsumed, 0); 
                        ?></h3>
                        <p>Total Consumed</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-minus-circle"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <?php if (can('create-deposits')): ?>
                <a href="create.php" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add Deposit
                </a>
                <?php endif; ?>
                <a href="float_dashboard.php" class="btn btn-info">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Deposits History -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Deposits History</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Deposited By</th>
                                <th>Description</th>
                                <th>Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($deposits)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No deposits found</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($deposits as $deposit): ?>
                            <tr>
                                <td><?php echo date('Y-m-d H:i', strtotime($deposit['created_at'])); ?></td>
                                <td><span class="badge badge-success">shs <?php echo number_format($deposit['amount'], 0); ?></span></td>
                                <td><?php echo $deposit['depositedBy']; ?></td>
                                <td><?php echo $deposit['description']; ?></td>
                                <td>
                                    <?php if ($deposit['receipt']): ?>
                                    <a href="images/<?php echo $deposit['receipt']; ?>" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <?php else: ?>
                                    <span class="text-muted">No receipt</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fuel Consumption History -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Fuel Consumption History</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Boda User</th>
                                <th>Amount</th>
                                <th>Fuel Type</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($consumption)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No fuel consumption recorded</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($consumption as $consume): ?>
                            <tr>
                                <td><?php echo date('Y-m-d H:i', strtotime($consume['consumptionDate'])); ?></td>
                                <td>
                                    <?php 
                                    if ($consume['bodaUserId']) {
                                        $bodaUser = $dbAccess->select("bodauser", ["bodaUserName"], ["bodaUserId" => $consume['bodaUserId']]);
                                        echo $bodaUser ? $bodaUser[0]['bodaUserName'] : 'Unknown User';
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                                <td><span class="badge badge-danger">shs <?php echo number_format($consume['amount'], 0); ?></span></td>
                                <td><?php echo $consume['fuelType']; ?></td>
                                <td><?php echo $consume['description']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
