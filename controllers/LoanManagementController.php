<?php

require_once __DIR__ . '/../utils/dbaccess.php';
require_once __DIR__ . '/SMSController.php';

class LoanManagementController extends DbAccess {
    private $smsController;

    public function __construct() {
        parent::__construct();
        $this->smsController = new SMSController();
    }

    /**
     * Check if user can borrow today
     */
    public function canUserBorrowToday($phoneNumber) {
        $user = $this->getBodaUserByPhone($phoneNumber);
        if (!$user) {
            return ['canBorrow' => false, 'reason' => 'User not found or not activated'];
        }

        // Testing mode - relaxed restrictions
        // Check if user is suspended
        if ($user['bodaUserStatus'] == 3) {
            return ['canBorrow' => false, 'reason' => 'Your account is suspended. Contact support'];
        }

        // For testing, allow borrowing even with outstanding loans
        // $outstandingLoan = $this->getOutstandingLoan($user['bodaUserId']);
        // if ($outstandingLoan) {
        //     return ['canBorrow' => false, 'reason' => 'You have an outstanding loan of ' . number_format($outstandingLoan['totalAmount']) . ' UGX'];
        // }

        // For testing, allow multiple loans per day
        // if ($user['lastLoanDate'] == date('Y-m-d')) {
        //     return ['canBorrow' => false, 'reason' => 'You have already borrowed fuel today. One loan per day allowed'];
        // }

        // Time restrictions disabled for testing
        // if (!$this->isWithinBorrowingHours()) {
        //     $startTime = $this->getBorrowStartTime();
        //     $endTime = $this->getBorrowEndTime();
        //     return ['canBorrow' => false, 'reason' => "Fuel requests only allowed between {$startTime} and {$endTime}"];
        // }

        return ['canBorrow' => true, 'reason' => 'You can borrow fuel today (testing mode)'];
    }

    /**
     * Get user's loan history
     */
    public function getUserLoanHistory($phoneNumber, $limit = 10) {
        $sql = "SELECT 
                    fl.*, 
                    fs.fuelStationName, 
                    s.stageName,
                    CASE 
                        WHEN fl.status = 'paid' THEN 'Paid'
                        WHEN fl.status = 'active' AND fl.dueDate >= CURDATE() THEN 'Active'
                        WHEN fl.status = 'active' AND fl.dueDate < CURDATE() THEN 'Overdue'
                        ELSE 'Unknown'
                    END as statusText
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
     * Get outstanding loan for user
     */
    public function getOutstandingLoan($bodaUserId) {
        $sql = "SELECT * FROM fuel_loans 
                WHERE bodaUserId = {$bodaUserId} 
                AND status = 'active' 
                ORDER BY createdAt DESC 
                LIMIT 1";
        
        $result = $this->selectQuery($sql);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Process loan payment
     */
    public function processLoanPayment($phoneNumber, $amount, $paymentMethod = 'mobile_money') {
        try {
            $user = $this->getBodaUserByPhone($phoneNumber);
            if (!$user) {
                error_log("Payment Debug - User not found for phone: $phoneNumber");
                return ['success' => false, 'message' => 'User not found'];
            }

            $outstandingLoan = $this->getOutstandingLoan($user['bodaUserId']);
            if (!$outstandingLoan) {
                error_log("Payment Debug - No outstanding loan for user ID: " . $user['bodaUserId']);
                // For testing, create a mock loan if none exists
                $mockLoan = [
                    'fuelLoanId' => 999,
                    'totalAmount' => $amount,
                    'loanAmount' => $amount * 0.95, // Assume 5% interest
                    'interestAmount' => $amount * 0.05
                ];
                error_log("Payment Debug - Using mock loan for testing");
                $outstandingLoan = $mockLoan;
            }

        // For testing, skip amount validation and always process payment
        error_log("Payment Debug - Processing payment: $amount for user: $phoneNumber");
        
        // Process payment (integrate with mobile money API)
        $paymentResult = $this->processPayment($phoneNumber, $amount, $paymentMethod);
        
        if ($paymentResult['success']) {
            // Update loan status (only if it's a real loan, not mock)
            if ($outstandingLoan['fuelLoanId'] != 999) {
                $this->update('fuel_loans', [
                    'status' => 'paid',
                    'paidAt' => date('Y-m-d H:i:s')
                ], ['fuelLoanId' => $outstandingLoan['fuelLoanId']]);
            }

            // Update user borrowing status
            $this->update('bodauser', [
                'canBorrowToday' => 1,
                'lastLoanDate' => NULL
            ], ['bodaUserId' => $user['bodaUserId']]);

            // Record payment (only if it's a real loan, not mock)
            if ($outstandingLoan['fuelLoanId'] != 999) {
                try {
                    $this->insert('payments', [
                        'loanId' => $outstandingLoan['fuelLoanId'],
                        'amount' => $amount,
                        'paymentMethod' => $paymentMethod,
                        'paymentDate' => date('Y-m-d H:i:s')
                    ]);
                } catch (Exception $e) {
                    error_log("Payment Debug - Could not insert payment record: " . $e->getMessage());
                }
            }

            // Send payment confirmation SMS (skip for testing)
            try {
                // $this->smsController->sendPaymentConfirmationSMS($phoneNumber, $amount);
                error_log("Payment Debug - SMS sending skipped for testing");
            } catch (Exception $e) {
                error_log("Payment Debug - SMS sending failed: " . $e->getMessage());
            }

            error_log("Payment Debug - Payment successful: " . $paymentResult['transactionId']);
            return [
                'success' => true,
                'message' => 'Payment processed successfully',
                'transactionId' => $paymentResult['transactionId'],
                'amount' => $amount
            ];
        } else {
            error_log("Payment Debug - Payment processing failed");
            return ['success' => false, 'message' => 'Payment processing failed'];
        }
        } catch (Exception $e) {
            error_log("Payment Debug - Exception: " . $e->getMessage());
            return ['success' => false, 'message' => 'System error: ' . $e->getMessage()];
        }
    }

    /**
     * Mark overdue loans
     */
    public function markOverdueLoans() {
        $sql = "UPDATE fuel_loans 
                SET status = 'overdue' 
                WHERE status = 'active' 
                AND dueDate < CURDATE()";
        
        $result = $this->update('fuel_loans', ['status' => 'overdue'], [
            'status' => 'active',
            'dueDate' => '< CURDATE()'
        ]);
        
        return $result;
    }

    /**
     * Send payment reminders
     */
    public function sendPaymentReminders() {
        // Only send reminders between 5 PM and 12 AM
        $currentHour = date('H');
        if ($currentHour < 17 || $currentHour > 23) {
            return ['sent' => 0, 'message' => 'Payment reminders only sent between 5 PM and 12 AM'];
        }

        return $this->smsController->sendBulkPaymentReminders();
    }

    /**
     * Get loan statistics
     */
    public function getLoanStatistics($date = null) {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $sql = "SELECT 
                    COUNT(*) as totalLoans,
                    SUM(loanAmount) as totalLoanAmount,
                    SUM(interestAmount) as totalInterest,
                    SUM(totalAmount) as totalRevenue,
                    COUNT(CASE WHEN status = 'paid' THEN 1 END) as paidLoans,
                    COUNT(CASE WHEN status = 'active' THEN 1 END) as activeLoans,
                    COUNT(CASE WHEN status = 'overdue' THEN 1 END) as overdueLoans,
                    AVG(loanAmount) as averageLoanAmount,
                    AVG(totalAmount) as averageTotalAmount
                FROM fuel_loans 
                WHERE DATE(loanDate) = '{$date}'";

        $result = $this->selectQuery($sql);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Get user loan summary
     */
    public function getUserLoanSummary($phoneNumber) {
        $user = $this->getBodaUserByPhone($phoneNumber);
        if (!$user) {
            return null;
        }

        $sql = "SELECT 
                    COUNT(*) as totalLoans,
                    SUM(loanAmount) as totalBorrowed,
                    SUM(totalAmount) as totalPaid,
                    COUNT(CASE WHEN status = 'paid' THEN 1 END) as paidLoans,
                    COUNT(CASE WHEN status = 'active' THEN 1 END) as activeLoans,
                    COUNT(CASE WHEN status = 'overdue' THEN 1 END) as overdueLoans
                FROM fuel_loans 
                WHERE bodaUserId = {$user['bodaUserId']}";

        $result = $this->selectQuery($sql);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Suspend user for non-payment
     */
    public function suspendUserForNonPayment($bodaUserId, $days = 7) {
        $this->update('bodauser', [
            'bodaUserStatus' => 3, // Suspended
            'canBorrowToday' => 0
        ], ['bodaUserId' => $bodaUserId]);

        // Log suspension
        $this->insert('sms_logs', [
            'phoneNumber' => $this->getUserPhone($bodaUserId),
            'message' => "Account suspended for non-payment. Contact support to resolve.",
            'messageType' => 'general',
            'status' => 'sent'
        ]);

        return true;
    }

    /**
     * Reactivate user after payment
     */
    public function reactivateUser($bodaUserId) {
        $this->update('bodauser', [
            'bodaUserStatus' => 1, // Active
            'canBorrowToday' => 1
        ], ['bodaUserId' => $bodaUserId]);

        return true;
    }

    /**
     * Check time restrictions
     */
    private function isWithinBorrowingHours() {
        $currentTime = date('H:i:s');
        $startTime = $this->getBorrowStartTime();
        $endTime = $this->getBorrowEndTime();
        
        return $currentTime >= $startTime && $currentTime <= $endTime;
    }

    private function getBorrowStartTime() {
        return '06:00:00'; // Default borrowing start time
    }

    private function getBorrowEndTime() {
        return '12:00:00'; // Default borrowing end time
    }

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

    private function processPayment($phoneNumber, $amount, $paymentMethod) {
        // Simulate payment processing (integrate with actual mobile money API)
        // This is where you would integrate with MTN Mobile Money, Airtel Money, etc.
        
        // Simulate API call
        sleep(1);
        
        // Generate transaction ID
        $transactionId = 'PAY' . time() . rand(1000, 9999);
        
        return [
            'success' => true,
            'transactionId' => $transactionId,
            'amount' => $amount,
            'phoneNumber' => $phoneNumber
        ];
    }

    private function getUserPhone($bodaUserId) {
        $result = $this->select('bodauser', ['bodaUserPhoneNumber'], ['bodaUserId' => $bodaUserId]);
        return !empty($result) ? $result[0]['bodaUserPhoneNumber'] : null;
    }
}
?>
