<?php
session_start();
include("../../utils/dbaccess.php");
$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();

$output = array();
//$user_id = $_GET['id'];
// $sql = "select administrators.*,roles.name,user_roles.role_id from administrators right join user_roles on administrators.adminId = user_roles.adminId right join roles on user_roles.role_id = roles.id";


$sql = "select * from user_totals_per_day  ";


$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value']) && !empty($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];

    $sql .= " WHERE today_boda_riders like '%" . $search_value . "%'";
    $sql .= " WHERE today_fuel_stations like '%" . $search_value . "%'";
    $sql .= " WHERE today_boda_stages like '%" . $search_value . "%'";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY id asc";
}

if ($_POST['length'] != -1) {
    $start = $_POST['start'];
    $length = $_POST['length'];
    $sql .= " LIMIT  " . $start . ", " . $length;
}




// var_dump($sql);

// die;/
$query = mysqli_query($con, $sql);

$count_rows = mysqli_num_rows($query);
$data = array();
while ($row = mysqli_fetch_assoc($query)) {
    $sub_array = array();
    $sub_array[] = $row['id'];
    $sub_array[] = $row['today_boda_riders'];
    $sub_array[] = $row['today_fuel_stations'];
    $sub_array[] = $row['today_boda_stages'];
    $sub_array[] = $row['created_at'];
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo  json_encode($output);
