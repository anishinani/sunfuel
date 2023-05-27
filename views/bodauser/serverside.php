<?php
try {
    session_start();
include("../../utils/dbaccess.php");
$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();

$output = array();
$sql = "SELECT bodauser.*, fuelstation.fuelStationName, stage.stageName, stage.stageId  FROM bodauser 
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
    $sql .= " OR bodaUserRole like '%" . $search_value . "%'";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    if($column_name == "ID") $column_name = "bodaUserId"; 
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
function showActions($id)
{
    $output = '';
    // if (in_array("view-bodausers", $_SESSION['permissions'])) {
        $output .= ' <form action="bodauserdetails.php?id="' . $id . '"" method="get">
        <button type="submit"   value="' . $id . '"
        class="btn btn-info btn-sm editbtn" name="bodadetails">show</button>
        </form>';
    // }
    // if (in_array("edit-bodauser", $_SESSION['permissions'])) {
        $output .= ' <form action="edit.php?id="' . $id . '"" method="get">
        <button type="submit" name="update"  value="' . $id . '"
        class="btn btn-info btn-sm editbtn" >Edit</button>
        </form>';

        $output .= '    <form method="POST" action="./delete.php">
        <input type="hidden" name="id" value="' . $id . '"/>
        <button 
      class="btn btn-danger btn-sm deleteBtn" >Delete</button>
      </form>';
    // }
  


    $styledOutPut = '<div style="display:flex;align-items:center;justify-content:space-between;">' . $output . '</div>';

    return $styledOutPut;
}
function getUser($user_id, $dbAccess){
    $names = $dbAccess->select("users", ["name"], ['adminId'=>$user_id])[0]['name'];
    return $names;
}

function showStatus($status)
{
    switch ($status) {
        case 0:
            return "<span style='background-color:#1c478e;border-radius:5px; padding:5px; color:#fff;'>Inactive</span> ";
        case 1:
            return "<span style='background-color:green;border-radius:5px; padding:5px; color:#fff;'>Active</span>";
        case 2:
            return "<span style='background-color:#997400;border-radius:5px; padding:5px; color:#fff;'>P-P</span>";
        case 3:
            return "<span style='background-color:red;border-radius:5px; padding:5px; color:#fff;'>Suspended</span>";
        default:
            return null;
    }
}




while ($row = mysqli_fetch_assoc($query)) {
    $sub_array = array();
    $sub_array[] = $row['bodaUserId'];
    $sub_array[] = $row['bodaUserName'];
    $sub_array[] = $row['bodaUserNIN'];
    $sub_array[] = $row['bodaUserBodaNumber'];
    $sub_array[] = $row['bodaUserRole'];
     $sub_array[] = getUser($row['user_id'], $dbAccess);
    $sub_array[] = showStatus($row['bodaUserStatus']);
    $sub_array[] = $row['fuelStationName'];
    $sub_array[] = $row['stageName'];
    // $sub_array[] = $row['bodaUserStatus'] == 0 ? '
    // <form action="activateBoda.php" method="post">
    // <input type="hidden" name="id" value="' . $row['bodaUserId'] . '"/>
    // <input type="hidden" name="stageId" value="' . $row['stageId'] . '"/>
    // <button type="submit" name="activate"  
    // class="btn btn-info btn-sm editbtn" >Activate</button></form>
    // ' : '    <form action="deactivateBoda.php" method="post">
    // <input type="hidden" name="id" value="' . $row['bodaUserId'] . '"/>
    // <button type="submit" name="deactivate" 
    // class="btn btn-danger btn-sm editbtn" >DeActivate</button></form>
    // ';
    $sub_array[] = showActions($row['bodaUserId']);
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo  json_encode($output);
} catch (\Throwable $th) {
    //throw $th;
    die($th->getMessage());
}


