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

if (!can('view-fuelagents')) header('Location:../Errors/unAuthorized.php');

if (!isset($_GET['showAgent'])) header('Location:../Errors/404.php');

$AgentId =  $_GET['showAgent'];

$fuelAgentDetails =  $dbAccess->select("fuelagent", "", ["fuelAgentId" => $AgentId]);

startContent();

// code here


breadCrumbs(['title' => 'Fuel Agent', 'sub_title' => 'Fuel Agent', 'previous' => 'Fuel Agents', 'previous_action' => './index.php']);

?>


<div class="row">
    <div class="col-12">

        <div class="container rounded bg-white mt-5 mb-5">
            <div class="row">
                <div class="col-md-3 border-right">
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        <img class=" mt-1" width="250px" src="<?= "https://appdev.creditplus.ug/bodafuelprojectmobileappapi/storage/app/public/images/id_cards/" . $fuelAgentDetails[0]['frontIDPhoto']; ?>">
                        <span class=" font-weight-bold">Front ID Photo</span>
                        <span class="text-black-50"><?= $fuelAgentDetails[0]['fuelAgentName'] ?></span><span> </span>
                    </div>
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        <img class=" mt-1" width="250px" src="<?= "https://appdev.creditplus.ug/bodafuelprojectmobileappapi/storage/app/public/images/id_cards/" . $fuelAgentDetails[0]['backIDPhoto']; ?>">
                        <span class=" font-weight-bold">Back IDPhoto</span>
                        <span class="text-black-50"><?= $fuelAgentDetails[0]['fuelAgentName'] ?></span><span> </span>
                    </div>
                </div>
                <div class="col-md-5 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right"> <?= $fuelAgentDetails[0]['fuelAgentName'] ?> Details</h4>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6"><label class="labels">Name</label><input type="text" disabled class="form-control" placeholder="first name" value="<?= $fuelAgentDetails[0]['fuelAgentName'] ?>"></div>
                            <div class="col-md-6"><label class="labels">NIN Number</label><input type="text" disabled class="form-control" placeholder="first name" value="<?= $fuelAgentDetails[0]['fuelAgentNIN'] ?>"></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Mobile Number</label>
                                <input type="text" class="form-control" disabled placeholder="enter phone number" value="<?= $fuelAgentDetails[0]['fuelAgentPhoneNumber'] ?>">
                            </div>
                            <div class="col-md-12"><label class="labels">Other Number</label>
                                <input type="text" class="form-control" disabled value="<?= $fuelAgentDetails[0]['anotherPhoneNumber'] ?>">
                            </div>
                            <div class="col-md-12"><label class="labels">Fuel Station</label>
                                <input type="text" class="form-control" disabled value="<?= $dbAccess->select("fuelstation", ['fuelStationName'], ['fuelStationId' => $fuelAgentDetails[0]['stationId']])[0]['fuelStationName'] ?>">
                            </div>

                        </div>


                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 py-5">


                        <div class="col-md-12"><label class="labels"> Status</label>
                            <?php
                            if ($fuelAgentDetails[0]["status"] == 0) {
                            ?>
                                <form action="activateAgent.php" method="post">
                                    <input type="hidden" name="id" value="<?= $fuelAgentDetails[0]["fuelAgentId"]; ?>" />
                                    <input type="hidden" name="fuelStationId" value="<?= $fuelAgentDetails[0]["stationId"] ?>" />
                                    <button type="submit" name="activate" class="btn btn-info btn-sm editbtn">Activate</button>
                                </form>
                            <?php } else { ?>
                                <form action="deactivateAgent.php" method="post">
                                    <input type="hidden" name="id" value="<?= $fuelAgentDetails[0]["fuelAgentId"]; ?>" />
                                    <button type="submit" name="deactivate" class="btn btn-danger btn-sm editbtn">DeActivate</button>
                                </form>

                            <?php } ?>

                        </div>
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
