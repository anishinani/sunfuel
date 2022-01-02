$sql = "SELECT SUM(loanAmount) AS total FROM loan WHERE DATE(updated_at) = CURDATE()";
$totalAmount = $loanCalc->selectQuery($sql)[0]["total"];

$loanInterest = $loanCalc->selectQuery("SELECT SUM(LoanInterest) AS total FROM loan WHERE DATE(updated_at) = CURDATE()")[0]['total'];
$unpaidInterest = $loanCalc->selectQuery("SELECT SUM(LoanInterest) AS total FROM loan WHERE DATE(updated_at) = CURDATE() AND status=1")[0]['total'];
$paidInterest = $loanCalc->selectQuery("SELECT SUM(LoanInterest) AS total FROM loan WHERE DATE(updated_at) = CURDATE() AND status=0")[0]['total'];
$balance = $expectedFuelPerDay - $totalAmount;

if ($totalAmount == NULL) {
$totalAmount = 0.0;
} else {
$totalAmount += $loanInterest;
}


//loans
$totalLoans = $loanCalc->selectQuery("SELECT COUNT(loanId) AS total FROM loan WHERE DATE(updated_at) = CURDATE()")[0]['total'];
$totalPaidLoans = $loanCalc->selectQuery("SELECT SUM(loanAmount) AS total FROM loan WHERE DATE(updated_at) = CURDATE()
AND status=0")[0]['total'];



if ($totalPaidLoans == NULL) {
$totalPaidLoans = 0.0;
} else {
$totalPaidLoans += $paidInterest;
}

$totalunpaidLoans = $loanCalc->selectQuery("SELECT SUM(loanAmount) AS total FROM loan WHERE DATE(updated_at) = CURDATE()
AND status=1")[0]['total'];


//unpaid loans
if ($totalunpaidLoans == NULL) {
$totalunpaidLoans = 0.0;
} else {
$totalunpaidLoans += $unpaidInterest;
}