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

breadCrumbs(['title' => 'Suspended Stages', 'sub_title' => 'details', 'previous' => 'Dashboard', 'previous_action' => '../dashboard/']);


startContent();

//time is in this format 2020-08-20 00:00:00,2023-05-24 07:07:41
try {
    //code...
    $sql = "SELECT* FROM stage  WHERE stageStatus=2";

$details = $dbAccess->selectQuery($sql);

} catch (\Throwable $th) {
    //throw $th;
    var_dump($th->getMessage());
    die("an error ocuured");
}


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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($details as $row) {
                        ?>
                            <tr>
                                <td><?= $row['stageName'] ?></td>
                                 <td>
                                    <form action="./activate_stage.php" method="post">
                                        <input type="hidden" name="id" value="<?= $row['stageId'] ?>" />
                                        <button type="submit" name="activate" value="<?= $row['stageId'] ?>" class="btn btn-success btn-sm">Activate</button>
                                    </form>
                                 </td>
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
