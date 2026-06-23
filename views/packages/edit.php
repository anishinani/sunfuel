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

if (!can('edit-packages')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";

if (!isset($_GET['update'])) echo "<script>window.open('../Errors/404.php','_self')</script>";

$id = $_GET["update"];
$results = $dbAccess->select("package", "", ["packageId" => $id]);

startContent();

breadCrumbs(['title' => 'Edit Package', 'sub_title' => 'Edit Package', 'previous' => 'Packages', 'previous_action' => './index.php']);

?>

<div class="row">
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-body">
                <p class="login-box-msg">Edit Package</p>
                <form method="POST" action="./update.php">
                    <div class="form-group mb-3">
                        <label for="">Package Name</label>
                        <input type="text" name="name" required class="form-control" placeholder="enter station name" value="<?= $results[0]['packageName']; ?>" />
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Package Amount</label>
                        <input type="text" name="amount" required class="form-control" placeholder="enter station address" value="<?= $results[0]['packageAmount']; ?>" />
                    </div>

                    <input type="hidden" name="id" value="<?= $_GET['update']; ?>" />
                    <div class="col-12">
                        <button type="submit" class="style_button" name="addPackage">Update Package</button>
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
