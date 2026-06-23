<?php
// Suppress warnings for cleaner output
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);

session_start();

// Initialize session data if not exists
if (!isset($_SESSION['ussd_session'])) {
    $_SESSION['ussd_session'] = [
        'phone' => '',
        'current_menu' => 'main',
        'user_data' => [],
        'session_active' => false
    ];
}

require_once 'utils/dbaccess.php';
require_once 'utils/PhoneHelper.php';
require_once 'controllers/FuelLoanController.php';
require_once 'controllers/LoanManagementController.php';

$db = new DbAccess();
$bodaUsers = $db->selectQuery(
    "SELECT bodaUserId, bodaUserName, bodaUserPhoneNumber, bodaUserStatus
     FROM bodauser
     ORDER BY bodaUserName ASC"
);
$defaultPhone = !empty($bodaUsers)
    ? PhoneHelper::toInternational($bodaUsers[0]['bodaUserPhoneNumber'])
    : '';

class USSDSimulator {
    private $db;
    private $fuelLoanController;
    private $loanController;

    public function __construct() {
        $this->db = new DbAccess();
        $this->fuelLoanController = new FuelLoanController();
        $this->loanController = new LoanManagementController();
    }

    public function handleRequest($input, $phone) {
        $_SESSION['ussd_session']['phone'] = $phone;
        $_SESSION['ussd_session']['session_active'] = true;

        $menu = $_SESSION['ussd_session']['current_menu'] ?? 'main';
        
        // Debug: Log the current menu state
        error_log("USSD Debug - Input: $input, Phone: $phone, Current Menu: $menu");
        
        switch ($menu) {
            case 'main':
                return $this->handleMainMenu($input);
            case 'fuel_request':
                return $this->handleFuelRequest($input);
            case 'payment':
                return $this->handlePayment($input);
            case 'balance':
                return $this->handleBalance($input);
            case 'help':
                return $this->handleHelp($input);
            default:
                return $this->showMainMenu();
        }
    }

    private function showMainMenu() {
        $_SESSION['ussd_session']['current_menu'] = 'main';
        
        return [
            'response' => "SUNFUEL\n" .
                         "1. Request Fuel\n" .
                         "2. Pay Loan\n" .
                         "3. Check Balance\n" .
                         "4. Help\n" .
                         "0. Exit",
            'endSession' => false
        ];
    }

    private function handleMainMenu($input) {
        // Handle USSD code input
        if ($input === '*123#' || $input === '*123') {
            return $this->showMainMenu();
        }
        
        switch ($input) {
            case '1':
                return $this->handleFuelRequestMenu();
            case '2':
                return $this->handlePaymentMenu();
            case '3':
                return $this->handleBalanceMenu();
            case '4':
                return $this->handleHelpMenu();
            case '0':
                return $this->endSession();
            default:
                return [
                    'response' => "Invalid option. Please try again.",
                    'endSession' => false
                ];
        }
    }

    private function handleFuelRequestMenu() {
        $phone = $_SESSION['ussd_session']['phone'];
        $canBorrow = $this->loanController->canUserBorrowToday($phone);
        
        if (!$canBorrow['canBorrow']) {
            return [
                'response' => $canBorrow['reason'],
                'endSession' => true
            ];
        }

        // Get user details
        $user = $this->getBodaUser($phone);
        if (!$user) {
            return [
                'response' => "User not found. Please contact support.",
                'endSession' => true
            ];
        }

        $_SESSION['ussd_session']['current_menu'] = 'fuel_request';
        $_SESSION['ussd_session']['user_data']['maxLoan'] = $user['maxDailyLoan'];
        
        // Debug: Log session state change
        error_log("USSD Debug - Setting current_menu to 'fuel_request'");
        
        return [
            'response' => "FUEL REQUEST\n" .
                         "Amount: " . number_format($user['maxDailyLoan']) . " UGX\n" .
                         "Interest: 5%\n" .
                         "Due: Today by midnight\n\n" .
                         "1. Confirm\n" .
                         "2. Cancel\n" .
                         "0. Back",
            'endSession' => false
        ];
    }

    private function handleFuelRequest($input) {
        switch ($input) {
            case '1':
                $phone = $_SESSION['ussd_session']['phone'];
                $result = $this->fuelLoanController->generateActivationCode($phone);
                
                if ($result['success']) {
                    return [
                        'response' => "✅ ACTIVATION CODE GENERATED!\n\n" .
                                     "🔑 CODE: " . $result['data']['activationCode'] . "\n\n" .
                                     "⏰ Valid for 2 hours (testing mode)\n" .
                                     "🏪 Visit: " . $result['data']['stationName'] . "\n\n" .
                                     "📱 Show this code to the fuel agent",
                        'endSession' => true
                    ];
                } else {
                    return [
                        'response' => $result['message'],
                        'endSession' => true
                    ];
                }
                
            case '2':
                return $this->showMainMenu();
            case '0':
                return $this->showMainMenu();
            default:
                return [
                    'response' => "Invalid option. Please try again.",
                    'endSession' => false
                ];
        }
    }

    private function handlePaymentMenu() {
        $phone = $_SESSION['ussd_session']['phone'];
        $user = $this->getBodaUser($phone);
        
        if (!$user) {
            return [
                'response' => "User not found. Please contact support.",
                'endSession' => true
            ];
        }

        $outstandingLoan = $this->loanController->getOutstandingLoan($user['bodaUserId']);
        
        if (!$outstandingLoan) {
            return [
                'response' => "No outstanding loans found.",
                'endSession' => true
            ];
        }

        $_SESSION['ussd_session']['current_menu'] = 'payment';
        $_SESSION['ussd_session']['user_data']['loanId'] = $outstandingLoan['fuelLoanId'];
        $_SESSION['ussd_session']['user_data']['totalAmount'] = $outstandingLoan['totalAmount'];
        
        return [
            'response' => "PAY LOAN\n" .
                         "Amount: " . number_format($outstandingLoan['totalAmount']) . " UGX\n" .
                         "Due: " . date('d/m/Y H:i', strtotime($outstandingLoan['dueDate'])) . "\n\n" .
                         "1. Pay Now\n" .
                         "2. Cancel\n" .
                         "0. Back",
            'endSession' => false
        ];
    }

    private function handlePayment($input) {
        try {
            switch ($input) {
                case '1':
                    $phone = $_SESSION['ussd_session']['phone'];
                    $loanId = $_SESSION['ussd_session']['user_data']['loanId'];
                    $amount = $_SESSION['ussd_session']['user_data']['totalAmount'];
                    
                    error_log("USSD Payment Debug - Processing payment for phone: $phone, amount: $amount");
                    $result = $this->loanController->processLoanPayment($phone, $amount);
                    error_log("USSD Payment Debug - Result: " . json_encode($result));
                    
                    if ($result['success']) {
                        return [
                            'response' => "Payment request sent.\n\n" .
                                         "Amount: " . number_format($amount) . " UGX\n" .
                                         "Approve the prompt on your phone.\n" .
                                         "Ref: " . $result['transactionId'],
                            'endSession' => true
                        ];
                    } else {
                        return [
                            'response' => "❌ Payment failed: " . $result['message'],
                            'endSession' => true
                        ];
                    }
                
                case '2':
                    return $this->showMainMenu();
                case '0':
                    return $this->showMainMenu();
                default:
                    return [
                        'response' => "Invalid option. Please try again.",
                        'endSession' => false
                    ];
            }
        } catch (Exception $e) {
            error_log("USSD Payment Debug - Exception: " . $e->getMessage());
            return [
                'response' => "System error. Please try again.",
                'endSession' => true
            ];
        }
    }

    private function handleBalanceMenu() {
        $phone = $_SESSION['ussd_session']['phone'];
        $user = $this->getBodaUser($phone);
        
        if (!$user) {
            return [
                'response' => "User not found. Please contact support.",
                'endSession' => true
            ];
        }

        $outstandingLoan = $this->loanController->getOutstandingLoan($user['bodaUserId']);
        $loanSummary = $this->loanController->getUserLoanSummary($phone);
        
        $response = "ACCOUNT BALANCE\n";
        $response .= "Name: " . $user['bodaUserName'] . "\n";
        $response .= "Stage: " . $user['stageName'] . "\n\n";
        
        if ($outstandingLoan) {
            $response .= "Outstanding: " . number_format($outstandingLoan['totalAmount']) . " UGX\n";
            $response .= "Due: " . date('d/m/Y', strtotime($outstandingLoan['dueDate'])) . "\n";
        } else {
            $response .= "No outstanding loans\n";
            $response .= "Status: Can borrow\n";
        }
        
        if ($loanSummary) {
            $response .= "\nTotal Loans: " . $loanSummary['totalLoans'] . "\n";
            $response .= "Total Borrowed: " . number_format($loanSummary['totalBorrowed']) . " UGX\n";
            $response .= "Total Paid: " . number_format($loanSummary['totalPaid']) . " UGX\n";
        }
        
        $response .= "\n0. Back";

        $_SESSION['ussd_session']['current_menu'] = 'balance';
        
        return [
            'response' => $response,
            'endSession' => false
        ];
    }

    private function handleBalance($input) {
        if ($input == '0') {
            return $this->showMainMenu();
        }
        return [
            'response' => "Invalid option. Please try again.",
            'endSession' => false
        ];
    }

    private function handleHelpMenu() {
        return [
            'response' => "SUNFUEL HELP\n\n" .
                         "1. Request Fuel: Get fuel on loan\n" .
                         "2. Pay Loan: Pay outstanding loan\n" .
                         "3. Check Balance: View account status\n\n" .
                         "Borrowing: 6 AM - 12 PM\n" .
                         "Payment: 5 PM - 12 AM\n\n" .
                         "Contact: 0800-SUNFUEL",
            'endSession' => true
        ];
    }

    private function endSession() {
        $_SESSION['ussd_session']['session_active'] = false;
        $_SESSION['ussd_session']['current_menu'] = 'main';
        
        return [
            'response' => "Thank you for using SunFuel!",
            'endSession' => true
        ];
    }

    private function getBodaUser($phone) {
        $phones = PhoneHelper::sqlInList(PhoneHelper::variants($phone));
        $sql = "SELECT b.*, s.stageName, fs.fuelStationName 
                FROM bodauser b 
                LEFT JOIN stage s ON b.stageId = s.stageId 
                LEFT JOIN fuelstation fs ON b.fuelStationId = fs.fuelStationId 
                WHERE b.bodaUserPhoneNumber IN ({$phones})
                AND b.bodaUserStatus = 1";

        $result = $this->db->selectQuery($sql);
        if (!empty($result)) {
            $user = $result[0];
            // Set default values for loan system
            $user['maxDailyLoan'] = 15000.00; // Default loan amount
            $user['packageId'] = 1; // Default package ID
            return $user;
        }
        return null;
    }
}

// Handle session reset first
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset_session') {
    session_destroy();
    session_start();
    $_SESSION['ussd_session'] = [
        'phone' => '',
        'current_menu' => 'main',
        'user_data' => [],
        'session_active' => false
    ];
    header('Content-Type: application/json');
    echo json_encode(['status' => 'reset']);
    exit;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Clean any previous output
    if (ob_get_level()) {
        ob_clean();
    }
    
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');
    
    try {
        $simulator = new USSDSimulator();
        $input = $_POST['input'] ?? '';
        $phone = $_POST['phone'] ?? $defaultPhone;
        
        $result = $simulator->handleRequest($input, $phone);
        
        // Ensure session is written before responding
        session_write_close();
        
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        echo json_encode([
            'response' => 'System error. Please try again.',
            'endSession' => true
        ]);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USSD Simulator - SunFuel</title>
    <link href="plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="plugins/fontawesome/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .ussd-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .ussd-header {
            background: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
        }
        .ussd-screen {
            background: #000;
            color: #00ff00;
            padding: 20px;
            min-height: 300px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.4;
            white-space: pre-wrap;
            overflow-y: auto;
            max-height: 400px;
        }
        .ussd-input {
            background: #333;
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            width: 100%;
            text-align: center;
            font-family: 'Courier New', monospace;
        }
        .ussd-input:focus {
            outline: none;
            background: #444;
        }
        .phone-selector {
            background: #34495e;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .demo-users {
            background: #ecf0f1;
            padding: 15px;
            border-bottom: 1px solid #bdc3c7;
        }
        .user-btn {
            margin: 2px;
            font-size: 12px;
        }
        .session-info {
            background: #3498db;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 12px;
        }
        .keypad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5px;
            padding: 15px;
            background: #ecf0f1;
        }
        .key {
            background: #95a5a6;
            border: none;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .key:hover {
            background: #7f8c8d;
        }
        .key.primary {
            background: #27ae60;
            color: white;
        }
        .key.primary:hover {
            background: #229954;
        }
        .key.danger {
            background: #e74c3c;
            color: white;
        }
        .key.danger:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="ussd-container">
        <!-- Header -->
        <div class="ussd-header">
            <i class="fas fa-mobile-alt"></i> USSD Simulator - SunFuel
        </div>

        <!-- Phone Selector -->
        <div class="phone-selector">
            <strong>Select Boda Rider:</strong>
            <div class="demo-users">
                <?php if (empty($bodaUsers)): ?>
                    <p class="text-muted mb-0 small">No boda users in the database.</p>
                <?php else: ?>
                    <?php foreach ($bodaUsers as $index => $user):
                        $phone = PhoneHelper::toInternational($user['bodaUserPhoneNumber']);
                        $statusLabel = (int) $user['bodaUserStatus'] === 1 ? 'Active' : 'Inactive';
                        $btnClass = $index === 0 ? 'btn-outline-primary' : 'btn-outline-secondary';
                    ?>
                        <button class="btn btn-sm <?= $btnClass ?> user-btn"
                                onclick="selectPhone('<?= htmlspecialchars($phone) ?>')">
                            <?= htmlspecialchars($user['bodaUserName']) ?><br>
                            <small><?= htmlspecialchars($phone) ?> (<?= $statusLabel ?>)</small>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="mt-2">
                <button class="btn btn-warning btn-sm" onclick="resetSession()">
                    <i class="fas fa-refresh"></i> Reset Session
                </button>
                <a href="agent_ussd_simulator.php" class="btn btn-success btn-sm">
                    <i class="fas fa-user-tie"></i> Agent USSD Simulator
                </a>
            </div>
        </div>

        <!-- Session Info -->
        <div class="session-info">
            <span id="sessionPhone">Phone: <?= htmlspecialchars($defaultPhone ?: 'N/A') ?></span> |
            <span id="sessionStatus">Session: Active</span>
        </div>

        <!-- USSD Screen -->
        <div class="ussd-screen" id="ussdScreen">
SUNFUEL
1. Request Fuel
2. Pay Loan
3. Check Balance
4. Help
0. Exit

Enter your choice:
        </div>

        <!-- Keypad -->
        <div class="keypad">
            <button class="key" onclick="sendInput('1')">1</button>
            <button class="key" onclick="sendInput('2')">2</button>
            <button class="key" onclick="sendInput('3')">3</button>
            <button class="key" onclick="sendInput('4')">4</button>
            <button class="key" onclick="sendInput('5')">5</button>
            <button class="key" onclick="sendInput('6')">6</button>
            <button class="key" onclick="sendInput('7')">7</button>
            <button class="key" onclick="sendInput('8')">8</button>
            <button class="key" onclick="sendInput('9')">9</button>
            <button class="key primary" onclick="sendInput('0')">0</button>
            <button class="key primary" onclick="sendInput('*')">*</button>
            <button class="key danger" onclick="sendInput('#')">#</button>
        </div>

        <!-- Manual Input -->
        <input type="text" 
               class="ussd-input" 
               id="manualInput" 
               placeholder="Type option or press keypad"
               onkeypress="handleKeyPress(event)">
    </div>

    <!-- Instructions -->
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5><i class="fas fa-info-circle"></i> USSD Simulator Instructions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>How to Use:</h6>
                                <ul>
                                    <li>Select a boda rider from the database</li>
                                    <li>Use the keypad or type numbers</li>
                                    <li>Follow the USSD menu prompts</li>
                                    <li>Test the complete fuel loan workflow</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Demo Features:</h6>
                                <ul>
                                    <li><strong>Request Fuel:</strong> Generate activation codes</li>
                                    <li><strong>Pay Loan:</strong> Process mobile money payments</li>
                                    <li><strong>Check Balance:</strong> View account status</li>
                                    <li><strong>Help:</strong> View system information</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <strong>Note:</strong> This simulator works with the actual fuel loan system.
                            Generated activation codes can be confirmed by the agent via
                            <a href="agent_ussd_simulator.php" class="alert-link">Agent USSD Simulator</a>
                            or the <a href="fuel_agent_login.php" class="alert-link">Fuel Agent Portal</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="plugins/jquery/jquery.min.js?v=<?php echo time(); ?>"></script>
    <script src="plugins/bootstrap/js/bootstrap.min.js?v=<?php echo time(); ?>"></script>
    
    <script>
        let currentPhone = <?= json_encode($defaultPhone) ?>;
        let sessionActive = true;

        function selectPhone(phone) {
            currentPhone = phone;
            document.getElementById('sessionPhone').textContent = 'Phone: ' + phone;
            
            // Reset session
            sessionActive = true;
            document.getElementById('sessionStatus').textContent = 'Session: Active';
            
            // Show main menu
            document.getElementById('ussdScreen').textContent = 
                'SUNFUEL\n' +
                '1. Request Fuel\n' +
                '2. Pay Loan\n' +
                '3. Check Balance\n' +
                '4. Help\n' +
                '0. Exit\n\n' +
                'Enter your choice:';
                
            // Update button styles
            document.querySelectorAll('.user-btn').forEach(btn => {
                btn.className = 'btn btn-sm btn-outline-secondary user-btn';
            });
            event.target.className = 'btn btn-sm btn-outline-primary user-btn';
        }

        function sendInput(input) {
            if (!sessionActive) {
                // Restart session
                sessionActive = true;
                document.getElementById('sessionStatus').textContent = 'Session: Active';
                selectPhone(currentPhone);
                return;
            }
            
            makeRequest(input);
        }

        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                const input = document.getElementById('manualInput').value.trim();
                if (input) {
                    sendInput(input);
                    document.getElementById('manualInput').value = '';
                }
            }
        }

        function makeRequest(input) {
            // Show loading
            const screen = document.getElementById('ussdScreen');
            const currentText = screen.textContent;
            screen.textContent = currentText + '\n\nProcessing...';
            
            // Disable input temporarily
            document.getElementById('manualInput').disabled = true;
            
            fetch('?v=' + Date.now(), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Cache-Control': 'no-cache',
                    'Pragma': 'no-cache'
                },
                body: 'action=ussd&input=' + encodeURIComponent(input) + '&phone=' + encodeURIComponent(currentPhone)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Invalid JSON response:', text);
                        throw new Error('Invalid response from server');
                    }
                });
            })
            .then(data => {
                // Update screen
                screen.textContent = data.response || 'No response received';
                
                // Update session status
                if (data.endSession) {
                    sessionActive = false;
                    document.getElementById('sessionStatus').textContent = 'Session: Ended';
                }
                
                // Re-enable input
                document.getElementById('manualInput').disabled = false;
                document.getElementById('manualInput').focus();
            })
            .catch(error => {
                console.error('Request error:', error);
                screen.textContent = 'Connection error. Please try again.';
                document.getElementById('manualInput').disabled = false;
            });
        }

        // Reset session function
        function resetSession() {
            fetch('?v=' + Date.now(), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Cache-Control': 'no-cache',
                    'Pragma': 'no-cache'
                },
                body: 'action=reset_session'
            }).then(() => {
                // Reset UI state
                sessionActive = true;
                document.getElementById('sessionStatus').textContent = 'Session: Active';
                document.getElementById('ussdScreen').textContent = 'Welcome to SunFuel USSD\n\nDial *123# to start';
                document.getElementById('manualInput').value = '';
                document.getElementById('manualInput').disabled = false;
                document.getElementById('manualInput').focus();
            });
        }

        // Auto-focus on input
        document.getElementById('manualInput').focus();
    </script>
</body>
</html>
