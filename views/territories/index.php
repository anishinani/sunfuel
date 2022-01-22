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
if (!can('view-territories')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";



breadCrumbs(['title' => 'Territories', 'sub_title' => 'Territories', 'previous' => 'Dashboard', 'previous_action' => '../dashboard/']);


?>



<div class="row">
    <div class="col-12">
        <!--table-->
        <!-- /.card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Territories Table</h3>

                <?php

                if (can('create-territories')) {
                ?>
                    <h4 class="float-sm-right ">
                        <a class="btn btn-success" href="./create.php"> Add New Territory
                        </a>
                    </h4>
                <?php } ?>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Id.</th>
                            <th>Territory Name</th>
                            <th>Territory Manager</th>
                            <th>Stages</th>
                            <th>Fuelstations</th>
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
