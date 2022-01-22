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

if (!can('create-fuelagent')) echo '<script>window.open("../Errors/unAuthorized.php","_self")</script>';

$results  =  $dbAccess->select("fuelstation", ["fuelStationId", "fuelStationName"]);


startContent();

// code here


breadCrumbs(['title' => 'Add Fuel Agent', 'sub_title' => 'Add Fuel Agent', 'previous' => 'Fuel Agents', 'previous_action' => './index.php']);


?>
<div class="row">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">

            <div class="card-body">
                <p class="login-box-msg">Register a new Fuel Agent</p>
                <form method="POST" action="./store.php" enctype="multipart/form-data">


                    <div class="form-group mb-3">
                        <label for="">Agent Name</label>
                        <input type="text" name="name" required class="form-control" placeholder="enter agent name" />

                    </div>

                    <!--person-->
                    <div class="form-group mb-3">
                        <label for="">Agent NIN Number</label>
                        <input type="text" name="nin" required class="form-control" placeholder="enter nin number" />

                    </div>
                    <!--person-->

                    <!--phone-->
                    <div class="form-group mb-3">
                        <label for=""> Agent Phone Number</label>
                        <input type="text" name="phoneNumber" required class="form-control" placeholder="enter phone number" />
                    </div>
                    <!---phone-->
                    <!--phone-->
                    <div class="form-group mb-3">
                        <label for=""> Another Phone Number</label>
                        <input type="text" name="anotherPhone" required class="form-control" placeholder="enter another phone number " />
                    </div>
                    <!---phone-->
                    <!--fuel station-->
                    <div class="form-group mb-3">
                        <div class="form-group">
                            <label for="my-select">Fuel Station</label>
                            <select id="my-select" class="form-control" name="station">
                                <option disabled selected>select station</option>
                                <?php
                                for ($i = 0; $i < count($results); $i++) {
                                ?>
                                    <option value="<?= $results[$i]["fuelStationId"] ?>">
                                        <?= $results[$i]["fuelStationName"] ?></option>

                                <?php } ?>
                            </select>
                        </div>

                    </div>
                    <!--fuel station-->
                    <!--front photo-->
                    <div class="form-group mb-3">
                        <label for=""> Front ID Photo</label>
                        <input type="file" name="frontPhoto" required class="form-control" accept="image/*" />
                    </div>
                    <!--front photo-->

                    <!--back photo-->
                    <div class="form-group mb-3">
                        <label for=""> Back ID Photo</label>
                        <input type="file" name="backPhoto" required class="form-control" accept="image/*" />
                    </div>
                    <!--back photo-->


                    <!-- /.col -->
                    <div class="col-12">

                        <button type="submit" class="style_button" name="addAgent">Register Agent</button>
                        <!-- <img src="/creditpluswebapp/dist/img/loader.gif" width="80px" height="80px" /> -->
                    </div>
                    <!-- /.col -->
            </div>

            </form>
            <div class="co1-12"></div>
            <!-- <button id="button">Register Agent</button> -->
            <div id="loader">

            </div>
        </div>



    </div>
    <!-- /.form-box -->
</div><!-- /.card -->


<?php

endContent();

/**
 * footer of the application
 * */
include_once '../templates/footer.php';

/**
 * custom page javascript
 * **/

?>

<script src="text/javascript">
    const spinner = $('#loader');
    $(function() {
        $("#button").click(function() {
            //spinner.show();
            alert("clicked");
        })
    })
</script>
<php endPage();