<?php
include("../../utils/dbaccess.php");
$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();

$output = array();
$sql = "SELECT * FROM stage ";

//die("here");

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE stageName like '%" . $search_value . "%'";
    $sql .= " OR stageContactPhoneNumber like '%" . $search_value . "%'";
    $sql .= " OR stageContactPerson like '%" . $search_value . "%'";
    $sql .= " OR stageContactPerson like '%" . $search_value . "%'";
    $sql .= " OR stageStatus like '%" . $search_value . "%'";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY stageId asc";
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
    $sub_array[] = $row['stageId'];
    $sub_array[] = $row['stageName'];
    $sub_array[] = $row['stageContactPerson'];
    $sub_array[] = $row['stageContactAddress'];
    $sub_array[] = $row['stageContactPhoneNumber'];
    $sub_array[] = $row['stageStatus'];
    $sub_array[] = '<a href="javascript:void();" data-id="' . $row['stageId'] . '" 
     class="btn btn-info btn-sm editbtn" >Edit</a>  <a href="javascript:void();" data-id="'
        . $row['stageId'] . '" 
      class="btn btn-danger btn-sm deleteBtn" >Delete</a>';
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo  json_encode($output);
