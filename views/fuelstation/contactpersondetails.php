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

$_SESSION['bool'] = true;

if (!can('view-fuelstations')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";

if (isset($_GET['showPerson'])) {
    $stationId = $_GET['showPerson'];
    $personDetails = $dbAccess->select("fuelstation", "", ["fuelStationId" => $stationId]);
}

startContent();

breadCrumbs(['title' => 'Fuel Station Contact Person Details', 'sub_title' => 'Fuel Station Contact Person Details', 'previous' => 'Fuel Stations', 'previous_action' => './index.php']);

?>
<style>
    .platform {
        display: none !important;
    }

    .image__remove {
        position: absolute !important;
        right: 30px !important;
        top: 10px !important;
        cursor: pointer;
    }

    #removeAlert {
        margin-top: 10px !important;
    }
</style>

<div class="row">
    <div class="col-12">

        <div class="container rounded bg-white mt-5 mb-5">
            <div class="row">
                <div class="col-md-3 border-right">
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        <img class=" mt-1" width="250px" src="<?= "https://app.creditplus.ug/bodafuelprojectmobileappapi/storage/app/public/images/id_cards/" . $personDetails[0]['frontIDPhoto']; ?>">
                        <span class=" font-weight-bold">Front ID Photo</span>
                        <span class="text-black-50"><?= $personDetails[0]['fuelStationContactPerson'] ?></span><span> </span>
                    </div>
                    <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                        <img class=" mt-1" width="250px" src="<?= "https://app.creditplus.ug/bodafuelprojectmobileappapi/storage/app/public/images/id_cards/" . $personDetails[0]['backIDPhoto']; ?>">
                        <span class=" font-weight-bold">Back IDPhoto</span>
                        <span class="text-black-50"><?= $personDetails[0]['fuelStationContactPerson'] ?></span><span> </span>
                    </div>
                </div>
                <div class="col-md-5 border-right">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right"> <?= $personDetails[0]['fuelStationContactPerson'] ?> Details</h4>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6"><label class="labels">Name</label><input type="text" disabled class="form-control" placeholder="first name" value="<?= $personDetails[0]['fuelStationContactPerson'] ?>"></div>
                            <div class="col-md-6"><label class="labels">NIN Number</label><input type="text" disabled class="form-control" placeholder="first name" value="<?= $personDetails[0]['NIN'] ?>"></div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12"><label class="labels">Mobile Number</label>
                                <input type="text" class="form-control" disabled placeholder="enter phone number" value="<?= $personDetails[0]['fuelStationContactPhone'] ?>">
                            </div>
                            <div class="col-md-12"><label class="labels">Fuel Station Name</label>
                                <input type="text" class="form-control" disabled value="<?= $personDetails[0]['fuelStationName'] ?>">
                            </div>


                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php

endContent();

/**
 * footer of the application
 * */
include_once '../templates/footer.php';

?>

<script type="text/javascript">
    $(function() {
        $('.image__remove').click(function() {
            $("#removeAlert").addClass('platform');
        })
    })
</script>
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
