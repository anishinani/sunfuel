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

if (!can('edit-fuelagent')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";

if (!isset($_GET['update'])) echo "<script>window.open('../Errors/404.php','_self')</script>";

$id = $_GET["update"];

$results  = $dbAccess->select("fuelagent", "", ["fuelAgentId" => $id]);

$stations =  $dbAccess->select("fuelstation", ["fuelStationId", "fuelStationName"]);

$currentStation = $dbAccess->select("fuelstation", "", ["fuelStationId" => $results[0]['stationId']]);


startContent();

// code here

breadCrumbs(['title' => 'Edit Fuel Agent', 'sub_title' => 'Edit Fuel Agent', 'previous' => 'Fuel Agents', 'previous_action' => './index.php']);

?>

<div class="row">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">

            <div class="card-body">
                <p class="login-box-msg">Edit Fuel Agent</p>
                <form method="POST" action="./update.php">


                    <div class="form-group mb-3">
                        <label for="">Fuel Agent Name</label>
                        <input type="text" name="name" required class="form-control" placeholder="enter station name" value="<?= $results[0]['fuelAgentName']; ?>" />

                    </div>

                    <!--person-->
                    <div class="form-group mb-3">
                        <label for="">Agent NIN Number</label>
                        <input type="text" name="nin" required class="form-control" value="<?= $results[0]['fuelAgentNIN']; ?>" placeholder="enter nin number" />

                    </div>
                    <!--person-->

                    <!--phone-->
                    <div class="form-group mb-3">
                        <label for=""> Agent Phone Number</label>
                        <input type="text" name="phoneNumber" required class="form-control" placeholder="enter phone number name" value="<?= $results[0]['fuelAgentPhoneNumber']; ?>" />
                    </div>
                    <!---phone-->
                    <!--phone-->
                    <div class="form-group mb-3">
                        <label for=""> Another Phone Number</label>
                        <input type="text" name="anotherPhone" required class="form-control" placeholder="enter
                                             another phone number " value="<?= $results[0]['anotherPhoneNumber']; ?>" />
                    </div>
                    <!---phone-->
                    <!--fuel station-->
                    <div class="form-group mb-3">
                        <div class="form-group">
                            <label for="my-select">Fuel Station</label>
                            <select id="my-select" class="form-control" name="station">
                                <option selected value="<?= $currentStation[0]["fuelStationId"] ?>">
                                    <?= $currentStation[0]["fuelStationName"] ?>
                                </option>
                                <?php
                                for ($i = 0; $i < count($stations); $i++) {
                                ?>
                                    <option value="<?= $stations[$i]["fuelStationId"] ?>">
                                        <?= $stations[$i]["fuelStationName"] ?></option>

                                <?php } ?>
                            </select>
                        </div>

                    </div>
                    <!--fuel station-->

                    <!--hidden-->
                    <input type="hidden" name="id" value="<?= $_GET['update']; ?>" />
                    <!--hidden-->
                    <!-- /.col -->
                    <div class="col-12">
                        <button type="submit" class="style_button" name="addAgent">Update Fuel Agent</button>
                    </div>
                    <!-- /.col -->
            </div>
            </form>

        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
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
