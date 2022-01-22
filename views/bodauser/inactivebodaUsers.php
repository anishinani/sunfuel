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
if (!can('view-bodausers')) echo '<script>window.open("../Errors/unAuthorized.php" , "_self")</script>';


breadCrumbs(['title' => 'Inactive Boda Users', 'sub_title' => 'settings', 'previous' => 'Dashboard', 'previous_action' => '../dashboard/']);

?>
<div class="row">
    <div class="col-12">
        <!--table-->
        <!-- /.card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Boda User Table</h3>

                <div>
                    <form method="post" action="./uploaddetails.php" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="my-input">Upload</label>
                            <input class="form-control-file" type="file" name="file">
                        </div>
                        <h4 class=" ">
                            <button class="btn btn-success" type="submit" name="upload">Upload
                            </button>
                        </h4>
                    </form>
                </div>

                <?php

                if (in_array("create-bodaUsers", $_SESSION['permissions'])) {
                ?>
                    <h4 class="float-sm-right ">
                        <a class="btn btn-success" href="./create.php"> Add New Boda User
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
                            <th>NIN Number</th>
                            <th>Boda Number</th>
                            <th>Role</th>
                            <th style="width: 150px !important;">Boda Status</th>

                            <th>Fuel Station</th>
                            <th>Stage</th>

                            <th>Activation Action</th>

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
                'url': './serversideInactiveBodaUser.php',
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
