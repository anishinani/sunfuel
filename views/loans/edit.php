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

if (!can('edit-fuelstation')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";

if (!isset($_GET['update'])) echo "<script>window.open('../Errors/404.php','_self')</script>";

$id = $_GET["update"];
$results = $dbAccess->select("fuelstation", "", ["fuelStationId" => $id]);

startContent();

breadCrumbs(['title' => 'Edit Fuel Station', 'sub_title' => 'Edit Fuel Station', 'previous' => 'Home', 'previous_action' => '../fuelstation/index.php']);

?>

<div class="row">
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-body">
                <p class="login-box-msg">Edit station</p>
                <form method="POST" action="./update.php">
                    <div class="form-group mb-3">
                        <label for="">Station Name</label>
                        <input type="text" name="name" required class="form-control" placeholder="enter station name" value="<?= $results[0]['fuelStationName']; ?>" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Addresss</label>
                        <input type="text" name="address" required class="form-control" placeholder="enter station address" value="<?= $results[0]['fuelStationAddress']; ?>" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Contact Person</label>
                        <input type="text" name="person" required class="form-control" placeholder="enter person name" value="<?= $results[0]['fuelStationContactPerson']; ?>" />
                    </div>
                    <div class="form-group mb-3">
                        <label for=""> Contact Phone Number</label>
                        <input type="text" name="phoneNumber" required class="form-control" placeholder="enter phone number name" value="<?= $results[0]['fuelStationContactPhone']; ?>" />
                    </div>

                    <input type="hidden" name="id" value="<?= $_GET['update']; ?>" />
                    <div class="col-12">
                        <button type="submit" class="style_button" name="addStation">Update Fuel Station</button>
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
