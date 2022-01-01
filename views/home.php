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
		include_once("../utils/dbaccess.php");
		//SELECT bodaUserId FROM bodauser WHERE DATE(updated_at) = CURDATE();
		$dbAccess =  new DbAccess();


		$totalActiveBodaUsers  = $dbAccess->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "1"]);
		$totalInActiveBodaUsers  = $dbAccess->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "0"]);
		$totalDefaultedBodaUsers  = $dbAccess->selectQuery("SELECT COUNT(bodaUserStatus) AS total FROM bodauser  WHERE  DATE(updated_at) = CURDATE() AND bodaUserStatus=2")[0]['total'];
		//$dbAccess->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "2"]);
		$suspendedBodaUsers =  $dbAccess->countRows("bodauser", "bodaUserStatus", ["bodaUserStatus", "3"]);
		//die($totalInActiveBodaUsers);

		//stage
		$totalActiveStages  = $dbAccess->countRows("stage", "stageStatus", ["stageStatus", "1"]);
		$totalInActiveStages  = $dbAccess->countRows("stage", "stageStatus", ["stageStatus", "0"]);
		$totalDefaultStages  = $dbAccess->countRows("stage", "stageStatus", ["stageStatus", "2"]);
		$suspendedStages  = $dbAccess->countRows("stage", "stageStatus", ["stageStatus", "3"]);

		//fuel stations
		$totalActiveFuelStations  = $dbAccess->countRows("fuelstation", "fuelStationStatus", ["fuelStationStatus", "1"]);
		$totalInActiveFuelStations  = $dbAccess->countRows("fuelstation", "fuelStationStatus", ["fuelStationStatus", "0"]);
		$suspendedFuelStations  = $dbAccess->countRows("fuelstation", "fuelStationStatus", ["fuelStationStatus", "3"]);
		//die($totalActiveFuelStations);

		//fuel consumption
		$expectedFuelPerDay = $totalActiveBodaUsers * 15000;
		//die($expectedFuelPerDay);
		$expectedAmountRecoveredPerDay =  ($totalActiveBodaUsers * 1000) + $expectedFuelPerDay;
		$expectedCrossProfit = $expectedAmountRecoveredPerDay - $expectedFuelPerDay;

		//sum of all loans
		$sql = "SELECT SUM(loanAmount) AS total FROM loan WHERE  DATE(updated_at) = CURDATE()";
		$totalAmount = $dbAccess->selectQuery($sql)[0]["total"];
		$loanInterest = $dbAccess->selectQuery("SELECT SUM(LoanInterest) AS total FROM loan  WHERE  DATE(updated_at) = CURDATE()")[0]['total'];
		$balance = $expectedFuelPerDay - $totalAmount;

		//loans
		$totalLoans = $dbAccess->selectQuery("SELECT COUNT(loanId) AS total FROM loan  WHERE  DATE(updated_at) = CURDATE()")[0]['total'];
		$totalPaidLoans = $dbAccess->selectQuery("SELECT SUM(loanAmount) AS total FROM loan  WHERE  DATE(updated_at) = CURDATE() 
		AND status='0'")[0]['total'];
		$totalunpaidLoans = $dbAccess->selectQuery("SELECT SUM(loanAmount) AS total FROM loan  WHERE  DATE(updated_at) = CURDATE()
		 AND status='1'")[0]['total'];

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

					<div class="row">
						<?php
						function welcome()
						{

							if (date("H") < 12) {

								return "Good Morning";
							} elseif (date("H") > 11 && date("H") < 18) {

								return "Good Afternoon";
							} elseif (date("H") > 17) {

								return "Good  Evening";
							}
						}
						?>
						<div class="home__top col-12">
							<ul class="navbar-nav">
								<li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
									<h1 class="welcome-text"><?= welcome(); ?> <span class="text-black fw-bold"><?= ", " . $_SESSION['user']; ?></span></h1>
									<h4 class="welcome-sub-text">Your Summary Details on <?php echo date("D/M/Y"); ?> </h4>
								</li>

							</ul>
							<div class="md-form md-outline input-with-post-icon datepicker">
								<form>
									<div class="form-group">
										<input id="theDate" class="form-control" type="date" name="date">
									</div>
								</form>
							</div>

						</div>
						<div class="col-12">
							<hr />
						</div>
						<!--floatdetails-->
						<div class="home__details col-12">

							<h2 class="home__word">Float Details</h2>
						</div>
						<div class="col-sm-12 eachCard">
							<div class="statistics-details d-flex align-items-center justify-content-between">

								<div class="home__eachCardDetails  ">
									<p class="statistics-title ">Total Expected Amount</p>
									<h3 class="rate-percentage"> <?= "shs " . number_format($expectedFuelPerDay, 0); ?></h3>

								</div>
								<div class="home__eachCardDetails">
									<p class="statistics-title">Total Amount Withdrawn</p>
									<h3 class="rate-percentage"> <?= "shs " . number_format($totalAmount, 0); ?></h3>
								</div>
							</div>

						</div>


						<!--floatdetails-->

						<!--boda details-->
						<div class="home__details col-12">
							<h2 class="home__word">Boda Details</h2>
						</div>


						<div class="col-sm-12">

							<div class="statistics-details d-flex align-items-center justify-content-between mycard">

								<div class="home__eachCardDetails">
									<p class="statistics-title ">Total Active Boda Users</p>
									<h3 class="rate-percentage"><?= $totalActiveBodaUsers ?></h3>

								</div>
								<div class="home__eachCardDetails">
									<p class="statistics-title">Total Inactive Boda Users</p>
									<h3 class="rate-percentage"> <?= $totalInActiveBodaUsers ?></h3>
								</div>
								<div class="home__eachCardDetails">
									<p class="statistics-title">Current Boda Loans</p>
									<h3 class="rate-percentage"> <?= $totalDefaultedBodaUsers ?></h3>
								</div>
								<div class="home__eachCardDetails">
									<p class="statistics-title">Total Suspended Boda Loans</p>
									<h3 class="rate-percentage"> <?= $suspendedBodaUsers ?></h3>
								</div>
							</div>
						</div>
					</div>
					<!--boda details-->

					<!--loan details-->
					<div class="home__details col-12">
						<h2 class="home__word">Loan Details</h2>
					</div>


					<div class="col-sm-12">

						<div class="statistics-details d-flex align-items-center justify-content-between mycard">

							<div class="home__eachCardDetails">
								<p class="statistics-title ">Total Loans</p>
								<h3 class="rate-percentage"> <?= $totalLoans ?></h3>

							</div>
							<div class="home__eachCardDetails">
								<p class="statistics-title">Total Loan Amount</p>
								<h3 class="rate-percentage"> <?= "shs " . number_format($totalAmount); ?></h3>
							</div>
							<div class="home__eachCardDetails">
								<p class="statistics-title">Total Loan Interest</p>
								<h3 class="rate-percentage"> <?= "shs" . number_format($loanInterest) ?></h3>
							</div>
							<div class="home__eachCardDetails">
								<p class="statistics-title">Total Paid Laons</p>
								<h3 class="rate-percentage"> <?= "shs" . number_format($totalPaidLoans); ?></h3>
							</div>
							<div class="home__eachCardDetails">
								<p class="statistics-title">Total UnPaid Laons</p>
								<h3 class="rate-percentage"> <?= "shs" . number_format($totalAmount + $loanInterest); ?></h3>
							</div>
						</div>
					</div>
				</div>

				<!--loan details-->



				<!--stage details-->
				<div class="home__details col-12">
					<h2 class="home__word">Stage Details</h2>
				</div>


				<div class="col-sm-12">

					<div class="statistics-details d-flex align-items-center justify-content-between mycard">

						<div class="home__eachCardDetails">
							<p class="statistics-title ">Total Active Stages</p>
							<h3 class="rate-percentage"> <?= $totalActiveStages ?></h3>

						</div>
						<div class="home__eachCardDetails">
							<p class="statistics-title">Total Inactive Stages</p>
							<h3 class="rate-percentage"> <?= $totalInActiveStages ?></h3>
						</div>
						<div class="home__eachCardDetails">
							<p class="statistics-title">Total Defaulted Stages</p>
							<h3 class="rate-percentage"> <?= $totalDefaultStages ?></h3>
						</div>
						<div class="home__eachCardDetails">
							<p class="statistics-title">Total Suspended</p>
							<h3 class="rate-percentage"> <?= $suspendedStages ?></h3>
						</div>
					</div>
				</div>
		</div>
		<!--stage details-->




	</div>

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

	<script src="text/javascript">
		//alert("here")
		$(document).ready(function() {
			var date = new Date();

			var day = date.getDate();
			var month = date.getMonth() + 1;
			var year = date.getFullYear();

			if (month < 10) month = "0" + month;
			if (day < 10) day = "0" + day;

			var today = year + "-" + month + "-" + day + "T00:00";
			$("#theDate").attr("value", today);
		});
	</script>

</body>

</html>