<?php
class LaonsCalc extends DbAccess
{
    //calc loans
    public function getTotalLaons()
    {
        $totalLoans = $this->selectQuery("SELECT COUNT(loanId) AS total FROM loan  WHERE  DATE(updated_at) = CURDATE()")[0]['total'];
        if ($totalLoans == NULL) {
            return 0;
        } else {
            return $totalLoans;
        }
    }

    public function getTotalAmountLoans()
    {
        $sql = "SELECT SUM(loanAmount) AS total FROM loan WHERE  DATE(updated_at) = CURDATE()";
        $totalAmount = $this->selectQuery($sql)[0]["total"];
        if ($totalAmount == NULL) {
            return 0.0;
        } else {
            $totalAmount += $this->getTotalInterest();
            return $totalAmount;
        }
    }
    //private 
    private function getTotalInterest()
    {
        $loanInterest = $this->selectQuery("SELECT SUM(LoanInterest) AS total FROM loan  WHERE  DATE(updated_at) = CURDATE()")[0]['total'];
        if ($loanInterest == NULL) {
            return 0.0;
        } else {
            return $loanInterest;
        }
    }

    //get paid or  unpiad load
    private function getPaidOrUnpaidLoanInterests($status)
    {
        $paidOrUnpaid = $this->selectQuery("SELECT SUM(LoanInterest) AS total FROM loan  WHERE  DATE(updated_at) = CURDATE()
         AND status=$status")[0]['total'];

        if ($paidOrUnpaid == NULL) {
            return $paidOrUnpaid = 0;
        } else {
            return $paidOrUnpaid;
        }
    }

    //get total paid laons
    public  function totalPaidLaons()
    {
        $totalPaidLoans = $this->selectQuery("SELECT SUM(loanAmount) AS total FROM loan  WHERE  DATE(updated_at) = CURDATE() 
		AND status=0")[0]['total'];
        if ($totalPaidLoans == NULL) {
            return $totalPaidLoans = 0.0;
        } else {
            return  $totalPaidLoans += $this->getPaidOrUnpaidLoanInterests(0);
        }
    }
    //get total paid loans
    //get total unpaid laons
    public function   totalUnpaidLoans()
    {
        $totalunpaidLoans = $this->selectQuery("SELECT SUM(loanAmount) AS total FROM loan  WHERE  DATE(updated_at) = CURDATE()
		 AND status=1")[0]['total'];


        //unpaid loans
        if ($totalunpaidLoans ==  NULL) {
            return $totalunpaidLoans = 0.0;
        } else {
            return  $totalunpaidLoans += $this->getPaidOrUnpaidLoanInterests(1);
        }
    }
    //get total unpaid loans

    //expected fuel consumption
    public function expectedFuelPerDay($totalBodaUsers)
    {
        $expectedFuelPerDay = $totalBodaUsers * 15000;
        return $expectedFuelPerDay;
    }
}
