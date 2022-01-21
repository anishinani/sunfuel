<?php
session_start();
include("../../utils/dbaccess.php");
$dbAccess =  new DbAccess();
$con = $dbAccess->getConnection();

$output = array();
$sql = "SELECT * FROM roles";

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if (isset($_POST['search']['value']) && !empty($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE name like '%" . $search_value . "%'";
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

$query = mysqli_query($con, $sql);
$count_rows = mysqli_num_rows($query);
$data = array();

function showActions($id)
{
    $output = '<div class="d-flex justify-content-between ">';


    if (in_array("edit-roles", $_SESSION['permissions'])) {
        $output .= '<form action="./show.php?id="' . $id . '"" method="get">
        <button type="submit" name="show"  value="' . $id . '"
        class="btn btn-info btn-sm  " ><i class="fas fa-eye"></i></button>
    
        </form>';
    }
    if (in_array("edit-roles", $_SESSION['permissions'])) {
        $output .= '    <form action="./edit.php?id="' . $id . '"" method="get">
        <button type="submit" name="update"  value="' . $id . '"
        class="btn btn-primary btn-sm " title="edit" ><i class="fas fa-edit"></i></button>
    
        </form>';
    }
    if (in_array("delete-roles", $_SESSION['permissions'])) {
        $output .= '    <form method="POST" action="./delete.php">
        <input type="hidden" name="id" value="' . $id . '"/>
        <button 
      class="btn btn-danger btn-sm " title="delete" > <i class="fas fa-trash"></i></button>
  
      </form>';
    }



    $styledOutPut =   $output . '</div>';

    return $styledOutPut;
}
while ($row = mysqli_fetch_assoc($query)) {
    $sub_array = array();
    $sub_array[] = $row['id'];
    $sub_array[] = $row['name'];
    $sub_array[] = showActions($row['id']);
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $count_rows,
    'recordsFiltered' =>   $total_all_rows,
    'data' => $data,
);
echo  json_encode($output);

