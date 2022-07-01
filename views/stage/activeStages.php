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
if (!can('view-stages')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";


breadCrumbs(['title' => 'Stages', 'sub_title' => 'stages', 'previous' => 'Home', 'previous_action' => '../dashboard/']);


?>

<div class="row">
    <div class="col-12">
        <!--table-->
        <!-- /.card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Stage Table</h3>
                <?php  if(in_array('create-stage' , $_SESSION['permissions'])): ?>
                <h4 class="float-sm-right ">
                    <a class="btn btn-success" href="./create.php"> Add New Stage
                    </a>
                </h4>
                <?php endif; ?>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Stage Name</th>
                            <th>Fuel Station Name</th>
                            <th>Stage Status</th>
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
