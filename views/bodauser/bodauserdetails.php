<?php

/**
 * Header of the application
 * @author ThinkxSoftware
 * **/
if (!isset($_GET['bodadetails'])) {
    header('Location:index.php');
}

include_once '../templates/SecurePageHeader.php';
/***
 * reusable components to inject code into the template
 * */
include_once '../templates/Components.php';

if (!can('view-bodausers')) echo '<script>window.open("../Errors/unAuthorized.php" , "_self")</script>';


startContent();

// code here


$bodaDetails =  $dbAccess->select("bodauser", "", ["bodaUserId" => $_GET['bodadetails']]);
 
$user_details =  $dbAccess->select('users', [], ['adminId'=>$bodaDetails[0]['user_id']]);




breadCrumbs(['title' => 'Boda Rider Details', 'sub_title' => 'Boda Rider Details', 'previous' => 'Boda Users', 'previous_action' => './index.php']);

//include_once "../templates/flashMessages.php";
?>



<div class="row">
    <div class="col-12">

        <div class="rounded bg-white mt-5 mb-5">
            <div class="row">
                <div class="col-md-3 border-right">
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        <img class=" mt-1" width="250px" src="<?= "https://app.creditplus.ug/bodafuelprojectmobileappapi/storage/app/public/images/id_cards/" . $bodaDetails[0]['bodaUserFrontPhoto']; ?>"><span class=" font-weight-bold">Front ID Photo</span>
                        <span class="text-black-50"><?= $bodaDetails[0]['bodaUserName'] ?></span><span> </span>
                    </div>
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        <img class=" mt-1" width="250px" src="<?= "https://app.creditplus.ug/bodafuelprojectmobileappapi/storage/app/public/images/id_cards/" . $bodaDetails[0]['bodaUserBackPhoto']; ?>">
                        <span class=" font-weight-bold">Back IDPhoto</span>
                        <span class="text-black-50"><?= $bodaDetails[0]['bodaUserName'] ?></span><span> </span>
                    </div>
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        <img class=" mt-1" width="250px" src="<?= "https://app.creditplus.ug/bodafuelprojectmobileappapi/storage/app/public/images/id_cards/" . $bodaDetails[0]['riderPhoto']; ?>"><span class=" font-weight-bold">Rider Photo</span>
                        <span class="text-black-50"><?= $bodaDetails[0]['bodaUserName'] ?></span><span> </span>
                    </div>
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        <img class=" mt-1" width="250px" src="<?= "https://app.creditplus.ug/bodafuelprojectmobileappapi/storage/app/public/images/id_cards/" . $bodaDetails[0]['motorcylePhoto']; ?>">
                        <span class=" font-weight-bold">Motor Cycle Photo</span>
                        <span class="text-black-50"><?= $bodaDetails[0]['bodaUserName'] ?></span><span> </span>
                    </div>
                </div>
                <div class="col-md-4 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right"> <?= $bodaDetails[0]['bodaUserName'] ?> Details</h4>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6"><label class="labels">Name</label><input type="text" disabled class="form-control" placeholder="first name" value="<?= $bodaDetails[0]['bodaUserName'] ?>"></div>
                            <div class="col-md-6"><label class="labels">NIN Number</label><input type="text" disabled class="form-control" placeholder="first name" value="<?= $bodaDetails[0]['bodaUserNIN'] ?>"></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Mobile Number</label>
                                <input type="text" class="form-control" disabled placeholder="enter phone number" value="<?= $bodaDetails[0]['bodaUserPhoneNumber'] ?>">
                            </div>
                            <div class="col-md-12"><label class="labels">Other Number</label>
                                <input type="text" class="form-control" disabled value="<?= $bodaDetails[0]['alternativePhotoNumber'] ?>">
                            </div>
                            <div class="col-md-12"><label class="labels">Boda Number</label>
                                <input type="text" class="form-control" disabled value="<?= $bodaDetails[0]['bodaUserBodaNumber'] ?>">
                            </div>

                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6"><label class="labels">Role</label><input type="text" class="form-control" disabled value="<?= $bodaDetails[0]['bodaUserRole'] ?>"></div>
                            <div class="col-md-6"><label class="labels">Stage</label><input type="text" class="form-control" value="<?= $dbAccess->select("stage", ['stageName'], ['stageId' => $bodaDetails[0]['stageId']])[0]['stageName']
                                                                                                                                    ?>" disabled></div>
                        </div>

                    </div>
                </div>

                <div class="col-md-2">
                    <div class="py-5">

                        <div class="col-md-12"><label class="labels">Fuel Station</label>
                            <input type="text" class="form-control" disabled value="<?= $dbAccess->select("fuelstation", ['fuelStationName'], ['fuelStationId' => $bodaDetails[0]['fuelStationId']])[0]['fuelStationName'] ?>">
                        </div> <br>
                        <div class="col-md-12"><label class="labels">Boda User Status</label>
                            <?php
                            if ($bodaDetails[0]["bodaUserStatus"] == 0) {
                            ?>
                                <form action="activateBoda.php" method="post">
                                    <input type="hidden" name="id" value="<?= $bodaDetails[0]["bodaUserId"]; ?>" />
                                    <input type="hidden" name="stageId" value="<?= $bodaDetails[0]["stageId"] ?>" />
                                    <button type="submit" name="activate" class="btn btn-info btn-sm editbtn">Activate</button>
                                </form>
                            <?php } else { ?>
                                <form action="deactivateBoda.php" method="post">
                                    <input type="hidden" name="id" value="<?= $bodaDetails[0]["bodaUserId"]; ?>" />
                                    <button type="submit" name="deactivate" class="btn btn-danger btn-sm editbtn">DeActivate</button>
                                </form>

                            <?php } ?>

                        </div>
                        <!-- reset pin -->
                        <div class="col-md-12"><label class="labels">Reset Pin</label>
                            <form action="resetpin.php" method="post">
                                <input type="hidden" name="bodaUserId" value="<?= $bodaDetails[0]["bodaUserId"]; ?>" /> 
                                <button type="submit" 
                                name="reset" 
                                class="btn btn-info btn-sm editbtn"
                                onclick="return confirm('Are you sure you want to reset pin?');"
                                data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing">Reset Pin</button>
                            </form>

                        </div>
                        <!-- reset pin -->
                    </div>
                </div>
                <div class="col">
                    <div class="py-5">

                        <div class="col-md-12"><label class="labels">OnBoarded By</label>
                            <input type="text" class="form-control" style="width:100%" disabled value="<?= $user_details[0]['name']?>">
                        </div> <br>

                        <div class="col-md-12"><label class="labels">Agent Phone Number</label>
                            <input type="text" class="form-control" style="width:100%"  disabled value="<?= $user_details[0]['phoneNumber']?>">
                        </div> <br>

                        <div class="col-md-12"><label class="labels">Agent Email</label>
                            <input type="text" class="form-control" style="width:100%" disabled value="<?= $user_details[0]['email']?>">
                        </div> <br>

                        <div class="col-md-12"><label class="labels">Date OnBoard</label>
                            <input type="text" class="form-control" style="width:100%" disabled value="<?= $bodaDetails[0]['created_at']?>">
                        </div> <br>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

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
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                $(nRow).attr('id', aData[0]);
            },
            'serverSide': 'true',
            'processing': 'true',
            'paging': 'true',
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            'order': [],
            'ajax': {
                'url': './serverside.php',
                'type': 'post',
            },
            "columnDefs": [{
                'target': [5],
                'orderable': false,
            }]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
<?php
endPage();





