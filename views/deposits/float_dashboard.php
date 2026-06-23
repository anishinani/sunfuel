<?php
/**
 * Float Management Dashboard
 * Shows real-time fuel station float status
 * @author ThinkxSoftware
 */
include_once '../templates/SecurePageHeader.php';
include_once '../templates/Components.php';

if (!can('view-deposits')) {
    echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";
}

$stations = $dbAccess->select("fuelstation", "", "", "ORDER BY fuelStationId ASC");

startContent();

breadCrumbs(['title' => 'Float Dashboard', 'sub_title' => 'Float Dashboard', 'previous' => 'Deposits', 'previous_action' => './index.php']);
?>

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

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= count($stations) ?></h3>
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
                                $deposits = $dbAccess->select("deposits", ["amount"], ["fuelStationId" => $station['fuelStationId']]);
                                $totalDeposits = 0;
                                foreach ($deposits as $deposit) {
                                    $totalDeposits += $deposit['amount'];
                                }

                                $currentFloat = $station['currentAmount'];
                                $status = $currentFloat > 100000 ? 'success' : ($currentFloat > 50000 ? 'warning' : 'danger');
                                $statusText = $currentFloat > 100000 ? 'Good' : ($currentFloat > 50000 ? 'Low' : 'Critical');
                            ?>
                            <tr>
                                <td><span class="badge badge-primary"><?= $station['fuelStationId'] ?></span></td>
                                <td><?= htmlspecialchars($station['fuelStationName']) ?></td>
                                <td><span class="badge badge-info"><?= htmlspecialchars($station['merchantCode']) ?></span></td>
                                <td><?= htmlspecialchars($station['bankName']) ?><br><small><?= htmlspecialchars($station['accountNumber']) ?></small></td>
                                <td><span class="badge badge-success">shs <?= number_format($totalDeposits, 0) ?></span></td>
                                <td><span class="badge badge-<?= $status ?>">shs <?= number_format($currentFloat, 0) ?></span></td>
                                <td><span class="badge badge-<?= $status ?>"><?= $statusText ?></span></td>
                                <td>
                                    <a href="create.php" class="btn btn-primary btn-sm me-1">Add Deposit</a>
                                    <a href="float_details.php?station=<?= $station['fuelStationId'] ?>" class="btn btn-info btn-sm">Details</a>
                                </td>
                            </tr>
                            <?php } ?>
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
?>

<script>
$(document).ready(function() {
    $('#floatTable').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[5, 'desc']],
        pageLength: 25,
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis'],
        language: {
            search: 'Search stations:',
            lengthMenu: 'Show _MENU_ stations per page',
            info: 'Showing _START_ to _END_ of _TOTAL_ stations',
            infoEmpty: 'No stations found',
            infoFiltered: '(filtered from _MAX_ total stations)'
        }
    }).buttons().container().appendTo('#floatTable_wrapper .col-md-6:eq(0)');

    setInterval(function() {
        location.reload();
    }, 30000);
});
</script>

<?php endPage(); ?>
