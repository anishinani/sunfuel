<?php

/**
 * Deposits Index Page
 * @author ThinkxSoftware
 */
include_once '../templates/SecurePageHeader.php';
include_once '../templates/Components.php';

if (!can('view-deposits')) {
    echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";
}

startContent();

breadCrumbs(['title' => 'Deposits Management', 'sub_title' => 'Deposits', 'previous' => 'Dashboard', 'previous_action' => '../dashboard/']);

$deposits = $dbAccess->selectQuery("SELECT * FROM deposits ORDER BY created_at DESC");
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Deposits</h3>
                <div class="card-tools">
                    <?php if (can('create-deposits')): ?>
                        <a href="create.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Make Deposit
                        </a>
                    <?php endif; ?>
                    <a href="float_dashboard.php" class="btn btn-info btn-sm">
                        <i class="fas fa-chart-line"></i> Float Dashboard
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="depositsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fuel Station</th>
                                <th>Amount</th>
                                <th>Deposited By</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($deposits as $deposit):
                                $station = $dbAccess->select("fuelstation", ["fuelStationName"], ["fuelStationId" => $deposit['fuelStationId']]);
                                $stationName = $station ? $station[0]['fuelStationName'] : 'Unknown Station';
                            ?>
                                <tr>
                                    <td><span class="badge badge-primary">#<?= $deposit['depositId'] ?></span></td>
                                    <td><?= htmlspecialchars($stationName) ?></td>
                                    <td><span class="badge badge-success">shs <?= number_format($deposit['amount'], 0) ?></span></td>
                                    <td><?= htmlspecialchars($deposit['depositedBy']) ?></td>
                                    <td><?= date('Y-m-d H:i', strtotime($deposit['created_at'])) ?></td>
                                    <td>
                                        <a href="show.php?id=<?= $deposit['depositId'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Details
                                        </a>
                                        <?php if (can('view-deposit-receipts')): ?>
                                            <a href="showReceipt.php?id=<?= $deposit['depositId'] ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-receipt"></i> Receipt
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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
    $('#depositsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[4, 'desc']],
        language: {
            search: 'Search deposits:',
            lengthMenu: 'Show _MENU_ deposits per page',
            info: 'Showing _START_ to _END_ of _TOTAL_ deposits',
            infoEmpty: 'No deposits found',
            infoFiltered: '(filtered from _MAX_ total deposits)'
        }
    });
});
</script>

<?php endPage(); ?>
