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

if (!can('edit-stages')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";

if (!isset($_GET['update'])) echo "<script>window.open('../Errors/404.php','_self')</script>";

$id = $_GET["update"];
$results = $dbAccess->select("stage", "", ["stageId" => $id]);
$currentStation = $dbAccess->select("fuelstation", "", ["fuelStationId" => $results[0]['fuelStationId']]);
$fuelStations = $dbAccess->select("fuelstation", ["fuelStationId", "fuelStationName"]);
$bodaUsers = $dbAccess->select("bodauser", ['bodaUserId', "bodaUserName"], ["bodaUserRole" => "Chairman"]);

startContent();

breadCrumbs(['title' => 'Edit Stage', 'sub_title' => 'Edit Stage', 'previous' => 'Stages', 'previous_action' => './index.php']);

?>

<div class="row">
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-body">
                <p class="login-box-msg">Edit Stage</p>
                <form method="POST" action="./update.php">
                    <div class="form-group mb-3">
                        <label for="">Stage Name</label>
                        <input type="text" name="name" required class="form-control" placeholder="enter stage name" value="<?= $results[0]['stageName']; ?>" />
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-group">
                            <label for="my-select">Fuel Station</label>
                            <select id="my-select" class="form-control" name="fuelStationId">
                                <option selected value="<?= $currentStation[0]["fuelStationId"]; ?>"><?= $currentStation[0]["fuelStationName"]; ?></option>
                                <?php for ($i = 0; $i < count($fuelStations); $i++) { ?>
                                    <option value="<?= $fuelStations[$i]["fuelStationId"] ?>">
                                        <?= $fuelStations[$i]["fuelStationName"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-group">
                            <label for="my-select">Select Chairman</label>
                            <select id="my-select" class="form-control" name="chairman">
                                <option disabled selected>Select Chairman</option>
                                <?php for ($i = 0; $i < count($bodaUsers); $i++) { ?>
                                    <option value="<?= $bodaUsers[$i]["bodaUserId"] ?>">
                                        <?= $bodaUsers[$i]["bodaUserName"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="id" value="<?= $_GET['update']; ?>" />
                    <div class="col-12">
                        <button type="submit" class="style_button" name="addStage">Update Stage </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php

endContent();

include_once '../templates/footer.php';

endPage();
