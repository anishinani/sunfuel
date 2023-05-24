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
$user_id = $_POST['id'];
//check if the id is set
if (!isset($user_id)) {
    $user_id = $_POST['id'];
} else {
    header("Location:index.php");
}
try {
    //code...
    $recents = $dbAccess->select('user_totals_per_day', [], ['user_id' => $user_id]);
    //boda users onboarded today
    //time is in this format 2020-08-20 00:00:00,2023-05-24 07:07:41
    $current_date = date('Y-m-d'); // Get the current date
    $query = "SELECT * FROM bodauser WHERE user_id = $user_id AND DATE_FORMAT(created_at, '%Y-%m-%d') = '$current_date'";
    $today_boda_riders = $dbAccess->selectQuery($query);
    $all_time_boda_riders = $dbAccess->select('bodauser', [], ['user_id' => $user_id]);
} catch (\Throwable $th) {
    //throw $th;
    die($th->getMessage());
}

//get usrer details
$user = $dbAccess->select('users', [], ['adminId' => $user_id]);

breadCrumbs(['title' => $user[0]['name'], 'sub_title' => 'users', 'previous' => 'user', 'previous_action' => '../dashboard/']);
$dbAccess =  new DbAccess();

$today_details =  $dbAccess->select('user_totals', [], ['user_id' => $user_id]);



startContent();





?>
<style>
    .cursor-pointer {
        cursor: pointer !important;
    }

    .info-box {
        cursor: pointer !important;
    }

    a {
        text-decoration: none;
        color: inherit;
    }
</style>
<div class="row">

    <!-- create a simple info card with value of 10 -->
    <div class="col-12 col-sm-6 col-md-3">

        <div class="info-box">

            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-motorcycle"></i></span>

            <div class="info-box-content">
                <a href="./today_riders.php?user_id=<?= $user_id ?>" class="cursor-pointer">
                    <span class="info-box-text">Today's Boda Riders Onboarded</span>

                    <span class="info-box-number">
                        <?= $today_details[0]['daily_boda_riders'] == null ? 0 : $today_details[0]['daily_boda_riders'] ?>

                    </span>

                </a>



            </div>



            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <a href="">

        </a>
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-gas-pump"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Today's Fuel Stations Onboarded</span>
                <span class="info-box-number">
                    <?= $today_details[0]['daily_fuel_stations'] == null ? 0 : $today_details[0]['daily_fuel_stations'] ?>

                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-stop"></i></span>

            <div class="info-box-content">
                <a href="./todays_stages.php?user_id=<?= $user_id ?>" class="cursor-pointer">
                    <span class="info-box-text">Today's Stages Onboarded</span>
                    <span class="info-box-number">
                        <?= $today_details[0]['daily_boda_stages'] == null ? 0 : $today_details[0]['daily_boda_stages'] ?>

                    </span>

                </a>

            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

</div>

<div class="row">

    <!-- create a simple info card with value of 10 -->

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-motorcycle"></i></span>

            <a href="./all_time_users.php?user_id=<?= $user_id ?>" class="cursor-pointer">
                <div class="info-box-content">
                    <span class="info-box-text">All Time Boda Riders Onboarded</span>
                    <span class="info-box-number">
                        <?= $today_details[0]['total_boda_riders'] == null ? 0 : $today_details[0]['total_boda_riders'] ?>

                    </span>
                </div>
            </a>

            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>


    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-gas-pump"></i></span>

            <div class="info-box-content">

                <span class="info-box-text">All Time Fuel Stations Onboarded</span>
                <span class="info-box-number">
                    <?= $today_details[0]['total_fuel_stations'] == null ? 0 : $today_details[0]['total_fuel_stations'] ?>

                </span>



            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-stop"></i></span>

            <div class="info-box-content">
                <a href="./all_time_stages.php?user_id=<?= $user_id ?>" class="cursor-pointer">
                    <span class="info-box-text">All Time Stages Onboarded</span>
                    <span class="info-box-number">
                        <?= $today_details[0]['total_boda_stages'] == null ? 0 : $today_details[0]['total_boda_stages'] ?>

                    </span>
                </a>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>



</div>

<!-- riders onboarded today -->
<!-- /.card -->
<div class="card">

    <!-- recently onboarded -->
    <div class="col-xl-12">
        <div class="card-box">
            <h4 class="header-title mb-3">Boda riders onboarded today</h4>

            <div class="table-responsive">
                <table class="table table-borderless table-hover table-centered m-0">

                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Fuel Station</th>
                            <th>Stage</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach ($today_boda_riders as $row) {


                        ?>
                            <tr>
                                <td><?= $row['bodaUserName'] ?></td>
                                <td><?= $row['bodaUserPhoneNumber'] ?></td>
                                <td>
                                    <?= $dbAccess->select("fuelstation", ['fuelStationName'], ['fuelStationId' => $bodaDetails[0]['fuelStationId']])[0]['fuelStationName'] ?>
                                </td>
                                <td>
                                    <?= $dbAccess->select("stage", ['stageName'], ['stageId' => $bodaDetails[0]['stageId']])[0]['stageName'] ?>
                                </td>
                                <td><?= $row['created_at'] ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div> <!-- end col -->
    <!-- recently onboarder -->

</div>
<!-- /.card -->
<!-- riders onboareder today -->


<!-- alltime -->
<!-- /.card -->
<div class="card">

    <!-- recently onboarded -->
    <div class="col-xl-12">
        <div class="card-box">
            <h4 class="header-title mb-3">Recent On Boardings</h4>

            <div class="table-responsive">
                <table class="table table-borderless table-hover table-centered m-0">

                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Fuel Station</th>
                            <th>Stage</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach ($all_time_boda_riders as $row) {


                        ?>
                            <tr>
                                <td><?= $row['bodaUserName'] ?></td>
                                <td><?= $row['bodaUserPhoneNumber'] ?></td>
                                <td>
                                    <?= $dbAccess->select("fuelstation", ['fuelStationName'], ['fuelStationId' => $row['fuelStationId']])[0]['fuelStationName'] ?>
                                </td>
                                <td>
                                    <?= $dbAccess->select("stage", ['stageName'], ['stageId' => $row['stageId']])[0]['stageName'] ?>
                                </td>
                                <td><?= $row['created_at'] ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div> <!-- end col -->
    <!-- recently onboarder -->

</div>
<!-- /.card -->
<!-- all time -->


<div class="row">

    <!-- cards -->


    <!-- cards -->
    <div class="col-12">
        <!--table-->
        <!-- /.card -->
        <div class="card">

            <!-- recently onboarded -->
            <div class="col-xl-12">
                <div class="card-box">
                    <h4 class="header-title mb-3">Over all Summary</h4>

                    <div class="table-responsive">
                        <table class="table table-borderless table-hover table-centered m-0">

                            <thead class="thead-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Boda Riders</th>
                                    <th>Fuel Stations</th>
                                    <th>Boda Stages</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                foreach ($recents as $row) {

                                ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['today_boda_riders'] ?></td>
                                        <td><?= $row['today_fuel_stations'] ?></td>
                                        <td><?= $row['today_boda_stages'] ?></td>
                                        <td><?= $row['created_at'] ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div> <!-- end col -->
            <!-- recently onboarder -->

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

<!-- <script>
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
                //add user id from a post data

                'url': './userserverside.php?',
                'type': 'post',
            },
            "columnDefs": [{
                'target': [5],
                'orderable': false,
            }]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script> -->

<?php

endPage();
