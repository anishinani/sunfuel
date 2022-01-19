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

if (!can('view-fuelstations')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";


startContent();

// code here
breadCrumbs(['title' => 'Fuel Stations', 'sub_title' => 'Fuel Stations', 'previous' => 'Dashboard', 'previous_action' => '../dashboard/']);

?>
<div class="row">
    <div class="col-12">
        <!--table-->
        <!-- /.card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Fuel Station Table</h3>
                <?php
                if (can("create-fuelStations")) {
                ?>
                    <h4 class="float-sm-right ">
                        <a class="btn btn-success" href="./create.php"> Add New Station
                        </a>
                    </h4>
                <?php } ?>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Merchant Code</th>
                            <th>Contact Person</th>
                            <th>Contact Address</th>
                            <th>Contact Phone Number</th>
                            <th>Fuel Station Status</th>
                            <th>Activation Status</th>
                            <th width="220px">Actions</th>
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
