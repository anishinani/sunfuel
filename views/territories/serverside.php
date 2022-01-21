<?php

session_start();

include("../../utils/dbaccess.php");
include("../../utils/pageFunctions.php");


$dbAccess =  new DbAccess();

$sql = "SELECT `territories`.*, `administrators`.`name` AS territoryManager     FROM `territories` INNER JOIN `administrators` ON `territories`.`territoryManager` = `administrators`.`adminId`";

$searchParam = (!empty($_POST['search']['value'])) ? $_POST['search']['value'] : null;


$output = $dbAccess->selectWithPagination(
   $sql,
   ['territoryId' , 'territoryName' , 'territoryManager','stages','fuelstations','action'],
   array(
       'length' => isset($_POST['length']) ? $_POST['length'] : -1,
       'start' => isset($_POST['start'])?? $_POST['start']
   ),
    array(
        'draw' => intval($_POST['draw']),
        'stages' => function($row){
          $html = "<a href='../stage/territoryStages.php?territory=".$row['territoryId']."' class='btn btn-primary btn-sm '><i class='fas fa-eye'></i> view</a>";
          return $html;    
        },
        'fuelstations' => function($row){
            $html = "<a href='../fuelstation/territoryFuelstations.php?territory=".$row['territoryId']."' class='btn btn-primary btn-sm '><i class='fas fa-eye'></i> view</a>";
            return $html;    
        },
        "action" => function($row){
            return  showActions2($row ,[
             array('permission' => 'edit-territories' , 'type' => 'edit'),
             array('permission' => 'delete-territories' , 'type' => 'delete')
            ],'territoryId');
        }
    )
   ,
   array(
       'column' => !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 'territoryId',
       'order' => !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'desc'
   ),
   $searchParam
);


$output = json_encode($output);

echo $output;