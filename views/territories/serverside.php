<?php

use function PHPSTORM_META\type;

include("../../utils/dbaccess.php");
include("../../utils/pageFunctions.php");


$dbAccess =  new DbAccess();

$sql = "SELECT `territories`. `territoryName`, `administrators`.`name` AS territoryManager  , `territoryId` , `status`  FROM `territories` INNER JOIN `administrators` ON `territories`.`territoryManager` = `administrators`.`adminId`";

$searchParam = (isset($_POST['search']['value']) && !empty($_POST['search']['value'])) ? $_POST['search']['value'] : null;


$output = $dbAccess->selectWithPagination(
   $sql,
   ['territoryId' , 'territoryName' , 'territoryManager','status'],
   array(
       'length' => isset($_POST['length']) ? $_POST['length'] : -1,
       'start' => isset($_POST['start'])?? $_POST['start']
   ),
    array(
        'draw' => intval($_POST['draw']),
        "showAction" => function($row){

            return  showActions($row ,[
             array('permission' => 'edit-territory' , 'type' => 'edit'),
             array('permission' => 'delete-territory' , 'type' => 'delete')
            ],'territoryId');
        }
    )
   ,
   array(
       'column' => isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 'territories.created_at',
       'order' => isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc'
   ),
   $searchParam
);


$output = json_encode($output);

echo $output;