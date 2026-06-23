<?php

/**
 * Header of the application
 * @author ThinkxSoftware
 * **/
include_once '../templates/SecurePageHeader.php';
/***
 * reusable components to inject code into the template
 * */
include_once '../templates/Components.php';

if (!can('view-stages')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";

if (isset($_POST['stageDetails'])) {
    $id = $_POST['id'];
} else {
    die("not sent");
}

$_SESSION["stageId"] = $id;

$stageName = $dbAccess->select("stage", ["stageName"], ["stageId" => $id])[0]['stageName'];
$totalBorrowers = $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total
          FROM bodauser WHERE DATE(updated_at) = CURDATE() AND bodaUserStatus=2 AND fuelStationId=$id")[0]["total"];

$totalActiveBodaUsers = $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total
        FROM bodauser WHERE bodaUserStatus=1 AND fuelStationId=$id")[0]["total"];

$totalInActiveBodaUsers = $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total
        FROM bodauser WHERE bodaUserStatus=0 AND fuelStationId=$id")[0]["total"];

$totalDefaultedBodaUsers = $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total
        FROM bodauser WHERE bodaUserStatus=2 AND fuelStationId=$id")[0]["total"];

$totalActiveStages = $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total FROM bodauser
        INNER JOIN stage ON stage.stageId = bodauser.stageId 
         WHERE  stage.stageId=$id AND bodauser.bodaUserStatus=1;")[0]['total'];

$totalInActiveStages = $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total FROM bodauser
        INNER JOIN stage ON stage.stageId = bodauser.stageId 
         WHERE  stage.stageId=$id AND bodauser.bodaUserStatus=0;")[0]['total'];

$totalDefaultStages = $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total FROM bodauser
        INNER JOIN stage ON stage.stageId = bodauser.stageId 
         WHERE  stage.stageId=$id AND bodauser.bodaUserStatus=2;")[0]['total'];

$totalUnPaidLoans = $dbAccess->selectQuery("SELECT COUNT(stageId) AS total   FROM loan WHERE stageId=$id AND status=1")[0]["total"];
$totalPaidLoans = $dbAccess->selectQuery("SELECT COUNT(stageId) AS total   FROM loan WHERE stageId=$id AND status=0")[0]["total"];

$expectedFuelPerDay = $totalActiveBodaUsers * 15000;
$expectedAmountRecoveredPerDay = ($totalBorrowers * 1000) + $expectedFuelPerDay;
$expectedCrossProfit = $expectedAmountRecoveredPerDay - $expectedFuelPerDay;

startContent();

breadCrumbs(['title' => $stageName, 'sub_title' => $stageName, 'previous' => 'Home', 'previous_action' => '../dashboard/']);

?>

<div class="row">
    <div class="col-lg-3 col-6">
        <a href="#">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= number_format($expectedFuelPerDay, 1) ?></h3>
                    <p>Total Expected Fuel Per Day</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-6">
        <a href="/sunfuel/views/stage/stagebodas.php?stagename=<?= $stageName ?>&data=activebodausers">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $totalActiveBodaUsers ?></h3>
                    <p>Total Active Boda Users</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-6">
        <a href="/sunfuel/views/stage/stagebodas.php?stagename=<?= $stageName ?>&data=inactivebodausers">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $totalInActiveBodaUsers ?></h3>
                    <p>Total Inactive Active Boda Users</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-6">
        <a href="/sunfuel/views/stage/stagebodas.php?stagename=<?= $stageName ?>&data=defaultedbodausers">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $totalDefaultedBodaUsers ?></h3>
                    <p>Total Defaulted Boda Users</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-6">
        <a href="/sunfuel/views/stage/stagePaidLoans.php">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $totalPaidLoans ?></h3>
                    <p>Total Paid Loans</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-6">
        <a href="/sunfuel/views/stage/stageUnPaidLoan.php">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?= $totalUnPaidLoans ?></h3>
                    <p>Total UnPaid Loans</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<?php

endContent();

include_once '../templates/footer.php';

endPage();
