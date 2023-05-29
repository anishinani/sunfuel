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
if (!can('view-payments')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";


breadCrumbs(['title' => 'Payments', 'sub_title' => 'Payments', 'previous' => 'Dashboard', 'previous_action' => '../dashboard/']);

?>

<div class="row">
    <div class="col-12">


        <!--table-->
        <!-- /.card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payments Table</h3>
            </div>
            <div class="d-flex justify-content-between m-2">
                <form action="./update.php" method="POST">
                    <button type="submit" name="update"  class="btn btn-info btn-sm editbtn">UPDATE</button>
                </form>    

                <form action="./force_update.php" method="POST">
                    <button type="submit" name="update"  class="btn btn-info btn-sm editbtn">FORCE UPDATE</button>
                </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                             <th>Status</th>  
                            <th>Reason</th>
                            <th>Phone Number</th>
                            <th>Amount</th>
                            <th>Paid On</th>
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
