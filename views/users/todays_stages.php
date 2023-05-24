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

if (!can('view-users')) header('Location:../Errors/unAuthorized.php');

breadCrumbs(['title' => 'Details', 'sub_title' => 'details', 'previous' => 'Dashboard', 'previous_action' => '../dashboard/']);


startContent();

//time is in this format 2020-08-20 00:00:00,2023-05-24 07:07:41
$current_date = date('Y-m-d'); // Get the current date
$user_id = $_GET['user_id'];
$query = "SELECT * FROM stage WHERE user_id = $user_id AND DATE_FORMAT(created_at, '%Y-%m-%d') = '$current_date'";
$today_boda_riders = $dbAccess->selectQuery($query);
$user_details =  $dbAccess->select('users', ['name'], ['adminId' => $user_id]);

// var_dump($user_details[0]['name']);
// die("am here");

?>


<div class="row">
    <div class="col-12">
        <!--table-->
        <!-- /.card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Details Table</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Stage Name</th>
                            <th>OnBoarded By</th>
                            <th>Fuel Station</th>
                            
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($today_boda_riders as $row) {


                        ?>
                            <tr>
                                <td><?= $row['stageName'] ?></td>
                                <td><?= $user_details[0]['name'] ?></td>
                                <td>
                                    <?= $dbAccess->select("fuelstation", ['fuelStationName'], ['fuelStationId' => $row['fuelStationId']])[0]['fuelStationName'] ?>
                                </td>
                                <td><?= $row['created_at'] ?></td>
                            </tr>
                        <?php
                        }
                        ?>



                    </tbody>

                </table>
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

//include_once "../templates/optimized_page_scripts.php";

/**
 * footer of the application
 * */
include_once '../templates/footer.php';

/**
 * custom page javascript
 * **/
?>
<script src="/creditpluswebapp/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/creditpluswebapp/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/creditpluswebapp/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/creditpluswebapp/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/creditpluswebapp/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/creditpluswebapp/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/creditpluswebapp/plugins/jszip/jszip.min.js"></script>
<script src="/creditpluswebapp/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/creditpluswebapp/plugins/pdfmake/vfs_fonts.js"></script>
<script src="/creditpluswebapp/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/creditpluswebapp/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/creditpluswebapp/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>

<?php

endPage();
