<?php

require_once __DIR__ . '/../utils/dbaccess.php';
require_once __DIR__ . '/../utils/sms.php';

class SMSController extends DbAccess {
    private $sms;

    public function __construct() {
        parent::__construct();
        $this->sms = new infobip();
    }

    /**
     * Send fuel activation code SMS
     */
    public function sendActivationCodeSMS($phoneNumber, $activationCode, $stationName, $amount) {
        $message = "SUNFUEL: Your fuel activation code is {$activationCode}. ";
        $message .= "Valid for 30 minutes. Visit {$stationName} to collect {$amount} UGX worth of fuel.";
        
        $result = $this->sms->sendsms('SUNFUEL', $phoneNumber, $message);
        
        // Log SMS
        $this->logSMS($phoneNumber, $message, 'activation_code', $result);
        
        return $result;
    }

    /**
     * Send fuel received confirmation SMS
     */
    public function sendFuelReceivedSMS($phoneNumber, $fuelAmount, $totalAmount, $dueTime) {
        $message = "SUNFUEL: You have received " . number_format($fuelAmount) . " UGX worth of fuel. ";
        $message .= "Total to pay: " . number_format($totalAmount) . " UGX by {$dueTime} to qualify for tomorrow's loan.";
        
        $result = $this->sms->sendsms('SUNFUEL', $phoneNumber, $message);
        
        // Log SMS
        $this->logSMS($phoneNumber, $message, 'fuel_received', $result);
        
        return $result;
    }

    /**
     * Send payment reminder SMS
     */
    public function sendPaymentReminderSMS($phoneNumber, $amount, $dueTime) {
        $message = "SUNFUEL: Please pay your outstanding loan of " . number_format($amount) . " UGX by {$dueTime} to qualify for tomorrow's fuel loan.";
        
        $result = $this->sms->sendsms('SUNFUEL', $phoneNumber, $message);
        
        // Log SMS
        $this->logSMS($phoneNumber, $message, 'payment_reminder', $result);
        
        return $result;
    }

    /**
     * Send payment confirmation SMS
     */
    public function sendPaymentConfirmationSMS($phoneNumber, $amount) {
        $message = "SUNFUEL: Payment of " . number_format($amount) . " UGX confirmed. You are eligible for tomorrow's fuel loan.";
        
        $result = $this->sms->sendsms('SUNFUEL', $phoneNumber, $message);
        
        // Log SMS
        $this->logSMS($phoneNumber, $message, 'payment_confirmed', $result);
        
        return $result;
    }

    /**
     * Send loan eligibility SMS
     */
    public function sendLoanEligibilitySMS($phoneNumber, $status, $reason = '') {
        if ($status === 'eligible') {
            $message = "SUNFUEL: You are eligible for today's fuel loan. Dial *123# to request fuel.";
        } else {
            $message = "SUNFUEL: You are not eligible for today's fuel loan. {$reason}";
        }
        
        $result = $this->sms->sendsms('SUNFUEL', $phoneNumber, $message);
        
        // Log SMS
        $this->logSMS($phoneNumber, $message, 'general', $result);
        
        return $result;
    }

    /**
     * Send system maintenance SMS
     */
    public function sendSystemMaintenanceSMS($phoneNumber, $maintenanceTime) {
        $message = "SUNFUEL: System maintenance scheduled for {$maintenanceTime}. Services will be temporarily unavailable.";
        
        $result = $this->sms->sendsms('SUNFUEL', $phoneNumber, $message);
        
        // Log SMS
        $this->logSMS($phoneNumber, $message, 'general', $result);
        
        return $result;
    }

    /**
     * Send bulk SMS to all active users
     */
    public function sendBulkSMS($message, $messageType = 'general') {
        $sql = "SELECT DISTINCT bodaUserPhoneNumber FROM bodauser WHERE bodaUserStatus = 1";
        $users = $this->selectQuery($sql);
        
        $sent = 0;
        $failed = 0;
        
        foreach ($users as $user) {
            $result = $this->sms->sendsms('SUNFUEL', $user['bodaUserPhoneNumber'], $message);
            
            if ($result) {
                $sent++;
            } else {
                $failed++;
            }
            
            // Log SMS
            $this->logSMS($user['bodaUserPhoneNumber'], $message, $messageType, $result);
            
            // Add delay to avoid rate limiting
            sleep(1);
        }
        
        return [
            'sent' => $sent,
            'failed' => $failed,
            'total' => count($users)
        ];
    }

    /**
     * Send payment reminders to all users with outstanding loans
     */
    public function sendBulkPaymentReminders() {
        $sql = "SELECT DISTINCT b.bodaUserPhoneNumber, fl.totalAmount 
                FROM bodauser b 
                INNER JOIN fuel_loans fl ON b.bodaUserId = fl.bodaUserId 
                WHERE fl.status = 'active' 
                AND fl.dueDate >= CURDATE() 
                AND HOUR(NOW()) >= 17";
        
        $loans = $this->selectQuery($sql);
        
        $sent = 0;
        foreach ($loans as $loan) {
            $this->sendPaymentReminderSMS($loan['bodaUserPhoneNumber'], $loan['totalAmount'], 'midnight');
            $sent++;
            
            // Add delay to avoid rate limiting
            sleep(2);
        }
        
        return $sent;
    }

    /**
     * Log SMS to database
     */
    private function logSMS($phoneNumber, $message, $messageType, $status) {
        $this->insert('sms_logs', [
            'phoneNumber' => $phoneNumber,
            'message' => $message,
            'messageType' => $messageType,
            'status' => $status ? 'sent' : 'failed',
            'referenceId' => null,
            'referenceType' => null
        ]);
    }

    /**
     * Get SMS statistics
     */
    public function getSMSStats($date = null) {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        $sql = "SELECT 
                    COUNT(*) as totalSMS,
                    COUNT(CASE WHEN status = 'sent' THEN 1 END) as sentSMS,
                    COUNT(CASE WHEN status = 'failed' THEN 1 END) as failedSMS,
                    COUNT(CASE WHEN messageType = 'activation_code' THEN 1 END) as activationSMS,
                    COUNT(CASE WHEN messageType = 'fuel_received' THEN 1 END) as fuelReceivedSMS,
                    COUNT(CASE WHEN messageType = 'payment_reminder' THEN 1 END) as reminderSMS,
                    COUNT(CASE WHEN messageType = 'payment_confirmed' THEN 1 END) as paymentSMS
                FROM sms_logs 
                WHERE DATE(createdAt) = '{$date}'";
        
        $result = $this->selectQuery($sql);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Get SMS delivery report
     */
    public function getSMSDeliveryReport($startDate, $endDate) {
        $sql = "SELECT 
                    DATE(createdAt) as date,
                    messageType,
                    status,
                    COUNT(*) as count
                FROM sms_logs 
                WHERE DATE(createdAt) BETWEEN '{$startDate}' AND '{$endDate}'
                GROUP BY DATE(createdAt), messageType, status
                ORDER BY date DESC, messageType, status";
        
        return $this->selectQuery($sql);
    }

    /**
     * Resend failed SMS
     */
    public function resendFailedSMS($smsId) {
        $sql = "SELECT * FROM sms_logs WHERE smsId = {$smsId} AND status = 'failed'";
        $sms = $this->selectQuery($sql);
        
        if (empty($sms)) {
            return ['success' => false, 'message' => 'SMS not found or not failed'];
        }
        
        $smsData = $sms[0];
        $result = $this->sms->sendsms('SUNFUEL', $smsData['phoneNumber'], $smsData['message']);
        
        // Update status
        $this->update('sms_logs', [
            'status' => $result ? 'sent' : 'failed'
        ], ['smsId' => $smsId]);
        
        return [
            'success' => $result,
            'message' => $result ? 'SMS resent successfully' : 'Failed to resend SMS'
        ];
    }
}
?>
