<?php
require_once '../../utils/dbaccess.php';
require_once '../../controllers/FuelLoanController.php';

// Check if user is logged in as fuel agent
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'agent') {
    header('Location: ../../login.php');
    exit();
}

$fuelLoanController = new FuelLoanController();
$message = '';
$messageType = '';

// Debug: Log all POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    error_log("Fuel Agent Activation - POST received: " . json_encode($_POST));
}

// Handle activation code lookup and confirmation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['activation_code'])) {
            // Step 1: Look up activation code details
    $activationCode = trim($_POST['activation_code']);
    $agentId = $_SESSION['user_id'];
    
            // Debug: Log the lookup attempt
            error_log("Fuel Agent Activation - Code Lookup: $activationCode, Agent ID: $agentId");
            
            $result = $fuelLoanController->getActivationCodeDetails($activationCode);
            
            // Debug: Log the result
            error_log("Fuel Agent Activation Lookup Result: " . json_encode($result));
            
            if ($result['success']) {
                // Store the activation details in session for confirmation
                $_SESSION['pending_activation'] = $result['data'];
                $message = "Activation code found! Please review details below and confirm.";
                $messageType = 'info';
            } else {
                $message = $result['message'];
                $messageType = 'error';
            }
        } elseif (isset($_POST['confirm_activation'])) {
            // Step 2: Confirm and activate
            if (isset($_SESSION['pending_activation'])) {
                $activationData = $_SESSION['pending_activation'];
                $agentId = $_SESSION['user_id'];
                
                // Debug: Log the confirmation attempt
                error_log("Fuel Agent Activation - Confirmation: " . json_encode($activationData));
                
                $result = $fuelLoanController->activateFuelLoan($activationData['activationCode'], $agentId);
                
                // Debug: Log the result
                error_log("Fuel Agent Activation Result: " . json_encode($result));
    
    if ($result['success']) {
                    $message = "✅ Fuel loan activated successfully! User: " . $activationData['userName'] . 
                              " - Amount: " . number_format($activationData['totalAmount']) . " UGX";
        $messageType = 'success';
                    // Clear pending activation
                    unset($_SESSION['pending_activation']);
    } else {
        $message = $result['message'];
                    $messageType = 'error';
                }
            } else {
                $message = "No pending activation found. Please enter activation code again.";
                $messageType = 'error';
            }
        } elseif (isset($_POST['cancel_activation'])) {
            // Cancel activation
            unset($_SESSION['pending_activation']);
            $message = "Activation cancelled.";
            $messageType = 'info';
        }
    } catch (Exception $e) {
        error_log("Fuel Agent Activation Error: " . $e->getMessage());
        $message = "System error: " . $e->getMessage();
        $messageType = 'error';
    }
}

// Get agent's fuel station info
$agentId = $_SESSION['user_id'];
$db = new DbAccess();
$agentInfo = $db->selectQuery("SELECT fa.*, fs.fuelStationName, fs.fuelStationLocation, fs.currentFloat, fs.minFloat
                               FROM fuelagent fa 
                               LEFT JOIN fuelstation fs ON fa.stationId = fs.fuelStationId 
                               WHERE fa.fuelAgentId = {$agentId}");

$station = !empty($agentInfo) ? $agentInfo[0] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>SunFuel Agent</title>
    <link href="../../plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../plugins/fontawesome/css/all.min.css" rel="stylesheet">
    <link rel="manifest" href="../../manifest.json">
    <meta name="theme-color" content="#1a1a2e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SunFuel Agent">
    <style>
        /* Modern Mobile App Design */
        * {
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            font-size: 16px;
            line-height: 1.5;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            color: #ffffff;
            overflow-x: hidden;
        }
        
        /* iOS-style status bar */
        .status-bar {
            height: 44px;
            background: rgba(0,0,0,0.8);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            font-size: 14px;
            font-weight: 600;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        
        .status-left {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .status-right {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .battery {
            width: 24px;
            height: 12px;
            border: 1px solid #fff;
            border-radius: 2px;
            position: relative;
        }
        
        .battery::after {
            content: '';
            position: absolute;
            right: -3px;
            top: 3px;
            width: 2px;
            height: 6px;
            background: #fff;
            border-radius: 0 1px 1px 0;
        }
        
        .battery-fill {
            width: 80%;
            height: 100%;
            background: #4CAF50;
            border-radius: 1px;
        }
        
        /* Main container */
        .app-container {
            padding-top: 44px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* App header */
        .app-header {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 20px;
            text-align: center;
        }
        
        .app-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            background: linear-gradient(45deg, #00d4ff, #00ff88);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .app-subtitle {
            font-size: 14px;
            opacity: 0.8;
            margin: 5px 0 0 0;
        }
        
        /* Station info card */
        .station-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            margin: 20px;
            padding: 20px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .station-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .float-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(0,255,136,0.1);
            border-radius: 12px;
            padding: 15px;
            border: 1px solid rgba(0,255,136,0.2);
        }
        
        .float-amount {
            font-size: 20px;
            font-weight: 700;
            color: #00ff88;
        }
        
        .float-label {
            font-size: 12px;
            opacity: 0.8;
        }
        
        /* Activation form */
        .activation-container {
            flex: 1;
            padding: 20px;
        }
        
        .activation-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .activation-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .activation-subtitle {
            font-size: 14px;
            opacity: 0.8;
            margin-bottom: 30px;
        }
        
        .code-input-container {
            position: relative;
            margin-bottom: 30px;
        }
        
        .code-input {
            width: 100%;
            height: 60px;
            font-size: 24px;
            text-align: center;
            letter-spacing: 8px;
            background: rgba(255,255,255,0.1);
            border: 2px solid rgba(255,255,255,0.2);
            border-radius: 15px;
            color: #fff;
            padding: 0 20px;
            transition: all 0.3s ease;
        }
        
        .code-input:focus {
            outline: none;
            border-color: #00ff88;
            background: rgba(0,255,136,0.1);
            box-shadow: 0 0 20px rgba(0,255,136,0.3);
        }
        
        .code-input::placeholder {
            color: rgba(255,255,255,0.5);
            letter-spacing: 3px;
        }
        
        .activate-btn {
            width: 100%;
            height: 60px;
            background: linear-gradient(45deg, #00d4ff, #00ff88);
            border: none;
            border-radius: 15px;
            color: #000;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,255,136,0.3);
        }
        
        .activate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,255,136,0.4);
        }
        
        .activate-btn:active {
            transform: translateY(0);
        }
        
        /* Testing mode badge */
        .testing-badge {
            background: rgba(255,193,7,0.2);
            border: 1px solid rgba(255,193,7,0.3);
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 600;
            color: #ffc107;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        /* Recent activations */
        .recent-section {
            margin-top: 30px;
        }
        
        .recent-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            opacity: 0.9;
        }
        
        .recent-item {
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .recent-code {
            font-size: 18px;
            font-weight: 700;
            color: #00ff88;
            margin-bottom: 5px;
        }
        
        .recent-details {
            font-size: 12px;
            opacity: 0.8;
            display: flex;
            justify-content: space-between;
        }
        
           /* Alert messages */
           .alert {
               border-radius: 12px;
               border: none;
               margin-bottom: 20px;
           }

           .alert-success {
               background: rgba(0,255,136,0.1);
               border: 1px solid rgba(0,255,136,0.2);
               color: #00ff88;
           }

           .alert-danger {
               background: rgba(255,59,48,0.1);
               border: 1px solid rgba(255,59,48,0.2);
               color: #ff3b30;
           }

           .alert-info {
               background: rgba(0,212,255,0.1);
               border: 1px solid rgba(0,212,255,0.2);
               color: #00d4ff;
           }

           /* Confirmation form styles */
           .confirmation-details {
               text-align: left;
           }

           .customer-info, .amount-breakdown {
               background: rgba(255,255,255,0.05);
               border-radius: 15px;
               padding: 20px;
               margin-bottom: 20px;
               border: 1px solid rgba(255,255,255,0.1);
           }

           .customer-info h4, .amount-breakdown h4 {
               font-size: 16px;
               font-weight: 600;
               margin-bottom: 15px;
               color: #00ff88;
           }

           .info-row {
               display: flex;
               justify-content: space-between;
               align-items: center;
               padding: 8px 0;
               border-bottom: 1px solid rgba(255,255,255,0.1);
           }

           .info-row:last-child {
               border-bottom: none;
           }

           .info-row .label {
               font-size: 14px;
               opacity: 0.8;
           }

           .info-row .value {
               font-size: 14px;
               font-weight: 600;
               color: #fff;
           }

           .code-display {
               background: rgba(0,255,136,0.2);
               padding: 4px 8px;
               border-radius: 6px;
               font-family: monospace;
               letter-spacing: 2px;
           }

           .total-row {
               background: rgba(0,255,136,0.1);
               border-radius: 8px;
               padding: 12px;
               margin-top: 10px;
               border: 1px solid rgba(0,255,136,0.2);
           }

           .total-amount {
               font-size: 18px;
               color: #00ff88;
           }

           .confirmation-actions {
               text-align: center;
               margin-top: 30px;
           }

           .confirm-btn, .cancel-btn {
               padding: 15px 30px;
               border: none;
               border-radius: 12px;
               font-size: 16px;
               font-weight: 600;
               cursor: pointer;
               transition: all 0.3s ease;
               min-width: 140px;
           }

           .confirm-btn {
               background: linear-gradient(45deg, #00d4ff, #00ff88);
               color: #000;
               box-shadow: 0 4px 15px rgba(0,255,136,0.3);
           }

           .confirm-btn:hover {
               transform: translateY(-2px);
               box-shadow: 0 6px 20px rgba(0,255,136,0.4);
           }

           .cancel-btn {
               background: rgba(255,59,48,0.2);
               color: #ff3b30;
               border: 1px solid rgba(255,59,48,0.3);
           }

           .cancel-btn:hover {
               background: rgba(255,59,48,0.3);
               transform: translateY(-2px);
           }
        
        /* Responsive adjustments */
        @media (max-width: 480px) {
            .app-header {
                padding: 15px;
            }
            
            .app-title {
                font-size: 20px;
            }
            
            .station-card, .activation-container {
                margin: 15px;
                padding: 20px;
            }
            
            .code-input {
                height: 50px;
                font-size: 20px;
                letter-spacing: 6px;
            }
            
            .activate-btn {
                height: 50px;
                font-size: 16px;
            }
        }
        
    </style>
</head>
<body>
    <!-- iOS-style status bar -->
    <div class="status-bar">
        <div class="status-left">
            <span>9:41</span>
        </div>
        <div class="status-right">
            <span>📶</span>
            <span>📶</span>
            <span>🔋</span>
            <div class="battery">
                <div class="battery-fill"></div>
            </div>
        </div>
    </div>

    <div class="app-container">
        <!-- App Header -->
        <div class="app-header">
            <h1 class="app-title">SunFuel Agent</h1>
            <p class="app-subtitle">Fuel Activation Portal</p>
        </div>

        <!-- Station Info Card -->
        <div class="station-card">
            <div class="station-name">
                <i class="fas fa-gas-pump"></i>
                <?php echo htmlspecialchars($station['fuelStationName']); ?>
            </div>
            <div class="float-info">
                <div>
                    <div class="float-amount"><?php echo number_format($station['currentFloat'] ?? 500000); ?> UGX</div>
                    <div class="float-label">Available Float</div>
                </div>
                <div>
                    <i class="fas fa-wallet" style="font-size: 24px; opacity: 0.6;"></i>
                </div>
            </div>
        </div>

        <!-- Activation Container -->
        <div class="activation-container">
            <div class="activation-card">
                <div class="testing-badge">
                    <i class="fas fa-flask"></i> Testing Mode
                </div>
                
                <h2 class="activation-title">Fuel Activation</h2>
                <p class="activation-subtitle">Enter the 6-digit code provided by the customer</p>

                <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                    <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
                <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

                <?php if (isset($_SESSION['pending_activation'])): ?>
                    <!-- Confirmation Form -->
                    <div class="confirmation-details">
                        <div class="customer-info">
                            <h4><i class="fas fa-user"></i> Customer Details</h4>
                            <div class="info-row">
                                <span class="label">Name:</span>
                                <span class="value"><?php echo htmlspecialchars($_SESSION['pending_activation']['userName']); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="label">Phone:</span>
                                <span class="value"><?php echo htmlspecialchars($_SESSION['pending_activation']['userPhone']); ?></span>
                            </div>
                            <div class="info-row">
                                <span class="label">Code:</span>
                                <span class="value code-display"><?php echo htmlspecialchars($_SESSION['pending_activation']['activationCode']); ?></span>
                    </div>
                        </div>

                        <div class="amount-breakdown">
                            <h4><i class="fas fa-calculator"></i> Amount Breakdown</h4>
                            <div class="info-row">
                                <span class="label">Fuel Amount:</span>
                                <span class="value"><?php echo number_format($_SESSION['pending_activation']['fuelAmount']); ?> UGX</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Interest (<?php echo $_SESSION['pending_activation']['interestRate']; ?>%):</span>
                                <span class="value"><?php echo number_format($_SESSION['pending_activation']['interestAmount']); ?> UGX</span>
                    </div>
                            <div class="info-row total-row">
                                <span class="label"><strong>Total Amount:</strong></span>
                                <span class="value total-amount"><?php echo number_format($_SESSION['pending_activation']['totalAmount']); ?> UGX</span>
                </div>
            </div>

                        <div class="confirmation-actions">
                            <form method="POST" style="display: inline;">
                                <button type="submit" name="confirm_activation" class="confirm-btn">
                                    <i class="fas fa-check-circle"></i> Confirm & Activate
                                </button>
                            </form>
                            <form method="POST" style="display: inline; margin-left: 10px;">
                                <button type="submit" name="cancel_activation" class="cancel-btn">
                                    <i class="fas fa-times-circle"></i> Cancel
                                </button>
                            </form>
                </div>
            </div>
                <?php else: ?>
                    <!-- Code Input Form -->
                <form method="POST" id="activationForm">
                        <div class="code-input-container">
                        <input type="text" 
                                   class="code-input" 
                               id="activation_code" 
                               name="activation_code" 
                               placeholder="000000" 
                               maxlength="6" 
                               pattern="[0-9]{6}"
                               required 
                               autocomplete="off"
                                   autofocus
                                   inputmode="numeric">
                    </div>
                    
                        <button type="submit" class="activate-btn">
                            <i class="fas fa-search"></i> Look Up Code
                    </button>
                </form>
                <?php endif; ?>

                <!-- Recent Activations -->
                <div class="recent-section">
                    <h3 class="recent-title">Recent Activations</h3>
                    <div id="recentActivations">
                        <div class="recent-item">
                            <div class="recent-code">Loading...</div>
                            <div class="recent-details">
                                <span>Fetching recent data</span>
                                <span>⏳</span>
                </div>
            </div>
        </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Auto-format activation code input
            $('#activation_code').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Auto-submit form when 6 digits are entered (only if no pending activation)
            <?php if (!isset($_SESSION['pending_activation'])): ?>
            $('#activation_code').on('input', function() {
                if (this.value.length === 6) {
                    console.log('Auto-submitting form with code:', this.value);
                    $('#activationForm').submit();
                }
            });
            <?php endif; ?>
            
            // Manual form submission handler
            $('#activationForm').on('submit', function(e) {
                console.log('Form submitted with code:', $('#activation_code').val());
                // Don't prevent default - let it submit normally
            });

            // Load recent activations
            loadRecentActivations();

            // Auto-refresh every 30 seconds
            setInterval(loadRecentActivations, 30000);
        });

        function loadRecentActivations() {
            $.ajax({
                url: '../../api/get_recent_activations.php',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.success && data.activations.length > 0) {
                        let html = '';
                        data.activations.forEach(function(activation) {
                            html += '<div class="recent-item">';
                            html += '<div class="recent-code">' + activation.code + '</div>';
                            html += '<div class="recent-details">';
                            html += '<span>' + activation.customer + '</span>';
                            html += '<span>' + activation.amount + '</span>';
                            html += '</div>';
                            html += '</div>';
                        });
                        $('#recentActivations').html(html);
                    } else {
                        $('#recentActivations').html(
                            '<div class="recent-item"><div class="recent-code">No recent activations</div></div>'
                        );
                    }
                },
                error: function() {
                    $('#recentActivations').html(
                        '<div class="recent-item"><div class="recent-code">Error loading data</div></div>'
                    );
                }
            });
        }

        // Auto-focus on activation code input
        setInterval(function() {
            if (document.activeElement.id !== 'activation_code' && window.innerWidth > 768) {
                $('#activation_code').focus();
            }
        }, 10000);
        
        // Mobile-specific enhancements
        if (window.innerWidth <= 768) {
            // Prevent zoom on input focus
            $('input').on('focus', function() {
                $('meta[name=viewport]').attr('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
            });
            // Restore zoom capability when input loses focus
            $('input').on('blur', function() {
                $('meta[name=viewport]').attr('content', 'width=device-width, initial-scale=1.0, user-scalable=no');
            });
            // Add haptic feedback simulation (if supported)
            $('.activate-btn').on('click', function() {
                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
            });
        }
    </script>
</body>
</html>
