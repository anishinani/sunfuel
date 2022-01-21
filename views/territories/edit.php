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

if (!can('edit-territories')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";

if (!isset($_GET['update'])) echo "<script>window.open('../Errors/404.php','_self')</script>";

$id = $_GET["update"];

require_once  '../../controllers/TerritoryController.php';

$territoryController = new TerritoryController();

$territory = $territoryController->getTerritory($id);

if (is_null($territory)) echo "<script>window.open('../Errors/404.php','_self')</script>";

$codes = array();

foreach($territory['districts'] as $dc) $codes[] = $dc['districtCode'];

breadCrumbs(['title' => 'Edit Territory', 'sub_title' => 'Edit Territory', 'previous' => 'Territories', 'previous_action' => './index.php']);

include_once "../../utils/pageFunctions.php";

?>

<div class="row">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">

            <div class="card-body">
                <p class="login-box-msg">Edit Territory</p>
                <form method="POST" id="form">
                    <div class="form-group">
                        <label for="territoryName"> Territory Name</label>
                        <input value="<?= $territory['territory']['territoryName']?>" type="text" class="form-control" placeholder="Enter Territory Name" name="territoryName">
                        <span id="territoryName_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="territoryManager"> Territory Manager</label>
                        <select class="form-control form-select" name="territoryManager">
                            <option value="">Choose</option>
                            <?= getUsers('options',$territory['territory']['territoryManager']); ?>
                        </select>
                        <span id="territoryManager_error" class="text-danger"></span>
                    </div>
                    <div class="form-group">
                        <label for="territoryDistricts"> Territory Districts</label>
                        <select id="select-districts" class="form-control form-select" name="territoryDistricts[]" multiple>
                            <?= getDistrictsSelected($codes); ?>
                        </select>
                        <span id="territoryManager_error" class="text-danger"></span>
                    </div>
                    <input type="text" class="d-none" name="territoryId"   value="<?=$_GET['update']?>" >
                    <!-- /.col -->
                    <div class="col-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-block py-1" name="UpdateTerritory" id="save">Save Territory</button>
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
<script src=" /creditpluswebapp/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript">
    $(function() {
        $("#select-districts").select2();
    })
</script>
<script>
    $('#form').on('submit', function(e) {
        e.preventDefault();
        var form = this;
        $.ajax({
            url: "./update.php",
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
                $("#save").html("Save Territory")
                $("#save").attr("disabled", false);
            }
        });
    });
</script>

<?php
endPage();
