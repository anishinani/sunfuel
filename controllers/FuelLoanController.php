<?php

require_once __DIR__ . '/../utils/dbaccess.php';
require_once __DIR__ . '/../utils/sms.php';

class FuelLoanController extends DbAccess {
    private $sms;

    public function __construct() {
        parent::__construct();
        $this->sms = new infobip();
    }

    /**
     * Generate fuel activation code for a boda user
     */
    public function generateActivationCode($phoneNumber) {
        try {
            // Get user details
            $user = $this->getBodaUserByPhone($phoneNumber);
            if (!$user) {
                return ['success' => false, 'message' => 'User not found or not activated'];
            }

            // Check if user can borrow today (relaxed for testing)
            if (!$this->canUserBorrowToday($user)) {
                return ['success' => false, 'message' => 'Cannot borrow today. Check outstanding loans'];
            }

            // Time restrictions disabled for testing
            // if (!$this->isWithinBorrowingHours()) {
            //     return ['success' => false, 'message' => 'Fuel requests only allowed between 6 AM and 12 PM'];
            // }

            // Generate unique activation code
            $activationCode = $this->generateUniqueCode();
            $expiresAt = date('Y-m-d H:i:s', strtotime('+2 hours')); // Extended for testing

            // Insert activation code
            $activationId = $this->insert('fuel_activation_codes', [
                'bodaUserId' => $user['bodaUserId'],
                'activationCode' => $activationCode,
                'fuelAmount' => $user['maxDailyLoan'],
                'packageId' => $user['packageId'],
                'fuelStationId' => $user['fuelStationId'],
                'stageId' => $user['stageId'],
                'status' => 'pending',
                'expiresAt' => $expiresAt
            ]);

            if ($activationId) {
                // Update user's last loan date and borrowing status
                $this->update('bodauser', [
                    'lastLoanDate' => date('Y-m-d'),
                    'canBorrowToday' => 0
                ], ['bodaUserId' => $user['bodaUserId']]);

                return [
                    'success' => true,
                    'data' => [
                        'activationId' => $activationId,
                        'activationCode' => $activationCode,
                        'amount' => $user['maxDailyLoan'],
                        'stationName' => $user['fuelStationName'],
                        'expiresAt' => $expiresAt
                    ]
                ];
            } else {
                return ['success' => false, 'message' => 'Failed to generate activation code'];
            }

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'System error: ' . $e->getMessage()];
        }
    }

    /**
     * Get activation code details for confirmation
     */
    public function getActivationCodeDetails($activationCode) {
        try {
            // Get activation code details
            $activation = $this->getActivationCode($activationCode);
            if (!$activation) {
                return ['success' => false, 'message' => 'Invalid activation code'];
            }

            // Check if code is expired
            if (strtotime($activation['expiresAt']) < time()) {
                return ['success' => false, 'message' => 'Activation code has expired'];
            }

            // Check if code is already used
            if ($activation['status'] != 'pending') {
                return ['success' => false, 'message' => 'Activation code already used'];
            }

            // Get user details
            $user = $this->getBodaUserById($activation['bodaUserId']);
            if (!$user) {
                return ['success' => false, 'message' => 'User not found'];
            }

            // Get fuel station details
            $station = $this->getFuelStation($activation['fuelStationId']);
            if (!$station) {
                return ['success' => false, 'message' => 'Fuel station not found'];
            }

            // Calculate interest
            $interestRate = $this->getPackageInterestRate($activation['packageId']);
            $interestAmount = ($activation['fuelAmount'] * $interestRate) / 100;
            $totalAmount = $activation['fuelAmount'] + $interestAmount;

            return [
                'success' => true,
                'data' => [
                    'activationId' => $activation['activationId'],
                    'activationCode' => $activation['activationCode'],
                    'userName' => $user['bodaUserName'],
                    'userPhone' => $user['bodaUserPhoneNumber'],
                    'fuelAmount' => $activation['fuelAmount'],
                    'interestRate' => $interestRate,
                    'interestAmount' => $interestAmount,
                    'totalAmount' => $totalAmount,
                    'stationName' => $station['fuelStationName'],
                    'expiresAt' => $activation['expiresAt'],
                    'createdAt' => $activation['createdAt']
                ]
            ];
        } catch (Exception $e) {
            error_log("Get Activation Code Details Error: " . $e->getMessage());
            return ['success' => false, 'message' => 'System error: ' . $e->getMessage()];
        }
    }

    /**
     * Activate fuel loan using activation code
     */
    public function activateFuelLoan($activationCode, $agentId) {
        try {
            // Get activation code details
            $activation = $this->getActivationCode($activationCode);
            if (!$activation) {
                return ['success' => false, 'message' => 'Invalid activation code'];
            }

            // Check if code is expired
            if (strtotime($activation['expiresAt']) < time()) {
                $this->update('fuel_activation_codes', ['status' => 'expired'], ['activationId' => $activation['activationId']]);
                return ['success' => false, 'message' => 'Activation code has expired'];
            }

            // Check if code is already used
            if ($activation['status'] != 'pending') {
                return ['success' => false, 'message' => 'Activation code already used'];
            }

            // Check fuel station float
            $station = $this->getFuelStation($activation['fuelStationId']);
            if ($station['currentFloat'] < $activation['fuelAmount']) {
                return ['success' => false, 'message' => 'Insufficient fuel at station'];
            }

            // Calculate interest
            $interestRate = $this->getPackageInterestRate($activation['packageId']);
            $interestAmount = ($activation['fuelAmount'] * $interestRate) / 100;
            $totalAmount = $activation['fuelAmount'] + $interestAmount;

            // Create fuel loan
            $loanId = $this->insert('fuel_loans', [
                'activationId' => $activation['activationId'],
                'bodaUserId' => $activation['bodaUserId'],
                'loanAmount' => $activation['fuelAmount'],
                'interestRate' => $interestRate,
                'interestAmount' => $interestAmount,
                'totalAmount' => $totalAmount,
                'fuelStationId' => $activation['fuelStationId'],
                'stageId' => $activation['stageId'],
                'status' => 'active',
                'loanDate' => date('Y-m-d'),
                'dueDate' => date('Y-m-d 23:59:59')
            ]);

            if ($loanId) {
                // Mark activation code as used
                $this->update('fuel_activation_codes', [
                    'status' => 'used',
                    'usedAt' => date('Y-m-d H:i:s')
                ], ['activationId' => $activation['activationId']]);

                // Send SMS confirmation to user
                $this->sendFuelReceivedSMS($activation, $totalAmount);

                // Log the transaction
                $this->logFuelTransaction($activation, $loanId, $agentId);

                return [
                    'success' => true,
                    'data' => [
                        'loanId' => $loanId,
                        'amount' => $activation['fuelAmount'],
                        'totalAmount' => $totalAmount,
                        'userName' => $activation['bodaUserName'],
                        'phoneNumber' => $activation['bodaUserPhoneNumber']
                    ]
                ];
            } else {
                return ['success' => false, 'message' => 'Failed to create fuel loan'];
            }

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'System error: ' . $e->getMessage()];
        }
    }

    /**
     * Process mobile money payment
     */
    public function processMobileMoneyPayment($phoneNumber, $loanId) {
        try {
            // Get loan details
            $loan = $this->getFuelLoan($loanId);
            if (!$loan) {
                return ['success' => false, 'message' => 'Loan not found'];
            }

            // Verify user owns this loan
            if ($loan['bodaUserPhoneNumber'] != $phoneNumber) {
                return ['success' => false, 'message' => 'Unauthorized payment'];
            }

            // Check if loan is already paid
            if ($loan['status'] == 'paid') {
                return ['success' => false, 'message' => 'Loan already paid'];
            }

            // Simulate mobile money payment (integrate with actual mobile money API)
            $paymentResult = $this->simulateMobileMoneyPayment($phoneNumber, $loan['totalAmount']);
            
            if ($paymentResult['success']) {
                // Update loan status
                $this->update('fuel_loans', [
                    'status' => 'paid',
                    'paidAt' => date('Y-m-d H:i:s')
                ], ['fuelLoanId' => $loanId]);

                // Update user borrowing status
                $this->update('bodauser', [
                    'canBorrowToday' => 1
                ], ['bodaUserId' => $loan['bodaUserId']]);

                // Record payment
                $this->insert('payments', [
                    'loanId' => $loanId,
                    'amount' => $loan['totalAmount'],
                    'paymentMethod' => 'mobile_money',
                    'paymentDate' => date('Y-m-d H:i:s')
                ]);

                // Send payment confirmation SMS
                $this->sendPaymentConfirmationSMS($phoneNumber, $loan['totalAmount']);

                return [
                    'success' => true,
                    'amount' => $loan['totalAmount'],
                    'transactionId' => $paymentResult['transactionId']
                ];
            } else {
                return ['success' => false, 'message' => $paymentResult['message']];
            }

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'System error: ' . $e->getMessage()];
        }
    }

    /**
     * Get user's loan history
     */
    public function getUserLoanHistory($phoneNumber, $limit = 10) {
        $sql = "SELECT fl.*, fs.fuelStationName, s.stageName 
                FROM fuel_loans fl 
                LEFT JOIN fuelstation fs ON fl.fuelStationId = fs.fuelStationId 
                LEFT JOIN stage s ON fl.stageId = s.stageId 
                WHERE fl.bodaUserId = (
                    SELECT bodaUserId FROM bodauser WHERE bodaUserPhoneNumber = '{$phoneNumber}'
                ) 
                ORDER BY fl.createdAt DESC 
                LIMIT {$limit}";

        return $this->selectQuery($sql);
    }

    /**
     * Check if user can borrow today
     */
    private function canUserBorrowToday($user) {
        // Relaxed for testing - allow multiple requests per day
        // Check if user has outstanding loan
        if (!$user['canBorrowToday']) {
            return false;
        }

        // Allow multiple requests per day for testing
        // if ($user['lastLoanDate'] == date('Y-m-d')) {
        //     return false;
        // }

        return true;
    }

    /**
     * Check if current time is within borrowing hours
     */
    private function isWithinBorrowingHours() {
        $currentTime = date('H:i:s');
        $startTime = '06:00:00';
        $endTime = '12:00:00';
        
        return $currentTime >= $startTime && $currentTime <= $endTime;
    }

    /**
     * Generate unique 6-digit activation code
     */
    private function generateUniqueCode() {
        do {
            $code = str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
            $exists = $this->select('fuel_activation_codes', ['activationId'], ['activationCode' => $code]);
        } while (!empty($exists));

        return $code;
    }

    /**
     * Get boda user by phone number
     */
    private function getBodaUserByPhone($phoneNumber) {
        $sql = "SELECT b.*, s.stageName, fs.fuelStationName 
                FROM bodauser b 
                LEFT JOIN stage s ON b.stageId = s.stageId 
                LEFT JOIN fuelstation fs ON b.fuelStationId = fs.fuelStationId 
                WHERE b.bodaUserPhoneNumber = '{$phoneNumber}' 
                AND b.bodaUserStatus = 1";

        $result = $this->selectQuery($sql);
        if (!empty($result)) {
            $user = $result[0];
            // Set default values for loan system
            $user['maxLoanAmount'] = 15000.00; // Default loan amount
            $user['maxDailyLoan'] = 15000.00; // Default daily loan amount
            $user['interestRate'] = 5.00; // Default interest rate
            $user['packageId'] = 1; // Default package ID
            $user['canBorrowToday'] = 1; // Allow borrowing for testing
            $user['lastLoanDate'] = null; // No previous loans
            return $user;
        }
        return null;
    }

    private function getBodaUserById($bodaUserId) {
        $sql = "SELECT b.*, s.stageName, fs.fuelStationName
                FROM bodauser b
                LEFT JOIN stage s ON b.stageId = s.stageId
                LEFT JOIN fuelstation fs ON b.fuelStationId = fs.fuelStationId
                WHERE b.bodaUserId = {$bodaUserId}
                AND b.bodaUserStatus = 1";
        $result = $this->selectQuery($sql);
        if (!empty($result)) {
            return $result[0];
        }
        return null;
    }

    /**
     * Get activation code details
     */
    private function getActivationCode($activationCode) {
        $sql = "SELECT ac.*, b.bodaUserName, b.bodaUserPhoneNumber, fs.fuelStationName, s.stageName 
                FROM fuel_activation_codes ac 
                LEFT JOIN bodauser b ON ac.bodaUserId = b.bodaUserId 
                LEFT JOIN fuelstation fs ON ac.fuelStationId = fs.fuelStationId 
                LEFT JOIN stage s ON ac.stageId = s.stageId 
                WHERE ac.activationCode = '{$activationCode}'";

        $result = $this->selectQuery($sql);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Get fuel station details
     */
    private function getFuelStation($stationId) {
        $result = $this->select('fuelstation', ['*'], ['fuelStationId' => $stationId]);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Get package interest rate
     */
    private function getPackageInterestRate($packageId) {
        $result = $this->select('package', ['interestRate'], ['packageId' => $packageId]);
        return !empty($result) ? $result[0]['interestRate'] : 5.00;
    }

    /**
     * Get fuel loan details
     */
    private function getFuelLoan($loanId) {
        $sql = "SELECT fl.*, b.bodaUserPhoneNumber 
                FROM fuel_loans fl 
                LEFT JOIN bodauser b ON fl.bodaUserId = b.bodaUserId 
                WHERE fl.fuelLoanId = {$loanId}";

        $result = $this->selectQuery($sql);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Simulate mobile money payment (replace with actual API integration)
     */
    private function simulateMobileMoneyPayment($phoneNumber, $amount) {
        // This is a simulation - integrate with actual mobile money API
        // For now, we'll simulate a successful payment
        
        // Simulate API call delay
        sleep(1);
        
        // Generate transaction ID
        $transactionId = 'TXN' . time() . rand(1000, 9999);
        
        return [
            'success' => true,
            'transactionId' => $transactionId,
            'amount' => $amount,
            'phoneNumber' => $phoneNumber
        ];
    }

    /**
     * Send fuel received SMS
     */
    private function sendFuelReceivedSMS($activation, $totalAmount) {
        $message = "You have received " . number_format($activation['fuelAmount']) . " UGX worth of fuel. ";
        $message .= "Total to pay: " . number_format($totalAmount) . " UGX by midnight to qualify for tomorrow's loan.";
        
        $this->sms->sendsms('SUNFUEL', $activation['bodaUserPhoneNumber'], $message);
        
        // Log SMS
        $this->insert('sms_logs', [
            'phoneNumber' => $activation['bodaUserPhoneNumber'],
            'message' => $message,
            'messageType' => 'fuel_received',
            'referenceId' => $activation['activationId'],
            'referenceType' => 'activation'
        ]);
    }

    /**
     * Send payment confirmation SMS
     */
    private function sendPaymentConfirmationSMS($phoneNumber, $amount) {
        $message = "Payment of " . number_format($amount) . " UGX confirmed. You are eligible for tomorrow's fuel loan.";
        
        $this->sms->sendsms('SUNFUEL', $phoneNumber, $message);
        
        // Log SMS
        $this->insert('sms_logs', [
            'phoneNumber' => $phoneNumber,
            'message' => $message,
            'messageType' => 'payment_confirmed',
            'referenceId' => null,
            'referenceType' => null
        ]);
    }

    /**
     * Log fuel transaction
     */
    private function logFuelTransaction($activation, $loanId, $agentId) {
        // You can extend this to log more detailed transaction information
        $this->insert('sms_logs', [
            'phoneNumber' => $activation['bodaUserPhoneNumber'],
            'message' => "Fuel loan activated: " . $activation['activationCode'],
            'messageType' => 'general',
            'referenceId' => $loanId,
            'referenceType' => 'fuel_loan'
        ]);
    }

    /**
     * Get daily loan statistics
     */
    public function getDailyLoanStats($date = null) {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $sql = "SELECT 
                    COUNT(*) as totalLoans,
                    SUM(loanAmount) as totalAmount,
                    SUM(interestAmount) as totalInterest,
                    SUM(totalAmount) as totalRevenue,
                    COUNT(CASE WHEN status = 'paid' THEN 1 END) as paidLoans,
                    COUNT(CASE WHEN status = 'active' THEN 1 END) as activeLoans,
                    COUNT(CASE WHEN status = 'overdue' THEN 1 END) as overdueLoans
                FROM fuel_loans 
                WHERE DATE(loanDate) = '{$date}'";

        $result = $this->selectQuery($sql);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Send payment reminders
     */
    public function sendPaymentReminders() {
        $sql = "SELECT fl.*, b.bodaUserPhoneNumber, b.bodaUserName 
                FROM fuel_loans fl 
                LEFT JOIN bodauser b ON fl.bodaUserId = b.bodaUserId 
                WHERE fl.status = 'active' 
                AND fl.dueDate >= CURDATE() 
                AND HOUR(NOW()) >= 17";

        $loans = $this->selectQuery($sql);
        
        foreach ($loans as $loan) {
            $message = "Please pay your outstanding loan of " . number_format($loan['totalAmount']) . " UGX by midnight to qualify for tomorrow's fuel loan.";
            
            $this->sms->sendsms('SUNFUEL', $loan['bodaUserPhoneNumber'], $message);
            
            // Log SMS
            $this->insert('sms_logs', [
                'phoneNumber' => $loan['bodaUserPhoneNumber'],
                'message' => $message,
                'messageType' => 'payment_reminder',
                'referenceId' => $loan['fuelLoanId'],
                'referenceType' => 'fuel_loan'
            ]);
        }

        return count($loans);
    }
}
?>
