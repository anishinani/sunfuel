<?php

/**
 * Header of the application
 * @author ThinkxSoftware
 * **/
include_once '../templates/SecurePageHeader.php';
/***
 * reusable components to inject code into the template
 * */
include_once '../templates/Components.php';

if(!can('view-receipts')) header('Location:../Errors/unAuthorized.php'); 


startContent();

// code here

if (!isset($_GET['showReceipt'])) header('Location:../Errors/404.php');

$depositId =  $_GET['showReceipt'];

$depositDetails =   $dbAccess->selectQuery("SELECT fuelstation.* ,deposits.* FROM fuelstation INNER JOIN deposits ON fuelstation.fuelStationId = deposits.fuelStationId WHERE deposits.fuelStationId = $depositId");


breadCrumbs(['title' => 'Deposit Receipt', 'sub_title' => 'Deposit Receipt', 'previous' => 'Deposits', 'previous_action' => './index']);



?>
<div class="row">
    <div class="col-12">

        <div class="container rounded bg-white mt-5 mb-5">
            <div class="row">
                <div class="col-md-3 border-right">
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        <img class=" mt-1" width="250px" src="<?= "images/" . $depositDetails[0]['receipt']; ?>">
                        <span class=" font-weight-bold">Receipt Photo</span>
                        <span class="text-black-50"><?= $depositDetails[0]['fuelStationName'] ?></span><span> </span>
                    </div>
                </div>
                <div class="col-md-5 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right"> <?= $depositDetails[0]['fuelStationName'] ?> Details</h4>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6"><label class="labels">Name</label><input type="text" disabled class="form-control" placeholder="first name" value="<?= $depositDetails[0]['fuelStationName'] ?>"></div>
                            <div class="col-md-6"><label class="labels">Deposited Amount</label><input type="text" disabled class="form-control" placeholder="first name" value="<?= $depositDetails[0]['amount'] ?>"></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Deposited By</label>
                                <input type="text" class="form-control" disabled placeholder="enter phone number" value="<?= $depositDetails[0]['depositedBy'] ?>">
                            </div>
                            <div class="col-md-12"><label class="labels">Deposited On</label>
                                <input type="text" class="form-control" disabled value="<?= $depositDetails[0]['created_at'] ?>">
                            </div>
                            <div class="col-md-12"><label class="labels">Total Amount Since Initial Deposit</label>
                                <input type="text" class="form-control" disabled value="<?= "shs " . number_format($depositDetails[0]['totalAmount'], 0) ?>">
                            </div>
                            <div class="col-md-12"><label class="labels">Current Amount</label>
                                <input type="text" class="form-control" disabled value="<?= "shs " . number_format($depositDetails[0]['currentAmount'], 0) ?>">
                            </div>

                        </div>


                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 py-5">
                        <div class="col-md-12"><label class="labels">Bank Name</label>
                            <input type="text" class="form-control" disabled value="<?= $depositDetails[0]['bankName'] ?>">
                        </div>
                        <div class="col-md-12"><label class="labels">Bank Branch</label>
                            <input type="text" class="form-control" disabled value="<?= $depositDetails[0]['bankBranch'] ?>">
                        </div>
                        <div class="col-md-12"><label class="labels">Account Name</label>
                            <input type="text" class="form-control" disabled value="<?= $depositDetails[0]['AccName'] ?>">
                        </div>
                        <div class="col-md-12"><label class="labels">Account Number</label>
                            <input type="text" class="form-control" disabled value="<?= $depositDetails[0]['AccNumber'] ?>">
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php

endContent();

/**
 * footer of the application
 * */
include_once '../templates/footer.php';

/**
 * custom page javascript
 * **/
endPage();
