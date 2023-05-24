<?php
session_start();

if (!isset($_SESSION['user'])) {
	header("Location:/creditpluswebapp/index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Credit Plus Dashboard</title>
	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="/creditpluswebapp/plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Tempusdominus Bootstrap 4 -->
	<link rel="stylesheet" href="/creditpluswebapp/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="/creditpluswebapp/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- JQVMap -->
	<link rel="stylesheet" href="/creditpluswebapp/plugins/jqvmap/jqvmap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="/creditpluswebapp/dist/css/adminlte.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="/creditpluswebapp/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="/creditpluswebapp/plugins/daterangepicker/daterangepicker.css">
	<!-- summernote -->
	<link rel="stylesheet" href="/creditpluswebapp/plugins/summernote/summernote-bs4.min.css">
	<!-- 
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->

	<style>
		.welcome-text,
		.welcome-sub-text {
			font-family: "Manrope", sans-serif !important;
			font-style: normal !important;
			font-weight: normal !important;
			font-size: 28px !important;
			line-height: 38px !important;
			color: #8D8D8D !important;
			margin-bottom: 10px !important;
		}

		.home__top {
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

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

		.mycard {
			/* box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px !important; */
		}

		.home__details {
			display: flex;
			justify-content: center;
			align-items: center;
			padding: 20px;
		}

		.home__word {}

		.home__content {
			height: 150vh !important;
			padding: 20px 40px !important;
			;
		}

		.eachCard {
			/* height: 15vh !important; */
			/* width: 100% !important;
			box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px !important; */
		}

		.home__eachCardDetails {
			padding-top: 20px !important;
			padding-left: 20px !important;
			padding-right: 20px !important;

		}
	</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">
		<?php

		include_once("./navbar/navbar.php");
		include_once("sidebar.php");
		//include_once("../utils/lo$loanCalc.php");
		//include_once("./controllers/LoansCalc.php");
		//SELECT bodaUserId FROM bodauser WHERE DATE(updated_at) = CURDATE();
		include_once("../utils/dbaccess.php");
		include_once("../controllers/LoansCalc.php");
		$loanCalc =  new LaonsCalc();
		//$loanCalc =  new lo$loanCalc();
		$totalActiveBodaUsers  = $loanCalc->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "1"]);
		$totalInActiveBodaUsers  = $loanCalc->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "0"]);
		$totalDefaultedBodaUsers  = $loanCalc->selectQuery("SELECT COUNT(bodaUserStatus) AS total FROM bodauser  WHERE  DATE(updated_at) = CURDATE() AND bodaUserStatus=2")[0]['total'];
		//$loanCalc->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "2"]);
		$suspendedBodaUsers =  $loanCalc->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "3"]);
		//die($totalInActiveBodaUsers);

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

		//boda user details

		$current_date = date('Y-m-d'); // Get the current date
		$query = "SELECT * FROM bodauser WHERE  DATE_FORMAT(created_at, '%Y-%m-%d') = '$current_date'";
		$boad_riders_onboarded_today =  $loanCalc->selectQuery($query);
		$overall_boda_riders = $loanCalc->selectQuery("SELECT * FROM bodauser");




		?>


		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0">Dashboard</h1>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="#">Home</a></li>
								<li class="breadcrumb-item active">Dashboard</li>
							</ol>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->
			<!-- Main content -->
			<section class="content home__content">
				<div class="container-fluid">

					<!--dummy--content-->
					<!-- Info boxes -->
					<div class="row">
						<div class="col-12 col-sm-6 col-md-3">
							<div class="info-box">
								<span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

								<div class="info-box-content">
									<span class="info-box-text">Total Loans</span>
									<span class="info-box-number">
										<?= $totalLoans ?>

									</span>
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
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
									<span class="info-box-text">Total Paid Loans</span>
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
									<span class="info-box-text">Total UnPaid Loans</span>
									<span class="info-box-number"><?= "shs" . number_format($totalUnpaidLoans); ?></span>
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->

						<!-- boda riders -->

						<!-- boda riders -->
					</div>

					<!-- /.row -->

					<!-- row -->
					<div class="row">
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

					</div>
					<!-- row -->

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
					<!-- /.row -->


					<!-- Main row -->

				</div>
				<!-- /.card -->
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->
	<!--dummy--content-->
	</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
	<?php
	include_once("./footer/footer.php");
	?>


	<!-- Control Sidebar -->
	<aside class="control-sidebar control-sidebar-dark">
		<!-- Control sidebar content goes here -->
	</aside>
	<!-- /.control-sidebar -->
	</div>
	<!-- ./wrapper -->

	<!-- jQuery -->
	<script src="/creditpluswebapp/plugins/jquery/jquery.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="/creditpluswebapp/plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
		$.widget.bridge('uibutton', $.ui.button)
	</script>
	<!-- Bootstrap 4 -->
	<script src="/creditpluswebapp/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- ChartJS -->

	<script src="/creditpluswebapp/plugins/chart.js/Chart.min.js"></script>
	<!-- Sparkline -->
	<script src="/creditpluswebapp/plugins/sparklines/sparkline.js"></script>
	<!-- JQVMap -->
	<script src="/creditpluswebapp/plugins/jqvmap/jquery.vmap.min.js"></script>
	<script src="/creditpluswebapp/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
	<!-- jQuery Knob Chart -->
	<script src="/creditpluswebapp/plugins/jquery-knob/jquery.knob.min.js"></script>
	<!-- daterangepicker -->
	<script src="/creditpluswebapp/plugins/moment/moment.min.js"></script>
	<script src="/creditpluswebapp/plugins/daterangepicker/daterangepicker.js"></script>

	<!-- Tempusdominus Bootstrap 4 -->
	<script src="/creditpluswebapp/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
	<!-- Summernote -->
	<script src="/creditpluswebapp/plugins/summernote/summernote-bs4.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="/creditpluswebapp/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="/creditpluswebapp/dist/js/adminlte.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="/creditpluswebapp/dist/js/demo.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="/creditpluswebapp/dist/js/pages/dashboard.js"></script>


	<script>
		//alert("here");
		//var chartArray = [];

		//bodadetails
		let bodaLabels = ['Active Boda Users', 'Inactive Boda Users', 'Pending Payments', 'Suspended Boda Users'];
		var bodaUrl = "bodachart.php";
		var chartBodaArray = [];
		var bodaId = "myChart";
		var backgroundColors = ['green', 'blue', 'yellow', 'red'];
		var borderColors = ['green', 'blue', 'yellow', 'red'];
		//bodadetails
		//fetchconsumption
		let fuelLabels = ['Expected Fuel Consumption', 'Consumed Fuel'];
		let fuelUrl = "fuelconsumption.php";
		let fuelId = "fuelconsumption";
		let fuelBackGroundColors = ['green', 'red'];
		let fuelBorderColors = ['green', 'red'];
		let fuelArray = [];
		//fetchconsumption

		//fetchstages
		let stageLabels = ['Active Stages', 'Inactive Stages', "Defaulted Stages", "Suspended Stages"];
		let stageUrl = "fetchstages.php";
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


</body>

</html>