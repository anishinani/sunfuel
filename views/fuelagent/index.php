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

if (!can('view-fuelagents')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";


startContent();

// code here


breadCrumbs(['title' => 'Fuel Agents', 'sub_title' => 'Fuel Agents', 'previous' => 'Dashboard', 'previous_action' => '../dashboard/']);

?>

<div class="row">
    <div class="col-12">
        <!--table-->
        <!-- /.card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Fuel Agent Table</h3>
                <?php if(can("create-fuelagent")): ?>
                <h4 class="float-sm-right ">
                    <a class="btn btn-success" href="./create.php"> Add New Fuel Agent
                    </a>
                </h4>
                <?php endif; ?>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th> Fuel Agent Name</th>
                            <th>Fuel Agent NIN Number</th>
                            <th>Fuel Agent Phone Number</th>
                            <th>Another Phone Number</th>
                            <th>Fuel Agent Station</th>
                            <th>Fuel Agent Status</th>
                            <th>Activation Status</th>
                            <th width="130px">Actions</th>
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
            "columnDefs": [{
                'target': [5],
                'orderable': false,
            }]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>


<?php
endPage();
