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

startContent();

breadCrumbs(['title' => $_GET['stagename'] . ' Stage', 'sub_title' => $_GET['data'], 'previous' => 'Stage', 'previous_action' => './index.php']);

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
                            <th>ID</th>
                            <th> Name</th>
                            <th>NIN Number</th>
                            <th>Boda Number</th>
                            <th>Role</th>
                            <th>Boda Status</th>
                            <th>Fuel Station</th>
                            <th>Stage</th>
                            <th>Activation Action</th>
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
                'url': "./serverside/stagebodaserverside.php?stagename=<?= $_GET['stagename'] ?>&table=<?= $_GET['data'] ?>",
                'type': 'post',
            },
            "data": {
                "id": 1
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
