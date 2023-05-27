<?php
class LaonsCalc extends DbAccess
{
    //calc loans
    public function getTotalLaons()
    {
        $totalLoans = $this->selectQuery("SELECT COUNT(loanId) AS total FROM loan  WHERE  DATE(created_at) = CURDATE()")[0]['total'];
        if ($totalLoans == NULL) {
            return 0;
        } else {
            return $totalLoans;
        }
    }

    public function getOverallTotalPaidLoans(){
        $totalLoans = $this->selectQuery("SELECT COUNT(loanId) AS total FROM loan  WHERE  status =0")[0]['total'];
        if ($totalLoans == NULL) {
            return 0;
        } else {
            return $totalLoans;
        }

    }

    public function getOverallTotalUnPaidLoans(){
        $totalLoans = $this->selectQuery("SELECT COUNT(loanId) AS total FROM loan  WHERE status =1")[0]['total'];
        if ($totalLoans == NULL) {
            return 0;
        } else {
            return $totalLoans;
        }

    }

    // public function getTotalPaidLoansToday(){
    //     $totalLoans = $this->selectQuery("SELECT COUNT(loanId) AS total FROM loan  WHERE  status =0 AND DATE(created_at) = CURDATE()")[0]['total'];
    //     if ($totalLoans == NULL) {
    //         return 0;
    //     } else {
    //         return $totalLoans;
    //     }

    // }
    public function getTotalPaidLoansToday() {
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
    
        $totalLoans = $this->selectQuery("SELECT COUNT(loanId) AS total FROM loan WHERE status = 0 AND created_at >= '$todayStart' AND created_at <= '$todayEnd'")[0]['total'];
    
        return $totalLoans ?? 0;
    }

    public function getTotalUnPaidLoansToday() {
        $todayStart = date('Y-m-d 00:00:00');
        $todayEnd = date('Y-m-d 23:59:59');
    
        $totalLoans = $this->selectQuery("SELECT COUNT(loanId) AS total FROM loan WHERE status = 1 AND created_at >= '$todayStart' AND created_at <= '$todayEnd'")[0]['total'];
    
        return $totalLoans ?? 0;
    }

    // public function getTotalUnPaidLoansToday(){
    //     $totalLoans = $this->selectQuery("SELECT COUNT(loanId) AS total FROM loan  WHERE  status =1 AND DATE(created_at) = CURDATE()")[0]['total'];
    //     if ($totalLoans == NULL) {
    //         return 0;
    //     } else {
    //         return $totalLoans;
    //     }

    // }

    public  function getOverallTotalLoans(){
        $totalLoans = $this->selectQuery("SELECT COUNT(loanId) AS total FROM loan")[0]['total'];
        if ($totalLoans == NULL) {
            return 0;
        } else {
            return $totalLoans;
        }

    }

    public function getTotalAmountLoans()
    {
        $sql = "SELECT SUM(loanAmount) AS total FROM loan WHERE  DATE(created_at) = CURDATE()";
        $totalAmount = $this->selectQuery($sql)[0]["total"];
        if ($totalAmount == NULL) {
            return 0.0;
        } else {
            $totalAmount += $this->getTotalInterest();
            return $totalAmount;
        }
    }

    //overall total loan amount
    public function getOverallTotalAmountLoans()
    {
        $sql = "SELECT SUM(loanAmount) AS total FROM loan";
        $totalAmount = $this->selectQuery($sql)[0]["total"];
        if ($totalAmount == NULL) {
            return 0.0;
        } else {
            $totalAmount += $this->getOverallInterest();
            return $totalAmount;
        }
    }

    private function getOverallInterest(){
        $loanInterest = $this->selectQuery("SELECT SUM(LoanInterest)  AS total from loan")[0]['total'];
        if ($loanInterest == NULL) {
            return 0.0;
        } else {
            return $loanInterest;
        }  

    }
    
    private function getTotalInterest()
    {
        $loanInterest = $this->selectQuery("SELECT SUM(LoanInterest) AS total FROM loan  WHERE  DATE(created_at) = CURDATE()")[0]['total'];
        if ($loanInterest == NULL) {
            return 0.0;
        } else {
            return $loanInterest;
        }
    }

    //overall total loan interest
    public function getOverallTotalInterest()
    {
        $loanInterest = $this->selectQuery("SELECT SUM(LoanInterest) AS total FROM loan")[0]['total'];
        if ($loanInterest == NULL) {
            return 0.0;
        } else {
            return $loanInterest;
        }
    }

    //get paid or  unpiad load
    private function getPaidOrUnpaidLoanInterests($status)
    {
        $paidOrUnpaid = $this->selectQuery("SELECT SUM(LoanInterest) AS total FROM loan  WHERE  DATE(created_at) = CURDATE()
         AND status=$status")[0]['total'];

        if ($paidOrUnpaid == NULL) {
            return $paidOrUnpaid = 0;
        } else {
            return $paidOrUnpaid;
        }
    }

     //get overall paid or  unpiad loan interest
        public function getOverallPaidOrUnpaidLoanInterests($status)
        {
            $paidOrUnpaid = $this->selectQuery("SELECT SUM(LoanInterest) AS total FROM loan  WHERE status=$status")[0]['total'];
    
            if ($paidOrUnpaid == NULL) {
                return $paidOrUnpaid = 0;
            } else {
                return $paidOrUnpaid;
            }
        }

    //get total paid laons
    public  function totalPaidLaons()
    {
        $totalPaidLoans = $this->selectQuery("SELECT SUM(loanAmount) AS total FROM loan  WHERE  DATE(created_at) = CURDATE() 
		AND status=0")[0]['total'];
        if ($totalPaidLoans == NULL) {
            return $totalPaidLoans = 0.0;
        } else {
            return  $totalPaidLoans += $this->getPaidOrUnpaidLoanInterests(0);
        }
    }

     //get overall total paid laons
        public  function overallTotalPaidLaons()
        {
            $totalPaidLoans = $this->selectQuery("SELECT SUM(loanAmount) AS total FROM loan  WHERE status=0")[0]['total'];
            if ($totalPaidLoans == NULL) {
                return $totalPaidLoans = 0.0;
            } else {
                return  $totalPaidLoans += $this->getOverallPaidOrUnpaidLoanInterests(0);
            }
        }

    //get total paid loans
    //get total unpaid laons
    public function   totalUnpaidLoans()
    {
        $totalunpaidLoans = $this->selectQuery("SELECT SUM(loanAmount) AS total FROM loan  WHERE  DATE(created_at) = CURDATE()
		 AND status=1")[0]['total'];


        //unpaid loans
        if ($totalunpaidLoans ==  NULL) {
            return $totalunpaidLoans = 0.0;
        } else {
            return  $totalunpaidLoans += $this->getPaidOrUnpaidLoanInterests(1);
        }
    }

     //get overall total unpaid looans
        public function   overallTotalUnpaidLoans()
        {
            $totalunpaidLoans = $this->selectQuery("SELECT SUM(loanAmount) AS total FROM loan  WHERE status=1")[0]['total'];
    
    
            //unpaid loans
            if ($totalunpaidLoans ==  NULL) {
                return $totalunpaidLoans = 0.0;
            } else {
                return  $totalunpaidLoans += $this->getOverallPaidOrUnpaidLoanInterests(1);
            }
        }


    //get total unpaid loans

    //expected fuel consumption
    public function expectedFuelPerDay($totalBodaUsers)
    {
        $expectedFuelPerDay = $totalBodaUsers * 15000;
        return $expectedFuelPerDay;
    }

    // suspesnded  boda riders
    public function getOverallSuspendedRiders(){
        $totalLoans = $this->selectQuery("SELECT COUNT(bodaUserId) AS total FROM bodauser  WHERE bodaUserStatus =3")[0]['total'];
        if ($totalLoans == NULL) {
            return 0;
        } else {
            return $totalLoans;
        }

    }
    //suspended boda riders

    //suspended stages
    public function getOverallSuspendedStages(){
        $totalLoans = $this->selectQuery("SELECT COUNT(stageId) AS total FROM stage  WHERE stageStatus =2")[0]['total'];
        if ($totalLoans == NULL) {
            return 0;
        } else {
            return $totalLoans;
        }

    }
    //suspended stages
}
