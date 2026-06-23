<?php
ob_start();
try {
include("../../utils/dbaccess.php");
require_once("../../utils/datatables_helper.php");
$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();

$output = array();
$sql = "SELECT * FROM activefuelstation";

//die("here");

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE fuelStationName like '%" . $search_value . "%'";
    $sql .= " OR fuelStationContactPhone like '%" . $search_value . "%'";
    $sql .= " OR fuelStationContactPerson like '%" . $search_value . "%'";
    $sql .= " OR fuelStationAddress like '%" . $search_value . "%'";
    $sql .= " OR fuelStationStatus like '%" . $search_value . "%'";
}

$sql .= datatables_order_clause($_POST['order'] ?? null, ['fuelStationId', 'fuelStationName', 'fuelStationContactPerson', 'fuelStationAddress', 'fuelStationContactPhone', 'fuelStationStatus'], 'fuelStationId DESC');

if ($_POST['length'] != -1) {
    $start = $_POST['start'];
    $length = $_POST['length'];
    $sql .= " LIMIT  " . $start . ", " . $length;
}

$query = mysqli_query($con, $sql);
$count_rows = mysqli_num_rows($query);
$data = array();
while ($row = mysqli_fetch_assoc($query)) {
    $sub_array = array();
    $sub_array[] = $row['fuelStationId'];
    $sub_array[] = $row['fuelStationName'];
    $sub_array[] = $row['fuelStationContactPerson'];
    $sub_array[] = $row['fuelStationAddress'];
    $sub_array[] = $row['fuelStationContactPhone'];
    $sub_array[] = $row['fuelStationStatus'] == 0 ? "Not Active" : "Active";
    $sub_array[] = $row['fuelStationStatus'] == 0 ? '
    <form action="activateStation.php" method="post">
    <input type="hidden" name="id" value="' . $row['fuelStationId'] . '"/>
    <button type="submit" name="activate" 
    class="btn btn-info btn-sm editbtn" >Activate</button>
    ' : '    <form action="deactivateStation.php" method="post">
    <input type="hidden" name="id" value="' . $row['fuelStationId'] . '"/>
    <button type="submit" name="deactivate"  
    class="btn btn-danger btn-sm editbtn" >DeActivate</button>
    ';

    $sub_array[] = '<div style="display:flex;align-items:center;justify-content:space-between;">
     <form action="edit.php?id="' . $row['fuelStationId'] . '"" method="get">
     <button type="submit" name="update"  value="' . $row['fuelStationId'] . '"
     class="btn btn-info btn-sm editbtn" >Edit</button>

     </form>
     <form method="POST" action="./delete.php">
       <input type="hidden" name="id" value="' . $row['fuelStationId'] . '"/>
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
} catch (Throwable $e) {
    datatables_json_error($e);
}
