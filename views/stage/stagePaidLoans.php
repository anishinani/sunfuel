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

if (!can('view-stages')) echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";


startContent();

// code here

$stageId =  $_SESSION['stageId'];
$stageName =  $dbAccess->select("stage", ["stageName"], ["stageId" => $stageId])[0]['stageName'];
$result = $dbAccess->selectQuery("SELECT *   FROM loan WHERE stageId=$stageId AND status=0");

breadCrumbs(['title' => $stageName . ' Loan', 'sub_title' => $stageName . ' Loan', 'previous' => 'Home', 'previous_action' => '../loans/index.php']);


?>
<div class="row">
    <div class="col-12">
        <!--table-->
        <!-- /.card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $stageName ?> Loan Table</h3>
                <!-- <h4 class="float-sm-right ">
                                        <a class="btn btn-success" href="./create.php"> Add New Station
                                        </a>
                                    </h4> -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Loan Amount</th>
                            <th>Loan Interest</th>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Fuel Station</th>
                            <th>Agent Name</th>
                            <th>Stage Name</th>
                            <th>Status</th>
                            <th>Paid On</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        function formatMobileNumber($number)
                        {

                            $newNumber = $number;
                            if (strpos($number, "+256") !== false) {
                                $newNumber =   str_replace("+256", "0", $number);
                            }
                            if (strpos($number, "256") !== false) {
                                $newNumber =  str_replace("256", "0", $number);
                            }
                            return $newNumber;
                        }

                        for ($i = 0; $i < count($result); $i++) {  ?>

                            <tr>
                                <td><?= $result[$i]['loanAmount'] ?></td>
                                <td><?= $result[$i]['LoanInterest'] ?></td>
                                <td><?= $dbAccess->select("bodauser", ['bodaUserName'], ['bodaUserPhoneNumber' => formatMobileNumber($result[$i]['boadUserId'])])[0]['bodaUserName'] ?></td>
                                <td><?= $result[$i]['boadUserId'] ?></td>
                                <td><?=
                                    count($dbAccess->select("fuelstation", ['fuelStationName'], ['fuelStationId' => $result[$i]['fuelSationId']]))
                                        ? $dbAccess->select("fuelstation", ['fuelStationName'], ['fuelStationId' => $result[$i]['fuelSationId']])[0]['fuelStationName'] : NULL
                                    ?></td>
                                <td>
                                    <?=
                                    count($dbAccess->select("fuelagent", ['fuelAgentName'], ['fuelAgentId' => $result[$i]['agentId']]))
                                        ? $dbAccess->select("fuelagent", ['fuelAgentName'], ['fuelAgentId' => $result[$i]['agentId']])[0]['fuelAgentName'] : NULL;
                                    ?>
                                </td>

                                <td>
                                    <?=
                                    count($dbAccess->select("stage", ['stageName'], ['stageId' => $result[$i]['stageId']])) ?
                                        $dbAccess->select("stage", ['stageName'], ['stageId' => $result[$i]['stageId']])[0]['stageName'] : NULL;
                                    ?>
                                </td>
                                <td>
                                    <button name="show" class="btn btn-success btn-sm editbtn">Paid</button>
                                </td>
                                <td>
                                    <?= $result[$i]['updated_at'] ?>
                                </td>
                            </tr>


                        <?php    }
                        ?>

                    </tbody>

                </table>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
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
    $(function() {
        $("#example").DataTable({
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
