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

startContent();

breadCrumbs(['title' => 'Territories', 'sub_title' => 'Territories', 'previous' => 'Fuel Stations', 'previous_action' => './index.php']);

?>
<div class="row">
    <div class="col-12">
        <!--table-->
        <!-- /.card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Fuel Stations in a Territory Table

                </h3>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Stage Name</th>
                            <th>Fuel Station Name</th>
                            <th>Station Status</th>
                            <th>Activation Actions</th>
                            <th width="150px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->


            <!-- /.card -->
            <!--table-->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>

<?php

endContent();

/**
 * footer of the application
 * */
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
                'url': './serverside_territory.php?territory=<?= $_GET['territory'] ?>',
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
