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
if (!can('create-stage')) echo '<script>window.open("../Errors/unAuthorized.php" , "_self")</script>';

$territories = $dbAccess->select("territories", ["territoryId", "territoryName"]);
$results = $dbAccess->select("fuelstation", ["fuelStationId", "fuelStationName"]);

breadCrumbs(['title' => 'Create Stage', 'sub_title' => 'Create Stages', 'previous' => 'Stages', 'previous_action' => './index.php']);


?>

<div class="row">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">

            <div class="card-body">
                <p class="login-box-msg">Register a new stage</p>
                <form method="POST" id="form">

                    <div class="form-group mb-3">
                        <label for="">Stage Name</label>
                        <input type="text" name="name" required class="form-control" placeholder="enter stage name" />
                    </div>

                    <div class="form-group mb-3">
                        <label for="">Stage Location</label>
                        <input type="text" name="location" class="form-control" placeholder="enter stage location (optional)" />
                    </div>

                    <!--territory-->
                    <div class="form-group mb-3">
                        <label for="territoryId">Territory</label>
                        <select id="territoryId" class="form-control" name="territoryId" required>
                            <option disabled selected>select territory</option>
                            <?php
                            for ($i = 0; $i < count($territories); $i++) {
                            ?>
                                <option value="<?= $territories[$i]["territoryId"] ?>">
                                    <?= $territories[$i]["territoryName"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <!--territory-->

                    <!--fuel station-->
                    <div class="form-group mb-3">
                        <label for="fuelStationId">Fuel Station</label>
                        <select id="fuelStationId" class="form-control" name="fuelStationId" required>
                            <option disabled selected>select fuel station</option>
                            <?php
                            for ($i = 0; $i < count($results); $i++) {
                            ?>
                                <option value="<?= $results[$i]["fuelStationId"] ?>">
                                    <?= $results[$i]["fuelStationName"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <!--fuel station-->

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
    $(document).ready(function() {
        // Simple form validation
        $('#form').on('submit', function(e) {
            var stageName = $('input[name="name"]').val();
            var territoryId = $('select[name="territoryId"]').val();
            var fuelStationId = $('select[name="fuelStationId"]').val();
            
            if (!stageName || !territoryId || !fuelStationId) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return false;
            }
        });
    });
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
