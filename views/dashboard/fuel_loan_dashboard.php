<?php
require_once '../../utils/dbaccess.php';
require_once '../../controllers/FuelLoanController.php';
require_once '../../controllers/LoanManagementController.php';

// Check if user is logged in as admin
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'super_admin'])) {
    header('Location: ../../login.php');
    exit();
}

$fuelLoanController = new FuelLoanController();
$loanController = new LoanManagementController();

// Get statistics
$todayStats = $loanController->getLoanStatistics();
$smsStats = $this->smsController->getSMSStats();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel Loan Dashboard - SunFuel</title>
    <link href="../../plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../plugins/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="../../plugins/chart.js/Chart.min.css" rel="stylesheet">
    <style>
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .stats-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .stats-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stats-card.danger {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 30px;
        }
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-tachometer-alt"></i> Fuel Loan Dashboard
            </a>
            <div class="navbar-nav ml-auto">
                <a href="../dashboard/index.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Main Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo $todayStats['totalLoans'] ?? 0; ?></h4>
                            <p class="mb-0">Today's Loans</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-gas-pump fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card success">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo number_format($todayStats['totalLoanAmount'] ?? 0); ?></h4>
                            <p class="mb-0">Total Amount (UGX)</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card warning">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo $todayStats['activeLoans'] ?? 0; ?></h4>
                            <p class="mb-0">Active Loans</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card danger">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo $todayStats['overdueLoans'] ?? 0; ?></h4>
                            <p class="mb-0">Overdue Loans</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-pie"></i> Loan Status Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="loanStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-line"></i> Daily Loan Trends</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="dailyTrendsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-history"></i> Recent Loan Activity</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Station</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="recentActivity">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            Loading recent activity...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-mobile-alt"></i> SMS Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Total SMS Sent:</span>
                                <strong><?php echo $smsStats['totalSMS'] ?? 0; ?></strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Activation Codes:</span>
                                <strong><?php echo $smsStats['activationSMS'] ?? 0; ?></strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Payment Reminders:</span>
                                <strong><?php echo $smsStats['reminderSMS'] ?? 0; ?></strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Payment Confirmations:</span>
                                <strong><?php echo $smsStats['paymentSMS'] ?? 0; ?></strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Failed SMS:</span>
                                <strong class="text-danger"><?php echo $smsStats['failedSMS'] ?? 0; ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-cogs"></i> System Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h6>Borrowing Window</h6>
                                    <div class="mb-2">
                                        <i class="fas fa-clock fa-2x text-success"></i>
                                    </div>
                                    <p class="mb-0">6:00 AM - 12:00 PM</p>
                                    <small class="text-muted">
                                        <?php 
                                        $currentTime = date('H:i:s');
                                        $canBorrow = ($currentTime >= '06:00:00' && $currentTime <= '12:00:00');
                                        echo $canBorrow ? 'Currently Active' : 'Currently Inactive';
                                        ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h6>Payment Window</h6>
                                    <div class="mb-2">
                                        <i class="fas fa-credit-card fa-2x text-warning"></i>
                                    </div>
                                    <p class="mb-0">5:00 PM - 12:00 AM</p>
                                    <small class="text-muted">
                                        <?php 
                                        $currentTime = date('H:i:s');
                                        $canPay = ($currentTime >= '17:00:00' || $currentTime <= '23:59:59');
                                        echo $canPay ? 'Currently Active' : 'Currently Inactive';
                                        ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h6>Active Sessions</h6>
                                    <div class="mb-2">
                                        <i class="fas fa-users fa-2x text-info"></i>
                                    </div>
                                    <p class="mb-0" id="activeSessions">Loading...</p>
                                    <small class="text-muted">USSD Sessions</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h6>System Health</h6>
                                    <div class="mb-2">
                                        <i class="fas fa-heartbeat fa-2x text-success"></i>
                                    </div>
                                    <p class="mb-0">Healthy</p>
                                    <small class="text-muted">All systems operational</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../plugins/chart.js/Chart.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Load recent activity
            loadRecentActivity();
            
            // Load active sessions
            loadActiveSessions();
            
            // Initialize charts
            initCharts();
            
            // Auto-refresh every 30 seconds
            setInterval(function() {
                loadRecentActivity();
                loadActiveSessions();
            }, 30000);
        });

        function loadRecentActivity() {
            $.ajax({
                url: '../../api/get_recent_loan_activity.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success && data.activities.length > 0) {
                        let html = '';
                        data.activities.forEach(function(activity) {
                            let statusClass = 'badge-secondary';
                            if (activity.status === 'paid') statusClass = 'badge-success';
                            else if (activity.status === 'active') statusClass = 'badge-warning';
                            else if (activity.status === 'overdue') statusClass = 'badge-danger';
                            
                            html += '<tr>';
                            html += '<td>' + activity.time + '</td>';
                            html += '<td>' + activity.customer + '</td>';
                            html += '<td>' + activity.amount + '</td>';
                            html += '<td>' + activity.station + '</td>';
                            html += '<td><span class="badge ' + statusClass + '">' + activity.statusText + '</span></td>';
                            html += '</tr>';
                        });
                        $('#recentActivity').html(html);
                    } else {
                        $('#recentActivity').html(
                            '<tr><td colspan="5" class="text-center text-muted">No recent activity</td></tr>'
                        );
                    }
                },
                error: function() {
                    $('#recentActivity').html(
                        '<tr><td colspan="5" class="text-center text-danger">Error loading data</td></tr>'
                    );
                }
            });
        }

        function loadActiveSessions() {
            $.ajax({
                url: '../../api/get_active_sessions.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        $('#activeSessions').text(data.count);
                    }
                }
            });
        }

        function initCharts() {
            // Loan Status Chart
            const loanStatusCtx = document.getElementById('loanStatusChart').getContext('2d');
            new Chart(loanStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Paid', 'Active', 'Overdue'],
                    datasets: [{
                        data: [
                            <?php echo $todayStats['paidLoans'] ?? 0; ?>,
                            <?php echo $todayStats['activeLoans'] ?? 0; ?>,
                            <?php echo $todayStats['overdueLoans'] ?? 0; ?>
                        ],
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Daily Trends Chart (sample data)
            const dailyTrendsCtx = document.getElementById('dailyTrendsChart').getContext('2d');
            new Chart(dailyTrendsCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Loans',
                        data: [12, 19, 15, 25, 22, 18, 14],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
