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

		//die($expectedFuelPerDay);
		// $expectedAmountRecoveredPerDay =  ($totalActiveBodaUsers * 1000) + $expectedFuelPerDay;
		// $expectedCrossProfit = $expectedAmountRecoveredPerDay - $expectedFuelPerDay;

		//sum of all loans

		$totalAmount = $loanCalc->getTotalAmountLoans();
		$totalLoans = $loanCalc->getTotalLaons();
		$totalPaidLoans =  $loanCalc->totalPaidLaons();
		$totalUnpaidLoans = $loanCalc->totalUnpaidLoans();




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
									<span class="info-box-text">Total Laon Amount</span>
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
												<a href="#" class="dropdown-item">Action</a>
												<a href="#" class="dropdown-item">Inactive Boda Users</a>
												<a href="#" class="dropdown-item">Active Boda Users</a>
												<a class="dropdown-divider"></a>
												<a href="#" class="dropdown-item">Suspended Boda Users</a>
												<a href="#" class="dropdown-item">Pendind Payments</a>
											</div>
										</div>
									</div>
								</div>
								<!-- /.card-header -->
								<div class="card-body">
									<div class="row">
										<div class="col-md-6">
											<p class="text-center">
												<strong>Boda Details: <?php echo date("D/M/Y"); ?></strong>
											</p>

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
												Send Inquiries
												<span class="float-right"><b>250</b>/500</span>
												<div class="progress progress-sm">
													<div class="progress-bar bg-warning" style="width: 50%"></div>
												</div>
											</div>
											<!-- /.progress-group -->
										</div>
										<!-- /.col -->
									</div>
									<!-- /.row -->
								</div>
								<!-- ./card-body -->
								<div class="card-footer">
									<div class="row">
										<div class="col-sm-3 col-6">
											<div class="description-block border-right">
												<span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>
												<h5 class="description-header">$35,210.43</h5>
												<span class="description-text">TOTAL REVENUE</span>
											</div>
											<!-- /.description-block -->
										</div>
										<!-- /.col -->
										<div class="col-sm-3 col-6">
											<div class="description-block border-right">
												<span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
												<h5 class="description-header">$10,390.90</h5>
												<span class="description-text">TOTAL COST</span>
											</div>
											<!-- /.description-block -->
										</div>
										<!-- /.col -->
										<div class="col-sm-3 col-6">
											<div class="description-block border-right">
												<span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
												<h5 class="description-header">$24,813.53</h5>
												<span class="description-text">TOTAL PROFIT</span>
											</div>
											<!-- /.description-block -->
										</div>
										<!-- /.col -->
										<div class="col-sm-3 col-6">
											<div class="description-block">
												<span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
												<h5 class="description-header">1200</h5>
												<span class="description-text">GOAL COMPLETIONS</span>
											</div>
											<!-- /.description-block -->
										</div>
									</div>
									<!-- /.row -->
								</div>
								<!-- /.card-footer -->
							</div>
							<!-- /.card -->
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->


					<!-- Main row -->
					<div class="row">
						<!-- Left col -->
						<div class="col-md-8">
							<!-- MAP & BOX PANE -->
							<div class="card">
								<div class="card-header">
									<h3 class="card-title">US-Visitors Report</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse">
											<i class="fas fa-minus"></i>
										</button>
										<button type="button" class="btn btn-tool" data-card-widget="remove">
											<i class="fas fa-times"></i>
										</button>
									</div>
								</div>
								<!-- /.card-header -->
								<div class="card-body p-0">
									<div class="d-md-flex">
										<div class="p-1 flex-fill" style="overflow: hidden">
											<!-- Map will be created here -->
											<div id="world-map-markers" style="height: 325px; overflow: hidden">
												<div class="map"></div>
											</div>
										</div>
										<div class="card-pane-right bg-success pt-2 pb-2 pl-4 pr-4">
											<div class="description-block mb-4">
												<div class="sparkbar pad" data-color="#fff">90,70,90,70,75,80,70</div>
												<h5 class="description-header">8390</h5>
												<span class="description-text">Visits</span>
											</div>
											<!-- /.description-block -->
											<div class="description-block mb-4">
												<div class="sparkbar pad" data-color="#fff">90,50,90,70,61,83,63</div>
												<h5 class="description-header">30%</h5>
												<span class="description-text">Referrals</span>
											</div>
											<!-- /.description-block -->
											<div class="description-block">
												<div class="sparkbar pad" data-color="#fff">90,50,90,70,61,83,63</div>
												<h5 class="description-header">70%</h5>
												<span class="description-text">Organic</span>
											</div>
											<!-- /.description-block -->
										</div><!-- /.card-pane-right -->
									</div><!-- /.d-md-flex -->
								</div>
								<!-- /.card-body -->
							</div>
							<!-- /.card -->
							<div class="row">
								<div class="col-md-6">
									<!-- DIRECT CHAT -->
									<div class="card direct-chat direct-chat-warning">
										<div class="card-header">
											<h3 class="card-title">Direct Chat</h3>

											<div class="card-tools">
												<span title="3 New Messages" class="badge badge-warning">3</span>
												<button type="button" class="btn btn-tool" data-card-widget="collapse">
													<i class="fas fa-minus"></i>
												</button>
												<button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
													<i class="fas fa-comments"></i>
												</button>
												<button type="button" class="btn btn-tool" data-card-widget="remove">
													<i class="fas fa-times"></i>
												</button>
											</div>
										</div>
										<!-- /.card-header -->
										<div class="card-body">
											<!-- Conversations are loaded here -->
											<div class="direct-chat-messages">
												<!-- Message. Default to the left -->
												<div class="direct-chat-msg">
													<div class="direct-chat-infos clearfix">
														<span class="direct-chat-name float-left">Alexander Pierce</span>
														<span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
													</div>
													<!-- /.direct-chat-infos -->
													<img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
													<!-- /.direct-chat-img -->
													<div class="direct-chat-text">
														Is this template really for free? That's unbelievable!
													</div>
													<!-- /.direct-chat-text -->
												</div>
												<!-- /.direct-chat-msg -->

												<!-- Message to the right -->
												<div class="direct-chat-msg right">
													<div class="direct-chat-infos clearfix">
														<span class="direct-chat-name float-right">Sarah Bullock</span>
														<span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
													</div>
													<!-- /.direct-chat-infos -->
													<img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
													<!-- /.direct-chat-img -->
													<div class="direct-chat-text">
														You better believe it!
													</div>
													<!-- /.direct-chat-text -->
												</div>
												<!-- /.direct-chat-msg -->

												<!-- Message. Default to the left -->
												<div class="direct-chat-msg">
													<div class="direct-chat-infos clearfix">
														<span class="direct-chat-name float-left">Alexander Pierce</span>
														<span class="direct-chat-timestamp float-right">23 Jan 5:37 pm</span>
													</div>
													<!-- /.direct-chat-infos -->
													<img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
													<!-- /.direct-chat-img -->
													<div class="direct-chat-text">
														Working with AdminLTE on a great new app! Wanna join?
													</div>
													<!-- /.direct-chat-text -->
												</div>
												<!-- /.direct-chat-msg -->

												<!-- Message to the right -->
												<div class="direct-chat-msg right">
													<div class="direct-chat-infos clearfix">
														<span class="direct-chat-name float-right">Sarah Bullock</span>
														<span class="direct-chat-timestamp float-left">23 Jan 6:10 pm</span>
													</div>
													<!-- /.direct-chat-infos -->
													<img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
													<!-- /.direct-chat-img -->
													<div class="direct-chat-text">
														I would love to.
													</div>
													<!-- /.direct-chat-text -->
												</div>
												<!-- /.direct-chat-msg -->

											</div>
											<!--/.direct-chat-messages-->

											<!-- Contacts are loaded here -->
											<div class="direct-chat-contacts">
												<ul class="contacts-list">
													<li>
														<a href="#">
															<img class="contacts-list-img" src="dist/img/user1-128x128.jpg" alt="User Avatar">

															<div class="contacts-list-info">
																<span class="contacts-list-name">
																	Count Dracula
																	<small class="contacts-list-date float-right">2/28/2015</small>
																</span>
																<span class="contacts-list-msg">How have you been? I was...</span>
															</div>
															<!-- /.contacts-list-info -->
														</a>
													</li>
													<!-- End Contact Item -->
													<li>
														<a href="#">
															<img class="contacts-list-img" src="dist/img/user7-128x128.jpg" alt="User Avatar">

															<div class="contacts-list-info">
																<span class="contacts-list-name">
																	Sarah Doe
																	<small class="contacts-list-date float-right">2/23/2015</small>
																</span>
																<span class="contacts-list-msg">I will be waiting for...</span>
															</div>
															<!-- /.contacts-list-info -->
														</a>
													</li>
													<!-- End Contact Item -->
													<li>
														<a href="#">
															<img class="contacts-list-img" src="dist/img/user3-128x128.jpg" alt="User Avatar">

															<div class="contacts-list-info">
																<span class="contacts-list-name">
																	Nadia Jolie
																	<small class="contacts-list-date float-right">2/20/2015</small>
																</span>
																<span class="contacts-list-msg">I'll call you back at...</span>
															</div>
															<!-- /.contacts-list-info -->
														</a>
													</li>
													<!-- End Contact Item -->
													<li>
														<a href="#">
															<img class="contacts-list-img" src="dist/img/user5-128x128.jpg" alt="User Avatar">

															<div class="contacts-list-info">
																<span class="contacts-list-name">
																	Nora S. Vans
																	<small class="contacts-list-date float-right">2/10/2015</small>
																</span>
																<span class="contacts-list-msg">Where is your new...</span>
															</div>
															<!-- /.contacts-list-info -->
														</a>
													</li>
													<!-- End Contact Item -->
													<li>
														<a href="#">
															<img class="contacts-list-img" src="dist/img/user6-128x128.jpg" alt="User Avatar">

															<div class="contacts-list-info">
																<span class="contacts-list-name">
																	John K.
																	<small class="contacts-list-date float-right">1/27/2015</small>
																</span>
																<span class="contacts-list-msg">Can I take a look at...</span>
															</div>
															<!-- /.contacts-list-info -->
														</a>
													</li>
													<!-- End Contact Item -->
													<li>
														<a href="#">
															<img class="contacts-list-img" src="dist/img/user8-128x128.jpg" alt="User Avatar">

															<div class="contacts-list-info">
																<span class="contacts-list-name">
																	Kenneth M.
																	<small class="contacts-list-date float-right">1/4/2015</small>
																</span>
																<span class="contacts-list-msg">Never mind I found...</span>
															</div>
															<!-- /.contacts-list-info -->
														</a>
													</li>
													<!-- End Contact Item -->
												</ul>
												<!-- /.contacts-list -->
											</div>
											<!-- /.direct-chat-pane -->
										</div>
										<!-- /.card-body -->
										<div class="card-footer">
											<form action="#" method="post">
												<div class="input-group">
													<input type="text" name="message" placeholder="Type Message ..." class="form-control">
													<span class="input-group-append">
														<button type="button" class="btn btn-warning">Send</button>
													</span>
												</div>
											</form>
										</div>
										<!-- /.card-footer-->
									</div>
									<!--/.direct-chat -->
								</div>
								<!-- /.col -->

								<div class="col-md-6">
									<!-- USERS LIST -->
									<div class="card">
										<div class="card-header">
											<h3 class="card-title">Latest Members</h3>

											<div class="card-tools">
												<span class="badge badge-danger">8 New Members</span>
												<button type="button" class="btn btn-tool" data-card-widget="collapse">
													<i class="fas fa-minus"></i>
												</button>
												<button type="button" class="btn btn-tool" data-card-widget="remove">
													<i class="fas fa-times"></i>
												</button>
											</div>
										</div>
										<!-- /.card-header -->
										<div class="card-body p-0">
											<ul class="users-list clearfix">
												<li>
													<img src="dist/img/user1-128x128.jpg" alt="User Image">
													<a class="users-list-name" href="#">Alexander Pierce</a>
													<span class="users-list-date">Today</span>
												</li>
												<li>
													<img src="dist/img/user8-128x128.jpg" alt="User Image">
													<a class="users-list-name" href="#">Norman</a>
													<span class="users-list-date">Yesterday</span>
												</li>
												<li>
													<img src="dist/img/user7-128x128.jpg" alt="User Image">
													<a class="users-list-name" href="#">Jane</a>
													<span class="users-list-date">12 Jan</span>
												</li>
												<li>
													<img src="dist/img/user6-128x128.jpg" alt="User Image">
													<a class="users-list-name" href="#">John</a>
													<span class="users-list-date">12 Jan</span>
												</li>
												<li>
													<img src="dist/img/user2-160x160.jpg" alt="User Image">
													<a class="users-list-name" href="#">Alexander</a>
													<span class="users-list-date">13 Jan</span>
												</li>
												<li>
													<img src="dist/img/user5-128x128.jpg" alt="User Image">
													<a class="users-list-name" href="#">Sarah</a>
													<span class="users-list-date">14 Jan</span>
												</li>
												<li>
													<img src="dist/img/user4-128x128.jpg" alt="User Image">
													<a class="users-list-name" href="#">Nora</a>
													<span class="users-list-date">15 Jan</span>
												</li>
												<li>
													<img src="dist/img/user3-128x128.jpg" alt="User Image">
													<a class="users-list-name" href="#">Nadia</a>
													<span class="users-list-date">15 Jan</span>
												</li>
											</ul>
											<!-- /.users-list -->
										</div>
										<!-- /.card-body -->
										<div class="card-footer text-center">
											<a href="javascript:">View All Users</a>
										</div>
										<!-- /.card-footer -->
									</div>
									<!--/.card -->
								</div>
								<!-- /.col -->
							</div>
							<!-- /.row -->

							<!-- TABLE: LATEST ORDERS -->
							<div class="card">
								<div class="card-header border-transparent">
									<h3 class="card-title">Latest Orders</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse">
											<i class="fas fa-minus"></i>
										</button>
										<button type="button" class="btn btn-tool" data-card-widget="remove">
											<i class="fas fa-times"></i>
										</button>
									</div>
								</div>
								<!-- /.card-header -->
								<div class="card-body p-0">
									<div class="table-responsive">
										<table class="table m-0">
											<thead>
												<tr>
													<th>Order ID</th>
													<th>Item</th>
													<th>Status</th>
													<th>Popularity</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><a href="pages/examples/invoice.html">OR9842</a></td>
													<td>Call of Duty IV</td>
													<td><span class="badge badge-success">Shipped</span></td>
													<td>
														<div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
													</td>
												</tr>
												<tr>
													<td><a href="pages/examples/invoice.html">OR1848</a></td>
													<td>Samsung Smart TV</td>
													<td><span class="badge badge-warning">Pending</span></td>
													<td>
														<div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
													</td>
												</tr>
												<tr>
													<td><a href="pages/examples/invoice.html">OR7429</a></td>
													<td>iPhone 6 Plus</td>
													<td><span class="badge badge-danger">Delivered</span></td>
													<td>
														<div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
													</td>
												</tr>
												<tr>
													<td><a href="pages/examples/invoice.html">OR7429</a></td>
													<td>Samsung Smart TV</td>
													<td><span class="badge badge-info">Processing</span></td>
													<td>
														<div class="sparkbar" data-color="#00c0ef" data-height="20">90,80,-90,70,-61,83,63</div>
													</td>
												</tr>
												<tr>
													<td><a href="pages/examples/invoice.html">OR1848</a></td>
													<td>Samsung Smart TV</td>
													<td><span class="badge badge-warning">Pending</span></td>
													<td>
														<div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
													</td>
												</tr>
												<tr>
													<td><a href="pages/examples/invoice.html">OR7429</a></td>
													<td>iPhone 6 Plus</td>
													<td><span class="badge badge-danger">Delivered</span></td>
													<td>
														<div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
													</td>
												</tr>
												<tr>
													<td><a href="pages/examples/invoice.html">OR9842</a></td>
													<td>Call of Duty IV</td>
													<td><span class="badge badge-success">Shipped</span></td>
													<td>
														<div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<!-- /.table-responsive -->
								</div>
								<!-- /.card-body -->
								<div class="card-footer clearfix">
									<a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>
									<a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Orders</a>
								</div>
								<!-- /.card-footer -->
							</div>
							<!-- /.card -->
						</div>
						<!-- /.col -->

						<div class="col-md-4">
							<!-- Info Boxes Style 2 -->
							<div class="info-box mb-3 bg-warning">
								<span class="info-box-icon"><i class="fas fa-tag"></i></span>

								<div class="info-box-content">
									<span class="info-box-text">Inventory</span>
									<span class="info-box-number">5,200</span>
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
							<div class="info-box mb-3 bg-success">
								<span class="info-box-icon"><i class="far fa-heart"></i></span>

								<div class="info-box-content">
									<span class="info-box-text">Mentions</span>
									<span class="info-box-number">92,050</span>
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
							<div class="info-box mb-3 bg-danger">
								<span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>

								<div class="info-box-content">
									<span class="info-box-text">Downloads</span>
									<span class="info-box-number">114,381</span>
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
							<div class="info-box mb-3 bg-info">
								<span class="info-box-icon"><i class="far fa-comment"></i></span>

								<div class="info-box-content">
									<span class="info-box-text">Direct Messages</span>
									<span class="info-box-number">163,921</span>
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->

							<div class="card">
								<div class="card-header">
									<h3 class="card-title">Browser Usage</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse">
											<i class="fas fa-minus"></i>
										</button>
										<button type="button" class="btn btn-tool" data-card-widget="remove">
											<i class="fas fa-times"></i>
										</button>
									</div>
								</div>
								<!-- /.card-header -->
								<div class="card-body">
									<div class="row">
										<div class="col-md-8">
											<div class="chart-responsive">
												<canvas id="pieChart" height="150"></canvas>
											</div>
											<!-- ./chart-responsive -->
										</div>
										<!-- /.col -->
										<div class="col-md-4">
											<ul class="chart-legend clearfix">
												<li><i class="far fa-circle text-danger"></i> Chrome</li>
												<li><i class="far fa-circle text-success"></i> IE</li>
												<li><i class="far fa-circle text-warning"></i> FireFox</li>
												<li><i class="far fa-circle text-info"></i> Safari</li>
												<li><i class="far fa-circle text-primary"></i> Opera</li>
												<li><i class="far fa-circle text-secondary"></i> Navigator</li>
											</ul>
										</div>
										<!-- /.col -->
									</div>
									<!-- /.row -->
								</div>
								<!-- /.card-body -->
								<div class="card-footer bg-light p-0">
									<ul class="nav nav-pills flex-column">
										<li class="nav-item">
											<a href="#" class="nav-link">
												United States of America
												<span class="float-right text-danger">
													<i class="fas fa-arrow-down text-sm"></i>
													12%</span>
											</a>
										</li>
										<li class="nav-item">
											<a href="#" class="nav-link">
												India
												<span class="float-right text-success">
													<i class="fas fa-arrow-up text-sm"></i> 4%
												</span>
											</a>
										</li>
										<li class="nav-item">
											<a href="#" class="nav-link">
												China
												<span class="float-right text-warning">
													<i class="fas fa-arrow-left text-sm"></i> 0%
												</span>
											</a>
										</li>
									</ul>
								</div>
								<!-- /.footer -->
							</div>
							<!-- /.card -->

							<!-- PRODUCT LIST -->
							<div class="card">
								<div class="card-header">
									<h3 class="card-title">Recently Added Products</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse">
											<i class="fas fa-minus"></i>
										</button>
										<button type="button" class="btn btn-tool" data-card-widget="remove">
											<i class="fas fa-times"></i>
										</button>
									</div>
								</div>
								<!-- /.card-header -->
								<div class="card-body p-0">
									<ul class="products-list product-list-in-card pl-2 pr-2">
										<li class="item">
											<div class="product-img">
												<img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
											</div>
											<div class="product-info">
												<a href="javascript:void(0)" class="product-title">Samsung TV
													<span class="badge badge-warning float-right">$1800</span></a>
												<span class="product-description">
													Samsung 32" 1080p 60Hz LED Smart HDTV.
												</span>
											</div>
										</li>
										<!-- /.item -->
										<li class="item">
											<div class="product-img">
												<img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
											</div>
											<div class="product-info">
												<a href="javascript:void(0)" class="product-title">Bicycle
													<span class="badge badge-info float-right">$700</span></a>
												<span class="product-description">
													26" Mongoose Dolomite Men's 7-speed, Navy Blue.
												</span>
											</div>
										</li>
										<!-- /.item -->
										<li class="item">
											<div class="product-img">
												<img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
											</div>
											<div class="product-info">
												<a href="javascript:void(0)" class="product-title">
													Xbox One <span class="badge badge-danger float-right">
														$350
													</span>
												</a>
												<span class="product-description">
													Xbox One Console Bundle with Halo Master Chief Collection.
												</span>
											</div>
										</li>
										<!-- /.item -->
										<li class="item">
											<div class="product-img">
												<img src="dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
											</div>
											<div class="product-info">
												<a href="javascript:void(0)" class="product-title">PlayStation 4
													<span class="badge badge-success float-right">$399</span></a>
												<span class="product-description">
													PlayStation 4 500GB Console (PS4)
												</span>
											</div>
										</li>
										<!-- /.item -->
									</ul>
								</div>
								<!-- /.card-body -->
								<div class="card-footer text-center">
									<a href="javascript:void(0)" class="uppercase">View All Products</a>
								</div>
								<!-- /.card-footer -->
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
		var chartArray = [];


		$(document).ready(function() {





			$.ajax({
				url: "bodachart.php",
				method: "POST",
				data: {
					action: "fetch"
				},
				dataType: "json",
				success: function(data) {
					//alert(data[0])
					//alert(data);
					//alert(da)
					//console.log(data);
					for (let index = 0; index < data.length; index++) {
						chartArray.push(data[index].total)
						//alert(data[index]);


					}
					const ctx = document.getElementById('myChart').getContext('2d');
					const myChart = new Chart(ctx, {
						type: 'pie',
						data: {
							labels: ['Active Boda Users', 'Inactive Boda Users', 'Suspended Boda Users', 'Pending Payments'],
							datasets: [{
								label: '# of Votes',
								data: chartArray,
								backgroundColor: [
									'green',
									'blue',
									'red',
									'yellow',
								],
								borderColor: [
									'green',
									'blue',
									'red',
									'yellow',
								],
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



		});
	</script>

	<script>
		//alert("here");
		var fuelArray = [];

		$(document).ready(function() {
			$.ajax({
				url: "fuelconsumption.php",
				method: "POST",
				data: {
					action: "fetch"
				},
				dataType: "json",
				success: function(data) {
					//alert("here")
					//console.log(data);

					// console.log(data);
					for (let index = 0; index < data.length; index++) {
						fuelArray.push(data[index].amount)
						//alert(data[index]);


					}
					//fuelconsumption
					const ctx1 = document.getElementById('fuelconsumption').getContext('2d');
					const myChart1 = new Chart(ctx1, {
						type: 'pie',
						data: {
							labels: ['Expected Fuel Consumption', 'Consumed Fuel', ],
							datasets: [{
								label: '# of Votes',
								data: fuelArray,
								backgroundColor: [
									'green',
									'red',

								],
								borderColor: [
									'green',

									'red',

								],
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

		})
	</script>

</body>

</html>