<?php
include("../../utils/dbaccess.php");
$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();

$output = array();
$sql = "SELECT * FROM roles";

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE roleName like '%" . $search_value . "%'";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY roleId asc";
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
    $sub_array[] = $row['roleId'];
    $sub_array[] = $row['roleName'];
    $sub_array[] = '<div style="display:flex;align-items:center;justify-content:space-between;">
    <form action="show.php?id="' . $row['roleId'] . '"" method="get">
    <button type="submit" name="show"  value="' . $row['roleId'] . '"
    class="btn btn-info btn-sm editbtn" >Show</button>

    </form>

    <form action="edit.php?id="' . $row['roleId'] . '"" method="get">
    <button type="submit" name="update"  value="' . $row['roleId'] . '"
    class="btn btn-info btn-sm editbtn" >Edit</button>

    </form>
    <form method="POST" action="./delete.php">
      <input type="hidden" name="id" value="' . $row['roleId'] . '"/>
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
