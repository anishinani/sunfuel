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

if (!can('create-fuelstation')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";

startContent();

// code here
$districts  =  $dbAccess->select("districts");

$stage  =  $dbAccess->select("stage", ["stageId", "stageName"]);


breadCrumbs(['title' => 'Create Fuel Station', 'sub_title' => 'Create Fuel Station', 'previous' => 'Fuel Stations', 'previous_action' => './index.php']);
?>
<div class="row">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">

            <div class="card-body">
                <p class="login-box-msg">Register a new station</p>
                <form method="POST" action="" enctype="multipart/form-data" id="form">


                    <!--district-->
                    <div class="form-group">
                        <label for="my-select">Station District</label>
                        <select id="districts" class="form-control" name="district">
                            <option selected disabled>select district</option>
                            <?php
                            for ($i = 0; $i < count($districts); $i++) {
                            ?>
                                <option value="<?= $districts[$i]["districtCode"] ?>">
                                    <?= $districts[$i]["districtName"] ?></option>

                            <?php } ?>
                        </select>
                    </div>
                    <!--district-->

                    <!--count-->
                    <div class="form-group">
                        <label for="my-select">Station County</label>
                        <select id="county" class="form-control" disabled name="county">
                            <option disabled selected>select county</option>

                        </select>
                    </div>
                    <!--count-->
                    <!--subcounty-->
                    <div class="form-group">
                        <label for="subcounty">Station Subcounty</label>
                        <select id="subcounty" class="form-control" disabled name="subcounty">
                            <option>select sub county</option>

                        </select>
                    </div>
                    <!--subcounty-->
                    <!--parish-->
                    <div class="form-group">
                        <label for="my-select">Station Parish</label>
                        <select id="parish" class="form-control" disabled name="parish">
                            <option selected disabled>select parish</option>

                        </select>
                    </div>
                    <!--parish-->
                    <!--village-->
                    <div class="form-group">
                        <label for="my-select">Station Village</label>
                        <select id="village" class="form-control" disabled name="village">
                            <option selected disabled>select village</option>

                        </select>
                    </div>
                    <!--village-->
                    <div class="form-group mb-3">
                        <label for="">Station Name</label>
                        <input type="text" name="name" required class="form-control" placeholder="enter station name" />

                    </div>
                    <!--address-->
                    <div class="form-group mb-3">
                        <label for="">Station Address</label>
                        <input type="text" name="address" required class="form-control" placeholder="enter station address" />
                    </div>
                    <!--address-->
                    <!--bank name-->
                    <div class="form-group mb-3">
                        <label for="">Bank Name</label>
                        <input type="text" name="bankname" required class="form-control" placeholder="enter station bank name" />
                    </div>
                    <!--bank name-->
                    <!--Bank Branch-->
                    <div class="form-group mb-3">
                        <label for="">Bank Branch</label>
                        <input type="text" name="bankbranch" required class="form-control" placeholder="enter station Bank Branch" />
                    </div>
                    <!--Bank Branch-->
                    <!--Account Name-->
                    <div class="form-group mb-3">
                        <label for="">Account Name</label>
                        <input type="text" name="accountname" required class="form-control" placeholder="enter station Account Name" />
                    </div>
                    <!--Account Name-->
                    <!--Account Number-->
                    <div class="form-group mb-3">
                        <label for="">Account Number</label>
                        <input type="text" name="accountnumber" required class="form-control" placeholder="enter station Account Number" />
                    </div>
                    <!--Account Number-->

                    <!--person-->
                    <div class="form-group mb-3">
                        <label for="">Contact Person</label>
                        <input type="text" name="person" required class="form-control" placeholder="enter person name" />

                    </div>
                    <!--person-->
                    <!--person-->
                    <div class="form-group mb-3">
                        <label for="">Contact Person NIN </label>
                        <input type="text" name="nin" required class="form-control" placeholder="enter person nin number" />

                    </div>
                    <!--person-->

                    <!--phone-->
                    <div class="form-group mb-3">
                        <label for=""> Contact Phone Number</label>
                        <input type="text" name="phoneNumber" required class="form-control" placeholder="enter phone number name" />
                    </div>
                    <!---phone-->
                    <!--front photo-->
                    <div class="form-group mb-3">
                        <label for=""> Contact Person Front ID Photo</label>
                        <input type="file" name="frontPhoto" required class="form-control" accept="image/*" />
                    </div>
                    <!--front photo-->

                    <!--back photo-->
                    <div class="form-group mb-3">
                        <label for="">Contact Person Back ID Photo</label>
                        <input type="file" name="backPhoto" required class="form-control" accept="image/*" />
                    </div>
                    <!--back photo-->

                    <!-- /.col -->
                    <div class="col-12 d-flex justify-content-center">
                        <button type="submit" class="style_button btn btn-primary w-75 " name="addStation" id="save">save fuel station</button>
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

<script>
    //alert("here")
    $(document).ready(function() {
        //alert("here");

        $("#districts").change(function() {
            let district = $("#districts").val();
            //alert(district);
            //alert("changed")
            $.ajax({
                url: "./fetchcounties.php",
                method: "post",

                data: {
                    district: district,
                    action: "fetch"
                },
                dataType: "json",
                beforeSend: function() {
                    $("#county").html('<option disabled selected>select county</option>');
                },
                success: function(data) {
                    $("#county").attr('disabled', false);
                    $("#village").attr('disabled', true);
                    $("#subcounty").attr('disabled', true);
                    $("#parish").attr('disabled', true);

                    $.each(data, function(key, value) {
                        $("#county").append('<option value=' + value.countyCode + '>' + value.countyName + '</option>');
                    });
                }

            })
        })
    })
</script>

<script>
    $(document).ready(function() {
        $("#county").change(function() {

            let subcounty = $("#county").val();
            let district = $("#districts").val();
            //alert(subcounty);
            $.ajax({
                url: "fetchsubcounties.php",
                method: 'post',
                dataType: "json",
                data: {
                    action: "fetch",
                    subcounty: subcounty,
                    district: district
                },
                beforeSend: function() {
                    $("#subcounty").html('<option disabled selected>select sub county</option>');
                },
                success: function(data) {
                    $("#subcounty").attr('disabled', false);
                    //console.log(data);
                    //alert(data);
                    //$("#subcounty").append('<option value=' + value.subCountyCode + '>' + value.subCountyName + '</option>');
                    $.each(data, function(key, value) {
                        $("#subcounty").append('<option value=' + value.subCountyCode + '>' + value.subCountyName + '</option>');
                    });
                }

            })

        })
    })
</script>

<script>
    $(document).ready(function() {
        $("#subcounty").change(function() {

            let district = $("#districts").val();
            let parish = $("#subcounty").val();
            let county = $("#county").val();
            //alert(parish);
            $.ajax({
                url: "fetchparishes.php",
                method: 'post',
                dataType: "json",
                data: {
                    action: "fetch",
                    parish: parish,
                    district: district,
                    county: county
                },
                beforeSend: function() {
                    $("#parish").html('<option disabled selected>select parish</option>');
                },
                success: function(data) {
                    $("#parish").attr('disabled', false);
                    $.each(data, function(key, value) {
                        $("#parish").append('<option value=' + value.parishCode + '>' + value.parishName + '</option>');
                    });
                }

            })

        })
    })
</script>

<script>
    $(document).ready(function() {
        $("#parish").change(function() {
            let district = $("#districts").val();
            let subcounty = $("#subcounty").val();
            let county = $("#county").val();


            let parish = $("#parish").val();


            $.ajax({
                url: "fetchvillages.php",
                method: 'post',
                dataType: "json",
                data: {
                    action: "fetch",
                    parish: parish,
                    district: district,
                    subcounty: subcounty,
                    county: county
                },
                beforeSend: function() {
                    $("#village").html('<option disabled selected>select villages</option>');
                },
                success: function(data) {
                    //salert(data);
                    $("#village").attr('disabled', false);
                    $.each(data, function(key, value) {
                        $("#village").append('<option value=' + value.villageCode + '>' + value.villageName + '</option>');
                    });
                }

            })

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
                $(form).find('span.error-text').text('');
                $("#save").html("saving...")
                $("#save").attr("disabled", true);
            },
            success: function(data) {
                //alert(data);
                if (data == "success") {
                    //alert("true");
                    location.href = "./index.php"

                } else {
                    alert("some thing went wrong");
                }
                $("#save").html("Save Fuel Station")
                $("#save").attr("disabled", false);
            }
        });
    });
</script>

<?php
endPage();
