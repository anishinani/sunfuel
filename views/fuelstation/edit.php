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

//select from db by id
if (isset($_GET['update'])) {
    $id = $_GET["update"];
    $results = $dbAccess->select("fuelstation", "", ["fuelStationId" => $id]);
}

startContent();

breadCrumbs(['title' => 'Edit Fuel Station', 'sub_title' => 'Edit Fuel Station', 'previous' => 'Fuel Stations', 'previous_action' => './index.php']);

?>
<?php if (isset($_SESSION['errors'])) { ?>
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            <?php
            foreach ($_SESSION['errors'] as $key => $value) {
                echo "<li>" . $value . "</li>";
            }
            ?>
        </ul>
    </div>
<?php } ?>

<div class="row">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">

            <div class="card-body">
                <p class="login-box-msg">Edit station</p>
                <form method="POST" action="./update.php">


                    <div class="form-group mb-3">
                        <label for="">Station Name</label>
                        <input type="text" name="name" required class="form-control" placeholder="enter station name" value="<?= $results[0]['fuelStationName']; ?>" />

                    </div>
                    <!--address-->
                    <div class="form-group mb-3">
                        <label for="">Addresss</label>
                        <input type="text" name="address" required class="form-control" placeholder="enter station address" value="<?= $results[0]['fuelStationAddress']; ?>" />
                    </div>
                    <!--address-->
                    <!--bank name-->
                    <div class="form-group mb-3">
                        <label for="">Bank Name</label>
                        <input type="text" name="bankname" required class="form-control"
                            value="<?= $results[0]['bankName']; ?>"
                            placeholder="enter station bank name"
                        />
                    </div>
                    <!--bank name-->
                    <!--Bank Branch-->
                    <div class="form-group mb-3">
                        <label for="">Bank Branch</label>
                        <input type="text" name="bankbranch"
                            value="<?= $results[0]['bankBranch']; ?>"
                            required class="form-control" placeholder="enter station Bank Branch" />
                    </div>
                    <!--Bank Branch-->
                    <!--Account Name-->
                    <div class="form-group mb-3">
                        <label for="">Account Name</label>
                        <input type="text" name="accountname" required class="form-control"
                            value="<?= $results[0]['AccName']; ?>"
                            placeholder="enter station Account Name" />
                    </div>
                    <!--Account Name-->
                    <!--Account Number-->
                    <div class="form-group mb-3">
                        <label for="">Account Number</label>
                        <input type="text" name="accountnumber"
                            value="<?= $results[0]['AccNumber']; ?>"
                            required class="form-control" placeholder="enter station Account Number" />
                    </div>
                    <!--Account Number-->
                    <!--person-->
                    <div class="form-group mb-3">
                        <label for="">Contact Person</label>
                        <input type="text" name="person" required class="form-control" placeholder="enter person name" value="<?= $results[0]['fuelStationContactPerson']; ?>" />

                    </div>
                    <!--person-->

                    <!--phone-->
                    <div class="form-group mb-3">
                        <label for=""> Contact Phone Number</label>
                        <input type="text" name="phoneNumber" required class="form-control" placeholder="enter phone number name" value="<?= $results[0]['fuelStationContactPhone']; ?>" />
                    </div>
                    <!---phone-->

                    <!--hidden-->
                    <input type="hidden" name="id" value="<?= $_GET['update']; ?>" />
                    <!--hidden-->
                    <!-- /.col -->
                    <div class="col-12">
                        <button type="submit" class="style_button" name="addStation">Update Fuel Station</button>
                    </div>
                    <!-- /.col -->
            </div>
            </form>

        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>
<!-- /.register-box -->

<?php

endContent();

/**
 * footer of the application
 * */
include_once '../templates/footer.php';

endPage();
