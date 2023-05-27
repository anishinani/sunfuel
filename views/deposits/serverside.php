<?php
session_start();
include("../../utils/dbaccess.php");
$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();




$output = array();
$sql = "SELECT * FROM deposits";

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE depositedBy like '%" . $search_value . "%'";
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

$query = mysqli_query($con, $sql);
$count_rows = mysqli_num_rows($query);
$data = array();

function showActions($id)
{
    $output = '';


    // if (in_array("view-receipts", $_SESSION['permissions'])) {
        $output = '<form action="./showReceipt.php?id="' . $id . '"" method="get">
        <button type="submit" name="showReceipt"  value="' . $id . '"
        class="btn btn-info btn-sm editbtn" >Show Receipt</button>
    
        </form>';
    //}
    // if (in_array("edit-roles", $_SESSION['roles'])) {
    //     $output .= '    <form action="./edit.php?id="' . $id . '"" method="get">
    //     <button type="submit" name="update"  value="' . $id . '"
    //     class="btn btn-info btn-sm editbtn" >Edit</button>

    //     </form>';
    // }
    // if (in_array("delete-users", $_SESSION['roles'])) {
    //     $output .= '    <form method="POST" action="./delete.php">
    //     <input type="hidden" name="id" value="' . $id . '"/>
    //     <button 
    //   class="btn btn-danger btn-sm deleteBtn" >Delete</button>

    //   </form>';
    // }


    $styledOutPut = '<div style="display:flex;align-items:center;justify-content:space-between;">' . $output . '</div>';

    return $styledOutPut;
}
//show stage
//$dbAccess->
//fins
//$row['fuelSationId'];

// function formatAmount($amount){

// }

while ($row = mysqli_fetch_assoc($query)) {
    $sub_array = array();
    $sub_array[] = $row['id'];
    $sub_array[] = count($dbAccess->select("fuelstation", ['fuelStationName'], ['fuelStationId' => $row['fuelStationId']]))
        ? $dbAccess->select("fuelstation", ['fuelStationName'], ['fuelStationId' => $row['fuelStationId']])[0]['fuelStationName'] : NULL;;
    $sub_array[] =  "shs " . number_format($row['amount'], 0);
    $sub_array[] = $row['depositedBy'];
    $sub_array[] = $row['created_at'];
    $sub_array[] =  showActions($row['fuelStationId']);
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo  json_encode($output);

// count($dbAccess->select("fuelstation", ['fuelStationName'], ['fuelStationId' => $row['fuelStationId']]))
//         ? $dbAccess->select("fuelstation", ['fuelStationName'], ['fuelStationId' => $row['fuelStation']])[0]['fuelStationName'] : NULL;
