<?php
include("../../utils/dbaccess.php");
$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();

$output = array();
$sql = "SELECT bodauser.*, fuelstation.fuelStationName, stage.stageName  FROM bodauser 
 INNER JOIN fuelstation ON fuelstation.fuelStationId = bodauser.fuelStationId 
INNER JOIN stage ON stage.stageId=bodauser.stageId";

//die("here");

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE bodaUserName like '%" . $search_value . "%'";
    $sql .= " OR bodaUserPhoneNumber like '%" . $search_value . "%'";
    $sql .= " OR bodaUserBodaNumber like '%" . $search_value . "%'";
    $sql .= " OR bodaUserPin like '%" . $search_value . "%'";
    $sql .= " OR fuelStationName like '%" . $search_value . "%'";
    $sql .= " OR stageName like '%" . $search_value . "%'";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY bodaUserId asc";
}

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
    $sub_array[] = $row['bodaUserId'];
    $sub_array[] = $row['bodaUserName'];
    $sub_array[] = $row['bodaUserNIN'];
    $sub_array[] = $row['bodaUserBodaNumber'];
    $sub_array[] = $row['bodaUserPhoneNumber'];
    $sub_array[] = $row['fuelStationName'];
    $sub_array[] = $row['stageName'];
    $sub_array[] = '<div style="display:flex;align-items:center;justify-content:space-between;">
    <form action="edit.php?id="' . $row['bodaUserId'] . '"" method="get">
    <button type="submit" name="update"  value="' . $row['bodaUserId'] . '"
    class="btn btn-info btn-sm editbtn" >Edit</button>

    </form>
    <form method="POST" action="./delete.php">
      <input type="hidden" name="id" value="' . $row['bodaUserId'] . '"/>
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
