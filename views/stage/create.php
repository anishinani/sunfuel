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

startContent();

// code here
$districts  =  $dbAccess->select("districts");

$results  =  $dbAccess->select("fuelstation", ["fuelStationId", "fuelStationName"]);

breadCrumbs(['title' => 'Create Stage', 'sub_title' => 'Create Stages', 'previous' => 'Stages', 'previous_action' => './index.php']);


?>

<div class="row">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">

            <div class="card-body">
                <p class="login-box-msg">Register a new stage</p>
                <form method="POST" id="form">

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
                        <label for="">Stage Name</label>
                        <input type="text" name="name" required class="form-control" placeholder="enter stage name" />

                    </div>
                    <!--fuel station-->


                    <div class="form-group mb-3">
                        <div class="form-group">
                            <label for="my-select">Fuel Station</label>
                            <select id="fuelStationId" class="form-control" name="fuelStationId" disabled>
                                <option disabled selected>select station</option>

                            </select>
                        </div>

                    </div>

                    <!-- /.col -->
                    <div class="col-12">
                        <button type="submit" class="style_button" name="addStage" id="save">Register new Stage</button>
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
            //let subcounty = $("#county").val();
            //alert(subcounty);

            let subcounty = $("#county").val();
            let district = $("#districts").val();
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
                    // $("#fuelStationId").html('<option disabled selected>select fuel station</option>');
                },
                success: function(data) {
                    $("#subcounty").attr('disabled', false);


                    $.each(data, function(key, value) {
                        $("#subcounty").append('<option value=' + value.subCountyCode + '>' + value.subCountyName + '</option>');
                    });

                    //fetch stations
                    $.ajax({
                        url: "fetchstation.php",
                        method: 'post',
                        dataType: "json",
                        data: {
                            action: "fetch",
                            subcounty: subcounty
                        },
                        beforeSend: function() {

                            $("#fuelStationId").html('<option disabled selected>select fuel station</option>');

                        },
                        success: function(data) {
                            $("#fuelStationId").attr('disabled', false);
                            $.each(data, function(key, value) {
                                $("#fuelStationId").append('<option value=' + value.fuelStationId + '>' + value.fuelStationName + '</option>');
                            });
                        }

                    })
                    //fetch stations
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
                    alert("some thing went wrong");
                }
                $("#save").html("Save New Stage")
                $("#save").attr("disabled", false);
            }
        });
    });
</script>
<?php
endPage();
