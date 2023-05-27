<?php
include("../../utils/dbaccess.php");
$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();

function activate()
{
}

$output = array();
$sql = "SELECT stage.*, fuelstation.fuelStationName FROM stage  
INNER JOIN fuelstation ON stage.fuelStationId = fuelstation.fuelStationId";

//die("here");

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE stageName like '%" . $search_value . "%'";
    $sql .= " OR stageStatus like '%" . $search_value . "%'";
    $sql .= " OR fuelStationName like '%" . $search_value . "%'";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY stageId desc";
}

if ($_POST['length'] != -1) {
    $start = $_POST['start'];
    $length = $_POST['length'];
    $sql .= " LIMIT  " . $start . ", " . $length;
}


function showStatus($status)
{
    switch ($status) {
        case 0:
            return "<span style='background-color:#1c478e;border-radius:5px; padding:5px; color:#fff;'>Inactive</span> ";
        case 1:
            return "<span style='background-color:green;border-radius:5px; padding:5px; color:#fff;'>Active</span>";
        case 2:
            return "<span style='background-color:red;border-radius:5px; padding:5px; color:#fff;'>Suspended</span>";
        default:
            return null;
    }
}

function stageStatusAction($status, $row){
    switch ($status) {
        case 0:
            return '<form action="activateStage.php" method="post">
            <input type="hidden" name="id" value="' . $row['stageId'] . '"/>
            <button type="submit" name="activate" 
            class="btn btn-info btn-sm editbtn" >Activate</button>';
        case 1:
            return '<form action="deactivateStage.php" method="post">
            <input type="hidden" name="id" value="' . $row['stageId'] . '"/>
            <button type="submit" name="deactivate"  
            class="btn btn-danger btn-sm editbtn" >DeActivate</button>';
        case 2:
            return '    <form action="unsuspend.php" method="post">
            <input type="hidden" name="id" value="' . $row['stageId'] . '"/>
            <button type="submit" name="unsuspend""  
            class="btn btn-warning btn-sm editbtn" >Un Suspend</button>';
        default:
            return null;
    }
}



$query = mysqli_query($con, $sql);
$count_rows = mysqli_num_rows($query);
$data = array();
while ($row = mysqli_fetch_assoc($query)) {
    $sub_array = array();
    $sub_array[] = $row['stageName'];
    $sub_array[] = $row['fuelStationName'];
    $sub_array[] = showStatus($row['stageStatus']);
    $sub_array[] = stageStatusAction($row['stageStatus'], $row);

    $sub_array[] = '
    
    <div style="display:flex;align-items:center;justify-content:space-between;">
    </form>
    <form method="POST" action="./StageDetails.php">
      <input type="hidden" name="id" value="' . $row['stageId'] . '"/>
      <button 
    class="btn btn-primary btn-sm deleteBtn" name="stageDetails" >Show</button>

    </form>

    <form action="edit.php?id="' . $row['stageId'] . '"" method="get">
    <button type="submit" name="update"  value="' . $row['stageId'] . '"
    class="btn btn-info btn-sm editbtn" >Edit</button>

    </form>
    <form method="POST" action="./delete.php">
      <input type="hidden" name="id" value="' . $row['stageId'] . '"/>
      <button 
    class="btn btn-danger btn-sm deleteBtn" >Delete</button>

    </form>
    </div>';
    $data[] = $sub_array;
}


$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo  json_encode($output);
