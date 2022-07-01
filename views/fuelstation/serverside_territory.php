<?php
include("../../utils/dbaccess.php");
include("../../controllers/TerritoryController.php");


$tc = new TerritoryController();

$territory = $tc->getTerritory($_GET['territory']);

$codes = [];

foreach($territory['districts'] as $district) $codes[] = $district['districtCode'];


$con = $tc->getConnection();



$output = array();
$sql = "SELECT fuelstation.*, stage.stageName FROM stage  
INNER JOIN fuelstation ON stage.fuelStationId = fuelstation.fuelStationId";



$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

if(count($codes) == 1) {
    $sql .= " WHERE stage.districtCode  = " . $codes[0] ." ";
}else{
    $sql .= " WHERE stage.districtCode  IN (" . implode(',',$codes) ." )";
}


if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " AND ( stageName like '%" . $search_value . "%'";
    $sql .= " OR stageStatus like '%" . $search_value . "%'";
    $sql .= " OR fuelStationName like '%" . $search_value . "%' ) ";
}

if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column_name . " " . $order . "";
} else {
    $sql .= " ORDER BY stageId desc";
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
    $sub_array[] = $row['stageName'];
    $sub_array[] = $row['fuelStationName'];
    $sub_array[] = $row['fuelStationStatus'] == 0 ? "Not Active" : "Active";
   
    $sub_array[] = $row['fuelStationStatus'] == 0 ? '
    <form action="activateStage.php" method="post">
    <input type="hidden" name="id" value="' . $row['fuelStationId'] . '"/>
    <button type="submit" name="activate" 
    class="btn btn-info btn-sm editbtn" >Activate</button>
    ' : '    <form action="deactivateStage.php" method="post">
    <input type="hidden" name="id" value="' . $row['fuelStationId'] . '"/>
    <button type="submit" name="deactivate"  
    class="btn btn-danger btn-sm editbtn" >DeActivate</button>
    ';


    $sub_array[] = '
    
    <div style="display:flex;align-items:center;justify-content:space-between;">
   

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
