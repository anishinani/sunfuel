<?php
include("../../utils/dbaccess.php");
$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();

$output = array();
$sql = "SELECT * FROM payments";

// if (isset($_POST['id'])) {
//     die("is there");
// } else {
//     die("not there");
// }

//die("here");

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE amount like '%" . $search_value . "%'";
    $sql .= " OR amount like '%" . $search_value . "%'";
    $sql .= " OR amount like '%" . $search_value . "%'";
    $sql .= " OR amount like '%" . $search_value . "%'";
    $sql .= " OR amount like '%" . $search_value . "%'";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY id desc";
}

if ($_POST['length'] != -1) {
    $start = $_POST['start'];
    $length = $_POST['length'];
    $sql .= " LIMIT  " . $start . ", " . $length;
}
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

$query = mysqli_query($con, $sql);
$count_rows = mysqli_num_rows($query);
$data = array();
while ($row = mysqli_fetch_assoc($query)) {
    $sub_array = array();
    $sub_array[] = count($dbAccess->select("bodauser", ['bodaUserName'], ['bodaUserPhoneNumber' => formatMobileNumber($row['msisdn'])])) ?
        $dbAccess->select("bodauser", ['bodaUserName'], ['bodaUserPhoneNumber' => formatMobileNumber($row['msisdn'])])[0]['bodaUserName'] : NULL;
    $sub_array[] = $row['status'];
    $sub_array[] = $row['narrative'];
    $sub_array[] = $row['msisdn'];
    $sub_array[] = $row['amount'];
    $sub_array[] = $row['updated_at'];
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo  json_encode($output);
