<?php
/**
 * Fuel Loan System Scheduler
 * This script handles time-based operations for the fuel loan system
 * Should be run as a cron job every minute
 */

require_once '../utils/dbaccess.php';
require_once '../controllers/SMSController.php';
require_once '../controllers/LoanManagementController.php';

class FuelLoanScheduler extends DbAccess {
    private $smsController;
    private $loanController;

    public function __construct() {
        parent::__construct();
        $this->smsController = new SMSController();
        $this->loanController = new LoanManagementController();
    }

    /**
     * Main scheduler function - run every minute
     */
    public function run() {
        echo "[" . date('Y-m-d H:i:s') . "] Starting fuel loan scheduler...\n";
        
        // Mark overdue loans
        $this->markOverdueLoans();
        
        // Send payment reminders
        $this->sendPaymentReminders();
        
        // Reset daily borrowing limits
        $this->resetDailyBorrowingLimits();
        
        // Clean expired activation codes
        $this->cleanExpiredActivationCodes();
        
        // Update fuel station floats
        $this->updateFuelStationFloats();
        
        // Send daily reports
        $this->sendDailyReports();
        
        echo "[" . date('Y-m-d H:i:s') . "] Scheduler completed.\n";
    }

    /**
     * Mark loans as overdue after midnight
     */
    private function markOverdueLoans() {
        echo "Checking for overdue loans...\n";
        
        $sql = "UPDATE fuel_loans 
                SET status = 'overdue' 
                WHERE status = 'active' 
                AND dueDate < CURDATE()";
        
        $result = $this->conn->query($sql);
        
        if ($result) {
            $affectedRows = $this->conn->affected_rows;
            if ($affectedRows > 0) {
                echo "Marked {$affectedRows} loans as overdue.\n";
                
                // Send SMS to users with overdue loans
                $this->notifyOverdueLoans();
            }
        }
    }

    /**
     * Send payment reminders at 5 PM
     */
    private function sendPaymentReminders() {
        $currentHour = date('H');
        
        // Send reminders at 5 PM, 7 PM, 9 PM, and 11 PM
        if (in_array($currentHour, [17, 19, 21, 23])) {
            echo "Sending payment reminders...\n";
            
            $remindersSent = $this->loanController->sendPaymentReminders();
            echo "Sent {$remindersSent} payment reminders.\n";
        }
    }

    /**
     * Reset daily borrowing limits at midnight
     */
    private function resetDailyBorrowingLimits() {
        $currentTime = date('H:i:s');
        
        // Reset at midnight (00:00)
        if ($currentTime >= '00:00:00' && $currentTime <= '00:05:00') {
            echo "Resetting daily borrowing limits...\n";
            
            $sql = "UPDATE bodauser 
                    SET canBorrowToday = 1 
                    WHERE bodaUserStatus = 1 
                    AND canBorrowToday = 0";
            
            $result = $this->conn->query($sql);
            
            if ($result) {
                $affectedRows = $this->conn->affected_rows;
                echo "Reset borrowing limits for {$affectedRows} users.\n";
            }
        }
    }

    /**
     * Clean expired activation codes
     */
    private function cleanExpiredActivationCodes() {
        echo "Cleaning expired activation codes...\n";
        
        $sql = "UPDATE fuel_activation_codes 
                SET status = 'expired' 
                WHERE status = 'pending' 
                AND expiresAt < NOW()";
        
        $result = $this->conn->query($sql);
        
        if ($result) {
            $affectedRows = $this->conn->affected_rows;
            if ($affectedRows > 0) {
                echo "Expired {$affectedRows} activation codes.\n";
            }
        }
    }

    /**
     * Update fuel station floats
     */
    private function updateFuelStationFloats() {
        echo "Updating fuel station floats...\n";
        
        // This would typically integrate with fuel station POS systems
        // For now, we'll just log that this should be done
        echo "Fuel station float updates should be integrated with POS systems.\n";
    }

    /**
     * Send daily reports
     */
    private function sendDailyReports() {
        $currentTime = date('H:i:s');
        
        // Send reports at 8 AM and 6 PM
        if ($currentTime >= '08:00:00' && $currentTime <= '08:05:00' || 
            $currentTime >= '18:00:00' && $currentTime <= '18:05:00') {
            echo "Sending daily reports...\n";
            
            $this->sendAdminReports();
        }
    }

    /**
     * Notify users with overdue loans
     */
    private function notifyOverdueLoans() {
        $sql = "SELECT DISTINCT b.bodaUserPhoneNumber, fl.totalAmount 
                FROM bodauser b 
                INNER JOIN fuel_loans fl ON b.bodaUserId = fl.bodaUserId 
                WHERE fl.status = 'overdue' 
                AND fl.dueDate = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        
        $loans = $this->selectQuery($sql);
        
        foreach ($loans as $loan) {
            $message = "SUNFUEL: Your loan of " . number_format($loan['totalAmount']) . " UGX is overdue. ";
            $message .= "Please pay immediately to avoid account suspension.";
            
            $this->smsController->sendSMS($loan['bodaUserPhoneNumber'], $message, 'general');
        }
    }

    /**
     * Send admin reports
     */
    private function sendAdminReports() {
        $stats = $this->loanController->getLoanStatistics();
        
        if ($stats) {
            $message = "SUNFUEL Daily Report - " . date('Y-m-d') . "\n";
            $message .= "Total Loans: {$stats['totalLoans']}\n";
            $message .= "Total Amount: " . number_format($stats['totalLoanAmount']) . " UGX\n";
            $message .= "Paid Loans: {$stats['paidLoans']}\n";
            $message .= "Active Loans: {$stats['activeLoans']}\n";
            $message .= "Overdue Loans: {$stats['overdueLoans']}\n";
            $message .= "Total Revenue: " . number_format($stats['totalRevenue']) . " UGX";
            
            // Send to admin (you would get this from database)
            $adminPhone = '256700000000'; // Replace with actual admin phone
            $this->smsController->sendSMS($adminPhone, $message, 'general');
        }
    }
}

// Run the scheduler if called directly
if (php_sapi_name() === 'cli') {
    $scheduler = new FuelLoanScheduler();
    $scheduler->run();
} else {
    // Web interface for testing
    if (isset($_GET['run']) && $_GET['run'] === 'scheduler') {
        $scheduler = new FuelLoanScheduler();
        $scheduler->run();
        echo "<br>Scheduler completed. Check server logs for details.";
    } else {
        echo "<h2>Fuel Loan Scheduler</h2>";
        echo "<p>This script should be run as a cron job every minute.</p>";
        echo "<p><a href='?run=scheduler'>Run Scheduler Now (Test)</a></p>";
        
        echo "<h3>Recommended Cron Jobs:</h3>";
        echo "<pre>";
        echo "# Run every minute\n";
        echo "* * * * * /usr/bin/php " . __FILE__ . "\n\n";
        echo "# Alternative: Run every 5 minutes\n";
        echo "*/5 * * * * /usr/bin/php " . __FILE__ . "\n";
        echo "</pre>";
    }
}
?>
