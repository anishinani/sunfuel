<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once '../utils/dbaccess.php';
require_once '../utils/sms.php';
require_once '../controllers/FuelLoanController.php';

class USSDHandler extends DbAccess {
    private $phoneNumber;
    private $sessionId;
    private $message;
    private $fuelLoanController;
    private $sms;

    public function __construct() {
        parent::__construct();
        $this->fuelLoanController = new FuelLoanController();
        $this->sms = new infobip();
        
        // Get USSD parameters
        $this->phoneNumber = $_POST['phoneNumber'] ?? '';
        $this->sessionId = $_POST['sessionId'] ?? '';
        $this->message = $_POST['message'] ?? '';
        
        // Clean phone number
        $this->phoneNumber = $this->cleanPhoneNumber($this->phoneNumber);
    }

    private function cleanPhoneNumber($phone) {
        // Remove any non-numeric characters except +
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Convert to international format
        if (strpos($phone, '+256') === 0) {
            return substr($phone, 1); // Remove + for storage
        } elseif (strpos($phone, '256') === 0) {
            return $phone;
        } elseif (strpos($phone, '0') === 0 && strlen($phone) == 10) {
            return '256' . substr($phone, 1);
        } elseif (strlen($phone) == 9) {
            return '256' . $phone;
        }
        
        return $phone;
    }

    public function handleRequest() {
        try {
            // Get or create USSD session
            $session = $this->getOrCreateSession();
            
            // Parse user input
            $input = trim($this->message);
            $menu = $session['currentMenu'];
            
            // Route to appropriate handler
            switch ($menu) {
                case 'main':
                    return $this->handleMainMenu($input);
                case 'fuel_request':
                    return $this->handleFuelRequest($input);
                case 'payment':
                    return $this->handlePayment($input);
                case 'balance':
                    return $this->handleBalanceCheck($input);
                default:
                    return $this->showMainMenu();
            }
        } catch (Exception $e) {
            return $this->sendErrorResponse("System error. Please try again later.");
        }
    }

    private function getOrCreateSession() {
        // Check for existing active session
        $existingSession = $this->select(
            'ussd_sessions',
            ['*'],
            [
                'phoneNumber' => $this->phoneNumber,
                'sessionCode' => $this->sessionId,
                'status' => 'active'
            ]
        );

        if (!empty($existingSession)) {
            return $existingSession[0];
        }

        // Create new session
        $sessionData = [
            'phoneNumber' => $this->phoneNumber,
            'sessionCode' => $this->sessionId,
            'currentMenu' => 'main',
            'expiresAt' => date('Y-m-d H:i:s', strtotime('+5 minutes'))
        ];

        $this->insert('ussd_sessions', $sessionData);
        return $sessionData;
    }

    private function updateSession($menu, $userData = null) {
        $updateData = ['currentMenu' => $menu];
        if ($userData) {
            $updateData['userData'] = json_encode($userData);
        }

        $this->update(
            'ussd_sessions',
            $updateData,
            [
                'phoneNumber' => $this->phoneNumber,
                'sessionCode' => $this->sessionId
            ]
        );
    }

    private function showMainMenu() {
        $this->updateSession('main');
        
        $menu = "SUNFUEL\n";
        $menu .= "1. Request Fuel\n";
        $menu .= "2. Pay Loan\n";
        $menu .= "3. Check Balance\n";
        $menu .= "4. Help\n";
        $menu .= "0. Exit";

        return $this->sendResponse($menu, false);
    }

    private function handleMainMenu($input) {
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
                return $this->sendResponse("Invalid option. Please try again.", false);
        }
    }

    private function handleFuelRequestMenu() {
        // Check if user can borrow today
        $user = $this->getBodaUser();
        if (!$user) {
            return $this->sendResponse("User not found. Please contact support.", true);
        }

        // Check time restrictions
        if (!$this->canBorrowNow()) {
            $startTime = $this->getBorrowStartTime();
            $endTime = $this->getBorrowEndTime();
            return $this->sendResponse("Fuel requests allowed from {$startTime} to {$endTime}. Current time: " . date('H:i'), true);
        }

        // Check if user has outstanding loan
        if (!$user['canBorrowToday']) {
            return $this->sendResponse("You have an outstanding loan. Please pay first to borrow again.", true);
        }

        // Check if user already borrowed today
        if ($user['lastLoanDate'] == date('Y-m-d')) {
            return $this->sendResponse("You have already borrowed fuel today. One loan per day allowed.", true);
        }

        $this->updateSession('fuel_request', ['step' => 'confirm']);
        
        $menu = "FUEL REQUEST\n";
        $menu .= "Amount: " . number_format($user['maxDailyLoan']) . " UGX\n";
        $menu .= "Interest: 5%\n";
        $menu .= "Due: Today by midnight\n\n";
        $menu .= "1. Confirm\n";
        $menu .= "2. Cancel\n";
        $menu .= "0. Back";

        return $this->sendResponse($menu, false);
    }

    private function handleFuelRequest($input) {
        $session = $this->getOrCreateSession();
        $userData = $session['userData'] ? json_decode($session['userData'], true) : [];

        switch ($input) {
            case '1':
                // Generate activation code
                $result = $this->fuelLoanController->generateActivationCode($this->phoneNumber);
                
                if ($result['success']) {
                    // Send SMS with activation code
                    $this->sendActivationCodeSMS($result['data']);
                    
                    $response = "Activation code sent to your phone.\n";
                    $response .= "Code: " . $result['data']['activationCode'] . "\n";
                    $response .= "Valid for 30 minutes.\n";
                    $response .= "Visit your assigned fuel station.";
                    
                    return $this->sendResponse($response, true);
                } else {
                    return $this->sendResponse($result['message'], true);
                }
                
            case '2':
                return $this->showMainMenu();
            case '0':
                return $this->showMainMenu();
            default:
                return $this->sendResponse("Invalid option. Please try again.", false);
        }
    }

    private function handlePaymentMenu() {
        $user = $this->getBodaUser();
        if (!$user) {
            return $this->sendResponse("User not found. Please contact support.", true);
        }

        $outstandingLoan = $this->getOutstandingLoan($user['bodaUserId']);
        if (!$outstandingLoan) {
            return $this->sendResponse("No outstanding loans found.", true);
        }

        $this->updateSession('payment', ['loanId' => $outstandingLoan['fuelLoanId']]);
        
        $menu = "PAY LOAN\n";
        $menu .= "Amount: " . number_format($outstandingLoan['totalAmount']) . " UGX\n";
        $menu .= "Due: " . date('d/m/Y H:i', strtotime($outstandingLoan['dueDate'])) . "\n\n";
        $menu .= "1. Pay Now\n";
        $menu .= "2. Cancel\n";
        $menu .= "0. Back";

        return $this->sendResponse($menu, false);
    }

    private function handlePayment($input) {
        $session = $this->getOrCreateSession();
        $userData = $session['userData'] ? json_decode($session['userData'], true) : [];

        switch ($input) {
            case '1':
                // Process payment via mobile money
                $result = $this->fuelLoanController->processMobileMoneyPayment(
                    $this->phoneNumber,
                    $userData['loanId']
                );
                
                if ($result['success']) {
                    $response = "Payment successful!\n";
                    $response .= "Amount: " . number_format($result['amount']) . " UGX\n";
                    $response .= "You can now borrow fuel tomorrow.";
                    
                    return $this->sendResponse($response, true);
                } else {
                    return $this->sendResponse($result['message'], true);
                }
                
            case '2':
                return $this->showMainMenu();
            case '0':
                return $this->showMainMenu();
            default:
                return $this->sendResponse("Invalid option. Please try again.", false);
        }
    }

    private function handleBalanceMenu() {
        $user = $this->getBodaUser();
        if (!$user) {
            return $this->sendResponse("User not found. Please contact support.", true);
        }

        $outstandingLoan = $this->getOutstandingLoan($user['bodaUserId']);
        
        $menu = "ACCOUNT BALANCE\n";
        $menu .= "Name: " . $user['bodaUserName'] . "\n";
        $menu .= "Stage: " . $user['stageName'] . "\n";
        
        if ($outstandingLoan) {
            $menu .= "Outstanding: " . number_format($outstandingLoan['totalAmount']) . " UGX\n";
            $menu .= "Due: " . date('d/m/Y', strtotime($outstandingLoan['dueDate'])) . "\n";
        } else {
            $menu .= "No outstanding loans\n";
            $menu .= "Status: Can borrow\n";
        }
        
        $menu .= "\n0. Back";

        return $this->sendResponse($menu, false);
    }

    private function handleBalanceCheck($input) {
        if ($input == '0') {
            return $this->showMainMenu();
        }
        return $this->sendResponse("Invalid option. Please try again.", false);
    }

    private function handleHelpMenu() {
        $help = "SUNFUEL HELP\n\n";
        $help .= "1. Request Fuel: Get fuel on loan\n";
        $help .= "2. Pay Loan: Pay outstanding loan\n";
        $help .= "3. Check Balance: View account status\n\n";
        $help .= "Borrowing: 6 AM - 12 PM\n";
        $help .= "Payment: 5 PM - 12 AM\n\n";
        $help .= "Contact: 0800-SUNFUEL";

        return $this->sendResponse($help, true);
    }

    private function endSession() {
        $this->update('ussd_sessions', ['status' => 'completed'], [
            'phoneNumber' => $this->phoneNumber,
            'sessionCode' => $this->sessionId
        ]);
        
        return $this->sendResponse("Thank you for using SunFuel!", true);
    }

    private function getBodaUser() {
        $sql = "SELECT b.*, s.stageName, p.maxLoanAmount, p.interestRate 
                FROM bodauser b 
                LEFT JOIN stage s ON b.stageId = s.stageId 
                LEFT JOIN package p ON b.packageId = p.packageId 
                WHERE b.bodaUserPhoneNumber = '{$this->phoneNumber}' 
                AND b.bodaUserStatus = 1";
        
        $result = $this->selectQuery($sql);
        return !empty($result) ? $result[0] : null;
    }

    private function getOutstandingLoan($bodaUserId) {
        $sql = "SELECT * FROM fuel_loans 
                WHERE bodaUserId = {$bodaUserId} 
                AND status = 'active' 
                AND dueDate >= CURDATE() 
                ORDER BY createdAt DESC 
                LIMIT 1";
        
        $result = $this->selectQuery($sql);
        return !empty($result) ? $result[0] : null;
    }

    private function canBorrowNow() {
        $currentTime = date('H:i:s');
        $startTime = $this->getBorrowStartTime();
        $endTime = $this->getBorrowEndTime();
        
        return $currentTime >= $startTime && $currentTime <= $endTime;
    }

    private function getBorrowStartTime() {
        $result = $this->selectQuery("SELECT borrowStartTime FROM package LIMIT 1");
        return !empty($result) ? $result[0]['borrowStartTime'] : '06:00:00';
    }

    private function getBorrowEndTime() {
        $result = $this->selectQuery("SELECT borrowEndTime FROM package LIMIT 1");
        return !empty($result) ? $result[0]['borrowEndTime'] : '12:00:00';
    }

    private function sendActivationCodeSMS($data) {
        $message = "Your fuel activation code is {$data['activationCode']}. ";
        $message .= "Valid for 30 minutes. ";
        $message .= "Visit {$data['stationName']} to collect fuel.";
        
        $this->sms->sendsms('SUNFUEL', $this->phoneNumber, $message);
        
        // Log SMS
        $this->insert('sms_logs', [
            'phoneNumber' => $this->phoneNumber,
            'message' => $message,
            'messageType' => 'activation_code',
            'referenceId' => $data['activationId'],
            'referenceType' => 'activation'
        ]);
    }

    private function sendResponse($message, $endSession = false) {
        $response = [
            'response' => $message,
            'endSession' => $endSession
        ];
        
        if ($endSession) {
            $this->update('ussd_sessions', ['status' => 'completed'], [
                'phoneNumber' => $this->phoneNumber,
                'sessionCode' => $this->sessionId
            ]);
        }
        
        return json_encode($response);
    }

    private function sendErrorResponse($message) {
        return json_encode([
            'response' => $message,
            'endSession' => true
        ]);
    }
}

// Handle the USSD request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ussdHandler = new USSDHandler();
    echo $ussdHandler->handleRequest();
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
