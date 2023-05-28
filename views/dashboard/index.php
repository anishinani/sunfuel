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



startContent();

include_once "../templates/flashMessages.php";

include_once "../../controllers/LoansCalc.php";
$loanCalc =  new LaonsCalc();

 try {
    //$loanCalc =  new lo$loanCalc();
$totalActiveBodaUsers  = $loanCalc->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "1"]);
$totalInActiveBodaUsers  = $loanCalc->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "0"]);
$totalDefaultedBodaUsers  = $loanCalc->selectQuery("SELECT COUNT(bodaUserStatus) AS total FROM bodauser  WHERE  DATE(updated_at) = CURDATE() AND bodaUserStatus=2")[0]['total'];
$suspendedBodaUsers =  $loanCalc->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "3"]);

//stage
$totalActiveStages  = $loanCalc->countRows("stage", "stageStatus", ["stageStatus", "1"]);
$totalInActiveStages  = $loanCalc->countRows("stage", "stageStatus", ["stageStatus", "0"]);
$totalDefaultStages  = $loanCalc->countRows("stage", "stageStatus", ["stageStatus", "2"]);
$suspendedStages  = $loanCalc->countRows("stage", "stageStatus", ["stageStatus", "3"]);

//fuel stations
$totalActiveFuelStations  = $loanCalc->countRows("fuelstation", "fuelStationStatus", ["fuelStationStatus", "1"]);
$totalInActiveFuelStations  = $loanCalc->countRows("fuelstation", "fuelStationStatus", ["fuelStationStatus", "0"]);
$suspendedFuelStations  = $loanCalc->countRows("fuelstation", "fuelStationStatus", ["fuelStationStatus", "3"]);
//die($totalActiveFuelStations);

//fuel consumption
$expectedFuelPerDay = $loanCalc->expectedFuelPerDay($totalActiveBodaUsers);
//$totalActiveBodaUsers * 15000;

//sum of all loans

$totalAmount = $loanCalc->getTotalAmountLoans();
$totalLoans = $loanCalc->getTotalLaons();
$totalPaidLoans =  $loanCalc->totalPaidLaons();
$totalUnpaidLoans = $loanCalc->totalUnpaidLoans();

$totalloanspaidtoday =  $loanCalc->getTotalPaidLoansToday();
 
$totalunloanspaidtoday =  $loanCalc->getTotalUnPaidLoansToday();

//overall loan details
$overalltotalloans =  $loanCalc->getOverallTotalLoans();
$overalltotalpaidloans =  $loanCalc->overallTotalPaidLaons();
$overalltotalunpaidloans =  $loanCalc->overallTotalUnpaidLoans();
$overallloanamount =  $loanCalc->getOverallTotalAmountLoans();

$overallpaidloans = $loanCalc->getOverallTotalPaidLoans();
$overallunpaidloans = $loanCalc->getOverallTotalUnPaidLoans();


//suspended stages
$suspende_stages = $loanCalc->getOverallSuspendedStages();
$suspended_riders = $loanCalc->getOverallSuspendedRiders();
//suspende stages


$current_date = date('Y-m-d'); // Get the current date
$query = "SELECT * FROM bodauser WHERE  DATE_FORMAT(created_at, '%Y-%m-%d') = '$current_date'";
$boad_riders_onboarded_today =  $loanCalc->selectQuery($query);
$overall_boda_riders = $loanCalc->selectQuery("SELECT * FROM bodauser");

breadCrumbsTwo(['title' => 'Analytics Dashboard', 'sub_title' => 'Dashboard', 'previous' => 'Home', 'previous_action' => '#']);
 } catch (\Throwable $th) {
    //throw $th;
    var_dump($th->getMessage());
    die("here");
 }

?>
<style>
    .cursor-pointer {
        cursor: pointer !important;
    }

    .info-box {
        cursor: pointer !important;
    }

    a {
        text-decoration: none;
        color: inherit;
    }
</style>
<!-- overall summary -->
<div class="row">
    <div class="text-center">
        <h3>Overall Summary</h3>
    </div>

</div>
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

            <div class="info-box-content">
                  <a href="./overall_total_loans.php" class="cursor-pointer">
                  <span class="info-box-text">Total Loans</span>
                <span class="info-box-number">
                    <?= $overalltotalloans ?>

                </span>

                  </a>

            </div>
            <!-- /.info-box-content -->
        </div>

    </div>
    <!-- total paid -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-cog"></i></span>

            <div class="info-box-content">
                <a href="./overall_paid_loans.php" class="cursor-pointer">
                    <span class="info-box-text">Total Paid Loans</span>
                    <span class="info-box-number">
                        <?= $overallpaidloans ?>

                    </span>

                </a>

            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- total paid -->

    <!-- total unpaid -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-cog"></i></span>

            <div class="info-box-content">
                <a href="./overall_unpaid_loans.php" class="cursor-pointer">
                    <span class="info-box-text">Total Unpaid Loans</span>
                    <span class="info-box-number">
                        <?= $overallunpaidloans ?>

                    </span>

                </a>

            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- total unpaid -->
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-sort-amount-up-alt"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total Loan Amount</span>
                <span class="info-box-number"><?= "shs " . number_format($overallloanamount); ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix hidden-md-up"></div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-handshake"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total Paid Loan Amount</span>
                <span class="info-box-number"><?= "shs" . number_format($overalltotalpaidloans); ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-money-check"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total UnPaid Loan Amount</span>
                <span class="info-box-number"><?= "shs" . number_format($overalltotalunpaidloans); ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- boda details -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">

            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-motorcycle"></i></span>
            <a href="../bodauser/index.php" class="cursor-pointer">
                <div class="info-box-content">
                    <span class="info-box-text">Total Boda Riders</span>
                    <span class="info-box-number"><?= count($overall_boda_riders) ?></span>
                </div>

            </a>

            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

    <!-- boda details -->

          <!-- suspended boda riders -->
          <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-motorcycle"></i></span>

            <div class="info-box-content">
                <a href="./suspended_riders.php" class="cursor-pointer">
                    <span class="info-box-text">Suspended Boda Riders</span>
                    <span class="info-box-number"><?= $suspended_riders ?></span>

                </a>

            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
     <!-- suspende boda riders -->
</div>

<!-- overall summary -->
<!-- Info boxes -->

<div class="row">
    <div class="text-center">
        <h3>Today's Summary</h3>
    </div>

</div>

<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

            <div class="info-box-content">
                 <a href="./overall_today_loans.php" class="cursor-pointer">
                 <span class="info-box-text">Total Loans</span>
                <span class="info-box-number">
                    <?= $totalLoans ?>

                </span>

                 </a>

            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

    <!-- total paid -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-cog"></i></span>

            <div class="info-box-content">

                <a href="./today_paid_loans.php" class="cursor-pointer">
                    <span class="info-box-text">Total Paid Loans</span>
                    <span class="info-box-number">
                        <?= $totalloanspaidtoday ?>

                    </span>
                </a>

            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- total paid -->

    <!-- total unpaid -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-cog"></i></span>

            <div class="info-box-content">
                <a href="./today_unpaid_loans.php" class="cursor-pointer">
                    <span class="info-box-text">Total Unpaid Loans</span>
                    <span class="info-box-number">
                        <?= $totalunloanspaidtoday ?>

                    </span>

                </a>

            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- total unpaid -->
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-sort-amount-up-alt"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total Loan Amount</span>
                <span class="info-box-number"><?= "shs " . number_format($totalAmount); ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix hidden-md-up"></div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-handshake"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total Paid Loan Amount</span>
                <span class="info-box-number"><?= "shs" . number_format($totalPaidLoans); ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-money-check"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total UnPaid Loan Amount</span>
                <span class="info-box-number"><?= "shs" . number_format($totalUnpaidLoans); ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <!-- riders on boarded today -->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-motorcycle"></i></span>

            <div class="info-box-content">
                <a href="./today_riders.php" class="cursor-pointer">
                    <span class="info-box-text">Riders On Boarded Today</span>
                    <span class="info-box-number"><?= count($boad_riders_onboarded_today) ?></span>

                </a>

            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- riders on boarded today -->

     <!-- suspended boda riders -->
     <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-motorcycle"></i></span>

            <div class="info-box-content">
                <a href="./suspended_riders.php" class="cursor-pointer">
                    <span class="info-box-text">Suspended Boda Riders</span>
                    <span class="info-box-number"><?= $suspended_riders ?></span>

                </a>

            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
     <!-- suspende boda riders -->
     
     <!-- suspended stages -->
     <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-stop"></i></span>

            <div class="info-box-content">
                <a href="./suspend_stages.php" class="cursor-pointer">
                    <span class="info-box-text">Suspended Boda Stages</span>
                    <span class="info-box-number"><?=$suspende_stages ?></span>

                </a>

            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
     <!-- suspended stages -->

    <!-- boda details -->
</div>
<!-- /.row -->

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daily Boda Report</h5>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-wrench"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">

                            <a href="/creditpluswebapp/views/bodauser/inactivebodaUsers.php" class="dropdown-item">Inactive Boda Users</a>
                            <a href="/creditpluswebapp/views/bodauser/activebodaUsers.php" class="dropdown-item">Active Boda Users</a>
                            <a class="dropdown-divider"></a>
                            <a href="#" class="dropdown-item">Suspended Boda Users</a>
                            <a href="/creditpluswebapp/views/bodauser/defaultedBodaUsers.php" class="dropdown-item">Pending Payments</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- <p class="text-center">
												<strong>Boda Details: <?php echo date("D/M/Y"); ?></strong>
											</p> -->

                        <div class="chart">
                            <!-- Sales Chart Canvas -->
                            <canvas id="myChart" height="180" style="height: 180px;"></canvas>
                        </div>
                        <!-- /.chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <p class="text-center">
                            <strong>Expected fuel Consumption</strong>
                        </p>

                        <canvas id="fuelconsumption" height="150" style="height: 150px;"></canvas>

                        <!-- <div class="progress-group">
												Expected fuel Consumption Today
												<span class="float-right"><b>160</b>/200</span>
												<div class="progress progress-sm">
													<div class="progress-bar bg-primary" style="width: 80%"></div>
												</div>
											</div> -->
                        <!-- /.progress-group -->
                        <!-- 
											<div class="progress-group">
												Complete Purchase
												<span class="float-right"><b>400</b>/400</span>
												<div class="progress progress-sm">
													<div class="progress-bar bg-danger" style="width: 75%"></div>
												</div>
											</div> -->

                        <!-- /.progress-group -->
                        <!-- <div class="progress-group">
												<span class="progress-text">Visit Premium Page</span>
												<span class="float-right"><b>480</b>/800</span>
												<div class="progress progress-sm">
													<div class="progress-bar bg-success" style="width: 60%"></div>
												</div>
											</div> -->

                        <!-- /.progress-group -->
                        <div class="progress-group">
                            <!-- Send Inquiries
												<span class="float-right"><b>250</b>/500</span>
												<div class="progress progress-sm">
													<div class="progress-bar bg-warning" style="width: 50%"></div>
												</div> -->
                        </div>
                        <!-- /.progress-group -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>

        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->

    <!--stages-->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Daily Stage Report</h5>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-wrench"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" role="menu">

                            <a href="/creditpluswebapp/views/stage/activeStages.php" class="dropdown-item">Active Stages</a>
                            <a href="/creditpluswebapp/views/stage/inactiveStages.php" class="dropdown-item">Inactive Stages</a>
                            <a class="dropdown-divider"></a>
                            <a href="#" class="dropdown-item">Suspended Stages</a>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- <p class="text-center">
												<strong>Boda Details: <?php echo date("D/M/Y"); ?></strong>
											</p> -->

                        <div class="chart">
                            <!-- Sales Chart Canvas -->
                            <canvas id="stageId" height="180" style="height: 180px;"></canvas>
                        </div>
                        <!-- /.chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <!-- <p class="text-center">
												<strong>Expected fuel Consumption</strong>
											</p> -->

                        <canvas id="fuelconsumption" height="150" style="height: 150px;"></canvas>

                        <!-- <div class="progress-group">
												Expected fuel Consumption Today
												<span class="float-right"><b>160</b>/200</span>
												<div class="progress progress-sm">
													<div class="progress-bar bg-primary" style="width: 80%"></div>
												</div>
											</div> -->
                        <!-- /.progress-group -->
                        <!-- 
											<div class="progress-group">
												Complete Purchase
												<span class="float-right"><b>400</b>/400</span>
												<div class="progress progress-sm">
													<div class="progress-bar bg-danger" style="width: 75%"></div>
												</div>
											</div> -->

                        <!-- /.progress-group -->
                        <!-- <div class="progress-group">
												<span class="progress-text">Visit Premium Page</span>
												<span class="float-right"><b>480</b>/800</span>
												<div class="progress progress-sm">
													<div class="progress-bar bg-success" style="width: 60%"></div>
												</div>
											</div> -->

                        <!-- /.progress-group -->
                        <div class="progress-group">
                            <!-- Send Inquiries
												<span class="float-right"><b>250</b>/500</span>
												<div class="progress progress-sm">
													<div class="progress-bar bg-warning" style="width: 50%"></div>
												</div> -->
                        </div>
                        <!-- /.progress-group -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>

        </div>
        <!-- /.card -->
    </div>
    <!--stages-->
</div>


<?php
endContent();

/**
 * footer of the application
 * */
include_once '../templates/footer.php';

/**
 * custom page javascript
 * **/

?>
<script src="/creditpluswebapp/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="/creditpluswebapp/plugins/sparklines/sparkline.js"></script>

<script src="/creditpluswebapp/dist/js/pages/dashboard.js"></script>


<script>
    //alert("here");
    //var chartArray = [];

    //bodadetails
    let bodaLabels = ['Active Boda Users', 'Inactive Boda Users', 'Pending Payments', 'Suspended Boda Users'];
    var bodaUrl = "../bodachart.php";
    var chartBodaArray = [];
    var bodaId = "myChart";
    var backgroundColors = ['green', 'blue', 'yellow', 'red'];
    var borderColors = ['green', 'blue', 'yellow', 'red'];
    //bodadetails
    //fetchconsumption
    let fuelLabels = ['Expected Fuel Consumption', 'Consumed Fuel'];
    let fuelUrl = "../fuelconsumption.php";
    let fuelId = "fuelconsumption";
    let fuelBackGroundColors = ['green', 'red'];
    let fuelBorderColors = ['green', 'red'];
    let fuelArray = [];
    //fetchconsumption

    //fetchstages
    let stageLabels = ['Active Stages', 'Inactive Stages', "Defaulted Stages", "Suspended Stages"];
    let stageUrl = "../fetchstages.php";
    let stageId = "stageId";
    let stageBackGroundColors = ['green', 'blue', 'yellow', 'red'];
    let stageBorderColors = ['green', 'blue', 'yellow', 'red'];
    let stageArray = [];
    //fetchstages

    $(document).ready(function() {
        fetchChartData(bodaLabels, bodaUrl, chartBodaArray, bodaId, backgroundColors, borderColors);
        fetchChartData(fuelLabels, fuelUrl, fuelArray, fuelId, fuelBackGroundColors, fuelBorderColors);
        fetchChartData(stageLabels, stageUrl, stageArray, stageId, stageBackGroundColors, stageBorderColors);

        function fetchChartData(labels, url, chartArray, id, backGroundColors, borderColors) {
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    action: "fetch"
                },
                dataType: "json",
                success: function(data) {
                    //alert(data[0])
                    //alert(data);
                    //alert(data)
                    //console.log(data);
                    for (let index = 0; index < data.length; index++) {
                        chartArray.push(data[index].data)
                        //alert(data[index]);


                    }
                    const ctx = document.getElementById(id).getContext('2d');
                    const myChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: '# of Votes',
                                data: chartArray,
                                backgroundColor: backGroundColors,
                                borderColor: borderColors,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                }
            })

        }




    });
</script>

<script>
    // var fuelArray = [];

    // $(document).ready(function() {
    // 	$.ajax({
    // 		url: "fuelconsumption.php",
    // 		method: "POST",
    // 		data: {
    // 			action: "fetch"
    // 		},
    // 		dataType: "json",
    // 		success: function(data) {
    // 			//alert("here")
    // 			//console.log(data);

    // 			// console.log(data);
    // 			for (let index = 0; index < data.length; index++) {
    // 				fuelArray.push(data[index].amount)
    // 				//alert(data[index]);


    // 			}
    // 			//fuelconsumption
    // 			const ctx1 = document.getElementById('fuelconsumption').getContext('2d');
    // 			const myChart1 = new Chart(ctx1, {
    // 				type: 'pie',
    // 				data: {
    // 					labels: ['Expected Fuel Consumption', 'Consumed Fuel', ],
    // 					datasets: [{
    // 						label: '# of Votes',
    // 						data: fuelArray,
    // 						backgroundColor: [
    // 							'green',
    // 							'red',

    // 						],
    // 						borderColor: [
    // 							'green',

    // 							'red',

    // 						],
    // 						borderWidth: 1
    // 					}]
    // 				},
    // 				options: {
    // 					scales: {
    // 						y: {
    // 							beginAtZero: true
    // 						}
    // 					}
    // 				}
    // 			});



    // 		}
    // 	})

    // })
</script>


<?php

endPage();
