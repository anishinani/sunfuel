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

if(!can('view-users')) header('Location:../Errors/unAuthorized.php'); 

breadCrumbs(['title' => 'Users', 'sub_title' => 'users', 'previous' => 'Dashboard', 'previous_action' => '../dashboard/']);


startContent();

?>


<div class="row">
    <div class="col-12">
        <!--table-->
        <!-- /.card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Users Table</h3>
                <?php // if (in_array("delete-users", $current_authenticated_user_permissions)) { ?>
                    <h4 class="float-sm-right ">
                        <a class="btn btn-success" href="./create.php"> Add New User
                        </a>
                    </h4>
                <?php //} ?>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Gender</th>
                            <!-- <th>Role</th> -->
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
            "lengthMenu": [
                    [20, 50, 100, 250, 500, -1],
                    [20, 50, 100, 250, 500, "All (Slow)"]
                ],
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
        })
    });
</script>

<?php

endPage();
