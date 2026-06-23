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

$_SESSION['bool'] = true;

$stageId = $_SESSION['stageId'];
$stageName = $dbAccess->select("stage", ["stageName"], ["stageId" => $stageId])[0]['stageName'];
$result = $dbAccess->selectQuery("SELECT *   FROM loan WHERE stageId=$stageId AND status=0");

startContent();

breadCrumbs(['title' => $stageName . ' Loans', 'sub_title' => $stageName . ' Loans', 'previous' => 'Home', 'previous_action' => './index.php']);

?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $stageName ?> Loan Table</h3>
            </div>
            <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Loan Amount</th>
                            <th>Loan Interest</th>
                            <th>Name</th>
                            <th>Boda Phone Number</th>
                            <th>Fuel Station</th>
                            <th>Agent Name</th>
                            <th>Stage Name</th>
                            <th>Status</th>
                            <th>Created On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        function formatMobileNumber($number)
                        {
                            $newNumber = $number;
                            if (strpos($number, "+256") !== false) {
                                $newNumber = str_replace("+256", "0", $number);
                            }
                            if (strpos($number, "256") !== false) {
                                $newNumber = str_replace("256", "0", $number);
                            }
                            return $newNumber;
                        }

                        for ($i = 0; $i < count($result); $i++) { ?>
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
                                    <button name="show" class="btn btn-danger btn-sm editbtn">UnPaid</button>
                                </td>
                                <td>
                                    <?= $result[$i]['created_at'] ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php

endContent();

include_once '../templates/footer.php';

?>
<script>
    $(function() {
        $('.image__remove').click(function() {
            $("#removeAlert").addClass('platform');
        })
    })
</script>
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
