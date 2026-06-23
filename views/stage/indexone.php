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

if (!can('view-stages')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";

$_SESSION['bool'] = true;

startContent();

breadCrumbs(['title' => $_GET['name'] . ' Stage', 'sub_title' => $_GET['data'], 'previous' => 'Home', 'previous_action' => '../bodauser/index.php']);

?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= strtoupper($_GET['data']) ?></h3>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Stage Name</th>
                            <th>Fuel Station Name</th>
                            <th>Stage Status</th>
                            <th>Activation Actions</th>
                            <th width="100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php

endContent();

include_once '../templates/footer.php';

?>
<script>
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
                'url': './serversideActiveStage.php?name=<?= $_GET["name"] ?>&table=<?= $_GET["data"] ?>',
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
