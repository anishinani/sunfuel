<?php

/**
 * Header of the application
 * @author ThinkxSoftware
 * **/
if(isset($_GET['update'])) header('Location:index.php');

include_once '../templates/SecurePageHeader.php';
/***
 * reusable components to inject code into the template
 * */
include_once '../templates/Components.php';


if (!can('create-bodausers')) echo '<script>window.open("../Errors/unAuthorized.php" , "_self")</script>';

startContent();

// code here

$results  =  $dbAccess->select("fuelstation", ["fuelStationId", "fuelStationName"]);

$stage  =  $dbAccess->select("stage", ["stageId", "stageName"]);

breadCrumbs(['title' => 'Create Boda User', 'sub_title' => 'settings', 'previous' => 'Boda Users', 'previous_action' => './index.php']);


?>
<div class="row my-2">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">

            <div class="card-body">
                <p class="login-box-msg">Register a new boda user</p>
                <form method="POST" enctype="multipart/form-data" id="form">


                    <div class="form-group mb-3">
                        <label for="">Boda User Names</label>
                        <input type="text" name="name" required class="form-control" placeholder="enter  names " />

                    </div>
                    <!--address-->
                    <div class="form-group mb-3">
                        <label for="">NIN Number</label>
                        <input type="text" name="nin" required class="form-control" placeholder="enter valid nin number" />
                    </div>
                    <!--address-->
                    <!--person-->
                    <div class="form-group mb-3">
                        <label for=""> Boda Number</label>
                        <input type="text" name="bodaNumber" required class="form-control" placeholder="enter boda number" />

                    </div>
                    <!--person-->

                    <!--phone-->
                    <div class="form-group mb-3">
                        <label for=""> Phone Number</label>
                        <input type="text" name="phoneNumber" required class="form-control" placeholder="enter phone number " />
                    </div>
                    <!---phone-->

                    <!--role-->
                    <div class="form-group">
                        <label for="my-select">Select Role</label>
                        <select class="form-control" name="role" id="role">
                            <option disabled selected>select role</option>
                            <option value="Chairman">Stage Chairman</option>
                            <option value="BodaUser">Boda User</option>
                        </select>
                    </div>


                    <!--phone-->
                    <div class="form-group mb-3 showAlternative" id="another">
                        <label for=""> Alternative Phone Number</label>
                        <input type="text" name="anotherNumber" class="form-control" placeholder="enter another number " />
                    </div>
                    <!---phone-->


                    <!--role-->

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


                    <!--fuel station-->
                    <div class="form-group mb-3">
                        <div class="form-group">
                            <label for="my-select">Fuel Station</label>
                            <select id="my-select" class="form-control" name="fuelStationId">
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

                    <!--stage-->
                    <div class="form-group mb-3">
                        <div class="form-group">
                            <label for="my-select">Stage</label>
                            <select id="my-select" class="form-control" name="stageId">
                                <option disabled selected>choose stage</option>
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
                    <!-- /.col -->
                    <div class="col-12 d-flex justify-content-center">
                        <button type="submit" class="style_button  btn btn-primary w-75 " name="addBodaUser" id="save">Save Boda User</button>
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
