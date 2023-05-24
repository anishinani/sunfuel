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

// if (!can('edit-bodausers')) echo '<script>window.open("../Errors/unAuthorized.php" , "_self")</script>';


startContent();

$results  = $dbAccess->select("bodauser", "", ["bodaUserId" => $_GET['update']]);

$fuelstation  =  $dbAccess->select("fuelstation", ["fuelStationId", "fuelStationName"]);

$stage  =  $dbAccess->select("stage", ["stageId", "stageName"]);

$currentStage   =  $dbAccess->select("stage", ["stageId", "stageName"], ["stageId" => $results[0]["stageId"]]);

$currentfuelStaion =   $dbAccess->select("fuelstation", ["fuelStationId", "fuelStationName"], ["fuelStationId" => $results[0]["fuelStationId"]]);

breadCrumbs(['title' => 'Edit Rider Details', 'sub_title' => 'settings', 'previous' => 'Boda Users', 'previous_action' => './index.php']);



?>
<div class="row">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">

            <div class="card-body">
                <p class="login-box-msg">Edit Boda user</p>
                <form method="POST" action="./update.php">
                    <div class="form-group mb-3">
                        <label for="">Boad User Names</label>
                        <input type="text" name="name" required class="form-control" placeholder="enter  names " value="<?= $results[0]["bodaUserName"] ?>" />

                    </div>
                    <!--address-->
                    <div class="form-group mb-3">
                        <label for="">NIN Number</label>
                        <input type="text" name="nin" required class="form-control" placeholder="enter valid nin number" value="<?= $results[0]["bodaUserNIN"] ?>" />
                    </div>
                    <!--address-->
                    <!--person-->
                    <div class="form-group mb-3">
                        <label for=""> Boda Number</label>
                        <input type="text" name="bodaNumber" required class="form-control" placeholder="enter boda name" value="<?= $results[0]["bodaUserBodaNumber"] ?>" />

                    </div>
                    <!--person-->

                    <!--phone-->
                    <div class="form-group mb-3">
                        <label for=""> Phone Number</label>
                        <input type="text" name="phoneNumber" required class="form-control" placeholder="enter phone number " value="<?= $results[0]["bodaUserPhoneNumber"] ?>" />
                    </div>
                    <!---phone-->
                    <!--phone-->
                    <div class="form-group mb-3">
                        <label for=""> Alternative Phone Number</label>
                        <input type="text" name="anotherNumber"  class="form-control" placeholder="enter another number " value="<?= $results[0]["alternativePhotoNumber"] ?>" />
                    </div>
                    <!---phone-->

                    <div class="form-group">
                        <label for="my-select">Select Role</label>
                        <select id="my-select" class="form-control" name="role">
                            <option selected value="<?= $results[0]["bodaUserRole"]; ?>">
                                <?= $results[0]["bodaUserRole"]; ?>
                            </option>
                            <option value="Chairman">Stage Chairman</option>
                            <option value="BodaUser">Boda User</option>
                        </select>
                    </div>


                    <!--fuel station-->
                    <div class="form-group mb-3">
                        <div class="form-group">
                            <label for="my-select">Fuel Station</label>
                            <select id="my-select" class="form-control" name="fuelStationId">
                                <option selected value="<?= $currentfuelStaion[0]["fuelStationId"]; ?>"><?= $currentfuelStaion[0]["fuelStationName"]; ?></option>
                                <?php
                                for ($i = 0; $i < count($fuelstation); $i++) {
                                ?>
                                    <option value="<?= $fuelstation[$i]["fuelStationId"] ?>">
                                        <?= $fuelstation[$i]["fuelStationName"] ?></option>

                                <?php } ?>
                            </select>
                        </div>

                    </div>
                    <!--fuel station-->

                    <!--stage-->
                    <div class="form-group mb-3">
                        <div class="form-group">
                            <label for="my-select">Stage</label>
                            <select id="my-select" class="form-control" name="stageId">
                                <option value="<?= $currentStage[0]["stageId"] ?>" selected><?= $currentStage[0]["stageName"] ?></option>
                                <?php
                                for ($i = 0; $i < count($stage); $i++) {
                                ?>
                                    <option value="<?= $stage[$i]["stageId"] ?>">
                                        <?= $stage[$i]["stageName"] ?></option>

                                <?php } ?>
                            </select>
                        </div>

                    </div>
                    <!--stage-->

                    <input type="hidden" name="id" value="<?= $_GET['update']; ?>" />
                    <!-- /.col -->
                    <div class="col-12 d-flex justify-content-center">
                        <button type="submit" class="style_button btn w-75 btn-primary" name="addBodaUser">Update Boda User</button>
                    </div>
                    <!-- /.col -->
            </div>


            </form>

        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!--form add user-->
</div>
<!-- /.row -->



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

<script type="text/javascript">
    $(function() {
        $("#role").change(function() {
            //alert("clicked");
            let role = $("#role").val();
            //alert(role);
            if (role == "Chairman") {
                $(".showAlternative").removeClass("showAlternative")
            } else {
                //alert("not true");
                $("#another").addClass("showAlternative")
            }
        })
    })
</script>
<script>
    $('#form').on('submit', function(e) {
        e.preventDefault();
        var form = this;
        $.ajax({
            url: "./store.php",
            method: $(form).attr('method'),
            data: new FormData(form),
            processData: false,
            // dataType: 'json',
            contentType: false,
            beforeSend: function() {
                // $(form).find('span.error-text').text('');
                $("#save").html("saving...")
                $("#save").attr("disabled", true);
            },
            success: function(data) {
                //alert(data);
                if (data == "success") {
                    //alert("true");
                    location.href = "./index.php"

                } else {
                    alert("some thing went wrong!! please try again");
                }
                $("#save").html("Save Boda User")
                $("#save").attr("disabled", false);
            }
        });
    });
</script>

<?php
endPage();
