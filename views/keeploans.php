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


<!--floatdetails-->
<div class="home__details col-12">

    <h2 class="home__word">Float Details</h2>
</div>
<div class="col-sm-12 eachCard">
    <div class="statistics-details d-flex align-items-center justify-content-between">

        <div class="home__eachCardDetails  ">
            <p class="statistics-title ">Total Expected Amount</p>
            <h3 class="rate-percentage"> <?= "shs " . number_format($expectedFuelPerDay, 0); ?></h3>

        </div>
        <div class="home__eachCardDetails">
            <p class="statistics-title">Total Amount Withdrawn</p>
            <h3 class="rate-percentage"> <?= "shs " . number_format($totalAmount, 0); ?></h3>
        </div>
    </div>

</div>


<!--floatdetails-->

<!--boda details-->
<div class="home__details col-12">
    <h2 class="home__word">Boda Details</h2>
</div>


<div class="col-sm-12">

    <div class="statistics-details d-flex align-items-center justify-content-between mycard">

        <div class="home__eachCardDetails">
            <p class="statistics-title ">Total Active Boda Users</p>
            <h3 class="rate-percentage"><?= $totalActiveBodaUsers ?></h3>

        </div>
        <div class="home__eachCardDetails">
            <p class="statistics-title">Total Inactive Boda Users</p>
            <h3 class="rate-percentage"> <?= $totalInActiveBodaUsers ?></h3>
        </div>
        <div class="home__eachCardDetails">
            <p class="statistics-title">Current Boda Loans</p>
            <h3 class="rate-percentage"> <?= $totalDefaultedBodaUsers ?></h3>
        </div>
        <div class="home__eachCardDetails">
            <p class="statistics-title">Total Suspended Boda Loans</p>
            <h3 class="rate-percentage"> <?= $suspendedBodaUsers ?></h3>
        </div>
    </div>
</div>
</div>
<!--boda details-->

<!--loan details-->
<div class="home__details col-12">
    <h2 class="home__word">Loan Details</h2>
</div>


<div class="col-sm-12">

    <div class="statistics-details d-flex align-items-center justify-content-between mycard">

        <div class="home__eachCardDetails">
            <p class="statistics-title ">Total Loans</p>
            <h3 class="rate-percentage"> <?= $totalLoans ?></h3>

        </div>
        <div class="home__eachCardDetails">
            <p class="statistics-title">Total Loan Amount</p>
            <h3 class="rate-percentage"> <?= "shs " . number_format($totalAmount); ?></h3>
        </div>
        <div class="home__eachCardDetails">
            <p class="statistics-title">Total Paid Laons</p>
            <h3 class="rate-percentage"> <?= "shs" . number_format($totalPaidLoans); ?></h3>
        </div>
        <div class="home__eachCardDetails">
            <p class="statistics-title">Total UnPaid Laons</p>
            <h3 class="rate-percentage"> <?= "shs" . number_format($totalUnpaidLoans); ?></h3>
        </div>
    </div>
</div>
</div>

<!--loan details-->



<!--stage details-->
<div class="home__details col-12">
    <h2 class="home__word">Stage Details</h2>
</div>


<div class="col-sm-12">

    <div class="statistics-details d-flex align-items-center justify-content-between mycard">

        <div class="home__eachCardDetails">
            <p class="statistics-title ">Total Active Stages</p>
            <h3 class="rate-percentage"> <?= $totalActiveStages ?></h3>

        </div>
        <div class="home__eachCardDetails">
            <p class="statistics-title">Total Inactive Stages</p>
            <h3 class="rate-percentage"> <?= $totalInActiveStages ?></h3>
        </div>
        <div class="home__eachCardDetails">
            <p class="statistics-title">Total Defaulted Stages</p>
            <h3 class="rate-percentage"> <?= $totalDefaultStages ?></h3>
        </div>
        <div class="home__eachCardDetails">
            <p class="statistics-title">Total Suspended</p>
            <h3 class="rate-percentage"> <?= $suspendedStages ?></h3>
        </div>
    </div>
</div>
</div>
<!--stage details-->

<div class="row">
    <?php
    function welcome()
    {

        if (date("H") < 12) {

            return "Good Morning";
        } elseif (date("H") > 11 && date("H") < 18) {

            return "Good Afternoon";
        } elseif (date("H") > 17) {

            return "Good  Evening";
        }
    }
    ?>
    <div class="home__top col-12">
        <ul class="navbar-nav">
            <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text"><?= welcome(); ?> <span class="text-black fw-bold"><?= ", " . $_SESSION['user']; ?></span></h1>
                <h4 class="welcome-sub-text">Your Summary Details on <?php echo date("D/M/Y"); ?> </h4>
            </li>

        </ul>
        <div class="md-form md-outline input-with-post-icon datepicker">
            <form>
                <div class="form-group">
                    <input id="theDate" class="form-control" type="date" name="date">
                </div>
            </form>
        </div>

    </div>
    <div class="col-12">
        <hr />
    </div>


</div>

var date = new Date();

var day = date.getDate();
var month = date.getMonth() + 1;
var year = date.getFullYear();

if (month < 10) month="0" + month; if (day < 10) day="0" + day; var today=year + "-" + month + "-" + day + "T00:00" ; $("#theDate").attr("value", today);