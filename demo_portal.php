<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SunFuel Demo Portal</title>
    <link href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="plugins/fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .demo-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        .demo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }
        .demo-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .demo-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .demo-description {
            color: #6c757d;
            margin-bottom: 1.5rem;
        }
        .btn-demo {
            padding: 12px 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 25px;
        }
        .header-section {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
        }
        .setup-section {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Header -->
        <div class="header-section text-center">
            <h1><i class="fas fa-gas-pump"></i> SunFuel Demo Portal</h1>
            <p class="lead">Complete Fuel Loan System Demonstration</p>
            <p>Test the entire Boda Boda fuel loan workflow with our interactive demo system</p>
        </div>

        <!-- Setup Section -->
        <div class="setup-section">
            <h3><i class="fas fa-cog"></i> Quick Setup</h3>
            <p>First, set up the demo data and fuel agent for testing:</p>
            <div class="row">
                <div class="col-md-6">
                    <div class="d-grid">
                        <a href="setup_ussd_demo_data.php" class="btn btn-primary btn-demo">
                            <i class="fas fa-database"></i> Setup Demo Data
                        </a>
                        <small class="text-muted mt-2">Creates demo users, stations, and packages</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid">
                        <a href="setup_demo_fuel_agent.php" class="btn btn-success btn-demo">
                            <i class="fas fa-user-tie"></i> Create Demo Agent
                        </a>
                        <small class="text-muted mt-2">Creates fuel station agent for testing</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demo Components -->
        <div class="row">
            <!-- USSD Simulator -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="demo-card p-4 text-center position-relative">
                    <span class="badge badge-success status-badge">Live Demo</span>
                    <div class="demo-icon text-primary">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="demo-title">USSD Simulator</div>
                    <div class="demo-description">
                        Interactive USSD interface for testing fuel loan requests, payments, and balance checking
                    </div>
                    <div class="demo-features mb-3">
                        <small class="text-muted">
                            ✓ Fuel requests<br>
                            ✓ Payment processing<br>
                            ✓ Balance checking<br>
                            ✓ Real-time validation
                        </small>
                    </div>
                    <a href="ussd_simulator.php" class="btn btn-primary btn-demo" target="_blank">
                        <i class="fas fa-play"></i> Launch Simulator
                    </a>
                </div>
            </div>

            <!-- Fuel Agent Portal -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="demo-card p-4 text-center position-relative">
                    <span class="badge badge-info status-badge">Agent Portal</span>
                    <div class="demo-icon text-success">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="demo-title">Fuel Agent Portal</div>
                    <div class="demo-description">
                        Web interface for fuel station agents to activate fuel requests and monitor station status
                    </div>
                    <div class="demo-features mb-3">
                        <small class="text-muted">
                            ✓ Activation code entry<br>
                            ✓ Station float monitoring<br>
                            ✓ Recent activity history<br>
                            ✓ Low float alerts
                        </small>
                    </div>
                    <a href="fuel_agent_login.php" class="btn btn-success btn-demo" target="_blank">
                        <i class="fas fa-sign-in-alt"></i> Agent Login
                    </a>
                    <a href="agent_ussd_simulator.php" class="btn btn-outline-success btn-demo mt-2" target="_blank">
                        <i class="fas fa-mobile-alt"></i> Agent USSD Simulator
                    </a>
                </div>
            </div>

            <!-- Agent USSD Simulator -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="demo-card p-4 text-center position-relative">
                    <span class="badge badge-success status-badge">Agent USSD</span>
                    <div class="demo-icon text-success">
                        <i class="fas fa-gas-pump"></i>
                    </div>
                    <div class="demo-title">Agent USSD Simulator</div>
                    <div class="demo-description">
                        USSD interface for fuel agents to enter rider activation codes and confirm fuel dispatch
                    </div>
                    <div class="demo-features mb-3">
                        <small class="text-muted">
                            ✓ Enter activation code<br>
                            ✓ Confirm dispatch<br>
                            ✓ Check station float<br>
                            ✓ Dial *124#
                        </small>
                    </div>
                    <a href="agent_ussd_simulator.php" class="btn btn-success btn-demo" target="_blank">
                        <i class="fas fa-play"></i> Launch Agent USSD
                    </a>
                </div>
            </div>

            <!-- Admin Dashboard -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="demo-card p-4 text-center position-relative">
                    <span class="badge badge-warning status-badge">Admin Panel</span>
                    <div class="demo-icon text-warning">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="demo-title">Admin Dashboard</div>
                    <div class="demo-description">
                        Comprehensive dashboard for monitoring loan statistics, SMS delivery, and system status
                    </div>
                    <div class="demo-features mb-3">
                        <small class="text-muted">
                            ✓ Real-time statistics<br>
                            ✓ SMS delivery reports<br>
                            ✓ Loan analytics<br>
                            ✓ System monitoring
                        </small>
                    </div>
                    <a href="views/dashboard/fuel_loan_dashboard.php" class="btn btn-warning btn-demo" target="_blank">
                        <i class="fas fa-tachometer-alt"></i> View Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Demo Workflow -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="demo-card p-4">
                    <h3><i class="fas fa-route"></i> Demo Workflow</h3>
                    <p>Follow this step-by-step workflow to test the complete fuel loan system:</p>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="text-center">
                                <div class="demo-icon text-primary">
                                    <i class="fas fa-1"></i>
                                </div>
                                <h6>Setup</h6>
                                <p class="small">Create demo data and agent</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-center">
                                <div class="demo-icon text-success">
                                    <i class="fas fa-2"></i>
                                </div>
                                <h6>Request Fuel</h6>
                                <p class="small">Use USSD simulator to request fuel</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-center">
                                <div class="demo-icon text-info">
                                    <i class="fas fa-3"></i>
                                </div>
                                <h6>Activate Fuel</h6>
                                <p class="small">Use agent portal to activate code</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-center">
                                <div class="demo-icon text-warning">
                                    <i class="fas fa-4"></i>
                                </div>
                                <h6>Monitor</h6>
                                <p class="small">Check dashboard for statistics</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technical Details -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="demo-card p-4">
                    <h5><i class="fas fa-code"></i> Technical Features</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success"></i> Real USSD menu simulation</li>
                        <li><i class="fas fa-check text-success"></i> Mobile money payment processing</li>
                        <li><i class="fas fa-check text-success"></i> SMS notification system</li>
                        <li><i class="fas fa-check text-success"></i> Time-based restrictions</li>
                        <li><i class="fas fa-check text-success"></i> Fuel station float management</li>
                        <li><i class="fas fa-check text-success"></i> Automated scheduling</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="demo-card p-4">
                    <h5><i class="fas fa-mobile-alt"></i> Demo Users</h5>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Phone</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>256700654321</code></td>
                                    <td>Demo Boda User</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td><code>256700111111</code></td>
                                    <td>Test User 1</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td><code>256700222222</code></td>
                                    <td>Test User 2</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-5">
            <p class="text-white">
                <i class="fas fa-info-circle"></i> 
                This is a demonstration system. All transactions are simulated for testing purposes.
            </p>
        </div>
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
