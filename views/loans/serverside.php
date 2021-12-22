<?php
session_start();
include("../../utils/dbaccess.php");
$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();




$output = array();
$sql = "SELECT * FROM loan";

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE loanAmount like '%" . $search_value . "%'";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY loanId asc";
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


    if (in_array("edit-roles", $_SESSION['roles'])) {
        $output = '<form action="./show.php?id="' . $id . '"" method="get">
        <button type="submit" name="show"  value="' . $id . '"
        class="btn btn-info btn-sm editbtn" >Show</button>
    
        </form>';
    }
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
function showStatus($status)
{
    $output = "";
    if (strval($status) == 0) {
        $output .= '  <button  name="show" 
        class="btn btn-success btn-sm editbtn" >Paid</button>';
    } else {
        $output .= '<button  name="show" 
        class="btn btn-danger btn-sm editbtn" >Pending</button>';
    }
    return $output;
}
while ($row = mysqli_fetch_assoc($query)) {
    $sub_array = array();
    $sub_array[] = $row['loanAmount'];
    $sub_array[] = $row['LoanInterest'];
    $sub_array[] = $row['boadUserId'];
    $sub_array[] = count($dbAccess->select("fuelstation", ['fuelStationName'], ['fuelStationId' => $row['fuelSationId']]))
        ? $dbAccess->select("fuelstation", ['fuelStationName'], ['fuelStationId' => $row['fuelSationId']])[0]['fuelStationName'] : NULL;
    $sub_array[] = count($dbAccess->select("fuelagent", ['fuelAgentName'], ['fuelAgentId' => $row['agentId']]))
        ? $dbAccess->select("fuelagent", ['fuelAgentName'], ['fuelAgentId' => $row['agentId']])[0]['fuelAgentName'] : NULL;
    $sub_array[] = count($dbAccess->select("stage", ['stageName'], ['stageId' => $row['stageId']])) ?
        $dbAccess->select("stage", ['stageName'], ['stageId' => $row['stageId']])[0]['stageName'] : NULL;
    $sub_array[] =  showStatus($row['status']);
    $sub_array[] = showActions($row['loanId']);
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo  json_encode($output);

