Status for the different users in the system
0-means not activated
1-means activated
2-pending payment
3-suspended



<div class="row">



					<!-- ./col -->
					<div class="col-lg-6 col-6">

						<!-- small box -->


						<!--col-->
						<div class="col-lg-6 col-6">
							<a href="#">
								<div class="small-box bg-white p-3" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px !important;">
									<div style="display: grid;place-items:center;">
										<h4>Loan Details on <?php echo date("D/M/Y"); ?> </h4>
									</div>
									<div style="display: flex;justify-content:space-around; align-items:center">
										<div class="text-info">
											<h4>
												Total Loans
											</h4>

										</div>
										<div class="text-info">
											<h4>
												<?= $totalLoans ?>
											</h4>

										</div>


									</div>
									<div style="display: flex;justify-content:space-around; align-items:center">
										<div class="text-success">
											<h4>
												Total Loan Amount
											</h4>

										</div>
										<div class="text-success">
											<h4>
												<?= "shs " . number_format($totalAmount); ?>
											</h4>

										</div>


									</div>
									<div style="display: flex;justify-content:space-around; align-items:center">
										<div class="text-success">
											<h4>
												Total Loan Interest
											</h4>

										</div>
										<div class="text-success">
											<h4>
												<?= "shs" . number_format($loanInterest) ?>
											</h4>

										</div>


									</div>
									<div style="display: flex;justify-content:space-around; align-items:center">
										<div class="text-success">
											<h4>
												Total Paid Loans
											</h4>

										</div>
										<div class="text-success">
											<h4>
												<?= "shs" . number_format($totalPaidLoans); ?>
											</h4>

										</div>


									</div>
									<div style="display: flex;justify-content:space-around; align-items:center">
										<div class="text-danger">
											<h4>
												Total Unpaid Loans
											</h4>

										</div>
										<div class="text-danger">
											<h4>
												<?= "shs" . number_format($totalAmount + $loanInterest - $totalPaidLoans); ?>
											</h4>

										</div>


									</div>

								</div>
							</a>

						</div>
						<!--col-->

						<!--col-->
						<div class="col-lg-6 col-6">
							<a href="#">
								<div class="small-box bg-white p-3" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px !important;">
									<div style="display: grid;place-items:center;">
										<h4>Stage Details on <?php echo date("D/M/Y"); ?> </h4>
									</div>
									<div style="display: flex;justify-content:space-around; align-items:center">
										<div class="text-success">
											<h4>
												Total Active Stages </h4>

										</div>
										<div class="text-success">
											<h4>
												<?= $totalActiveStages ?>
											</h4>

										</div>


									</div>
									<div style="display: flex;justify-content:space-around; align-items:center">
										<div class="text-info">
											<h4>
												Total Inactive Stages
											</h4>

										</div>
										<div class="text-info">
											<h4>
												<?= $totalInActiveStages ?>
											</h4>

										</div>


									</div>
									<div style="display: flex;justify-content:space-around; align-items:center">
										<div class="text-info">
											<h4>
												Total Defaulted Stages
											</h4>

										</div>
										<div class="text-info">
											<h4>
												<?= $totalDefaultStages ?>
											</h4>

										</div>


									</div>
									<div style="display: flex;justify-content:space-around; align-items:center">
										<div class="text-danger">
											<h4>
												Total Suspended Stages
											</h4>

										</div>
										<div class="text-danger">
											<h4>
												<?= $suspendedStages ?>
											</h4>

										</div>


									</div>


								</div>
							</a>

						</div>
						<!--col-->

						<!--col-->
						<div class="col-lg-6 col-6">
							<a href="#">
								<div class="small-box bg-white p-3" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px !important;">
									<div style="display: grid;place-items:center;">
										<h4>Fuel Station Details on <?php echo date("D/M/Y"); ?> </h4>
									</div>
									<div style="display: flex;justify-content:space-around; align-items:center">
										<div class="text-info">
											<h4>
												Total Active Fuel Stations
											</h4>

										</div>
										<div class="text-info">
											<h4>
												<?= $totalActiveFuelStations ?>
											</h4>

										</div>


									</div>
									<div style="display: flex;justify-content:space-around; align-items:center">
										<div class="text-danger">
											<h4>
												Total InActive Fuel Stations
											</h4>

										</div>
										<div class="text-danger">
											<h4>
												<?= $totalInActiveFuelStations ?>
											</h4>

										</div>


									</div>

								</div>
							</a>

						</div>







						<!-- /.row -->
						<!-- Main row -->
						<div class="row">
							<!-- Left col -->
							<section class="col-lg-7 connectedSortable">





							</section>
							<!-- right col -->
						</div>
						<!-- /.row (main row) -->