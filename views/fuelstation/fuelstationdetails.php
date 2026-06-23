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

if (!can('view-fuelstations')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";

if (isset($_POST['details'])) {
    $id = $_POST['id'];
    $_SESSION["fuelDetailsId"] = $id;
} else {
    die("not sent");
}

$details = $dbAccess->select('fuelstation', "", ['fuelstationId' => $id])[0];

$fuelStationName = $dbAccess->select("fuelstation", ["fuelStationName"], ["fuelStationId" => $id])[0]['fuelStationName'];
$totalBorrowers = $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total
          FROM bodauser WHERE DATE(updated_at) = CURDATE() AND bodaUserStatus=2 AND fuelStationId=$id")[0]["total"];

$totalActiveBodaUsers = $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total
        FROM bodauser WHERE bodaUserStatus=1 AND fuelStationId=$id")[0]["total"];

$totalInActiveBodaUsers = $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total
        FROM bodauser WHERE bodaUserStatus=0 AND fuelStationId=$id")[0]["total"];

$totalDefaultedBodaUsers = $dbAccess->selectQuery("SELECT COUNT(bodaUserId) AS total
        FROM bodauser WHERE bodaUserStatus=2 AND fuelStationId=$id")[0]["total"];

$totalActiveStages = $dbAccess->selectQuery("SELECT COUNT(stageId) AS total FROM stage 
        INNER JOIN fuelstation ON fuelstation.fuelStationId = stage.fuelStationId 
         WHERE  stage.fuelStationId=$id AND stage.stageStatus=1;")[0]['total'];

$totalInActiveStages = $dbAccess->selectQuery("SELECT COUNT(stageId) AS total FROM stage 
         INNER JOIN fuelstation ON fuelstation.fuelStationId = stage.fuelStationId 
          WHERE  stage.fuelStationId=$id AND stage.stageStatus=0;")[0]['total'];

$totalDefaultStages = $dbAccess->selectQuery("SELECT COUNT(stageId) AS total FROM stage 
         INNER JOIN fuelstation ON fuelstation.fuelStationId = stage.fuelStationId 
          WHERE  stage.fuelStationId=$id AND stage.stageStatus=2;")[0]['total'];

$expectedFuelPerDay = $totalActiveBodaUsers * 15000;
$expectedAmountRecoveredPerDay = ($totalBorrowers * 1000) + $expectedFuelPerDay;
$expectedCrossProfit = $expectedAmountRecoveredPerDay - $expectedFuelPerDay;

startContent();

breadCrumbs(['title' => $fuelStationName, 'sub_title' => $fuelStationName, 'previous' => 'Fuel Stations', 'previous_action' => './index.php']);

?>
<style>
    .statistics-details {
        margin-bottom: 48px;
    }

    .statistics-details .statistics-title {
        font-style: normal;
        font-weight: 500;
        font-size: 13px;
        line-height: 18px;
        color: #8D8D8D;
        margin-bottom: 4px;
    }

    .statistics-details .rate-percentage {
        font-style: normal;
        font-weight: bold;
        font-size: 26px;
        line-height: 36px;
        color: #000000;
        margin-bottom: 0;
    }
</style>

<div class="">
    <div class="col-sm-12">
        <div class="statistics-details d-flex align-items-center justify-content-between">
            <div>
                <p class="statistics-title">Total Amount</p>
                <h3 class="rate-percentage"><?= "shs " . number_format($details['totalAmount']) ?></h3>

            </div>
            <div>
                <p class="statistics-title">Current Amount</p>
                <h3 class="rate-percentage"><?= "shs " . number_format($details['currentAmount']) ?></h3>
            </div>
            <div>
                <p class="statistics-title">Bank Name</p>
                <h3 class="rate-percentage"><?= $details['bankName'] ?></h3>
            </div>
            <div class="d-none d-md-block">
                <p class="statistics-title">Bank Branch</p>
                <h3 class="rate-percentage"><?= $details['bankBranch'] ?></h3>
            </div>
            <div class="d-none d-md-block">
                <p class="statistics-title">Account Name</p>
                <h3 class="rate-percentage"><?= $details['AccName'] ?></h3>
            </div>
            <div class="d-none d-md-block">
                <p class="statistics-title">Account Number</p>
                <h3 class="rate-percentage"><?= $details['AccNumber'] ?></h3>

            </div>
        </div>
    </div>
</div>

<!-- Small boxes (Stat box) -->
<div class="row">

    <!-- ./col -->
    <div class="col-lg-3 col-6">

        <!-- small box -->
        <a href="#">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= number_format($expectedFuelPerDay, 1) ?></h3>

                    <p>Total Expexted Fuel Per Day</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>

            </div>
        </a>

    </div>


    <!--col-->
    <div class="col-lg-3 col-6">
        <a href="/sunfuel/views/fuelstation/activeOnEachStage.php?stationname=<?= $fuelStationName ?>&data=activebodausers">
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
    <!--col-->
    <!--col-->
    <div class="col-lg-3 col-6">
        <a href="/sunfuel/views/fuelstation/activeOnEachStage.php?stationname=<?= $fuelStationName ?>&data=inactivebodausers">
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
    <!--col-->
    <!--col-->
    <div class="col-lg-3 col-6">
        <a href="/sunfuel/views/fuelstation/activeOnEachStage.php?stationname=<?= $fuelStationName ?>&data=defaultedbodausers">
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
    <!--col-->
    <!--col-->
    <div class="col-lg-3 col-6">
        <a href="/sunfuel/views/stage/indexone.php?name=<?= $fuelStationName ?>&data=activestages">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $totalActiveStages ?></h3>

                    <p>Total Active Stages</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>

            </div>
        </a>

    </div>
    <!--col-->
    <!--col-->
    <div class="col-lg-3 col-6">
        <a href="/sunfuel/views/stage/indexone.php?name=<?= $fuelStationName ?>&data=inactivestages">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $totalInActiveStages ?></h3>

                    <p>Total InActive Stages</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>

            </div>
        </a>

    </div>
    <!--col-->
    <!--col-->
    <div class="col-lg-3 col-6">
        <a href="/sunfuel/views/stage/indexone.php?name=<?= $fuelStationName ?>&data=defaultedstages">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><?= $totalDefaultStages ?></h3>

                    <p>Total Defaulted Stages</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>

            </div>
        </a>

    </div>
    <!--col-->

</div>

<?php

endContent();

/**
 * footer of the application
 * */
include_once '../templates/footer.php';

endPage();
