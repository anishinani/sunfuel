<?php

function unpaidStageBodaLoans(PDO $conn){


    $sql = "select stageId from loan where status = 0  and created_at < curdate() ";

    $stmt = $conn->query($sql);

    if(false == $stmt){ $conn->rollBack(); return false;}

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stages = array();

    foreach($results as $r)  $stages[] = $r['stageId'];

    return $stages;
}


function MoveBodaStagesState( PDO $conn , array $stages , $status){

    $sql = "update stage set stageStatus = :state where stageId ";

    if(count($stages) == 1 ){

        $sql .= " = ".$stages[0];

    }else if(count($stages) > 1){

        $sql .= " is in (". implode(',',$stages) .")";
    }
    else{
        return true;
    }

    $stmt = $conn->prepare($sql);

    if(false == $stmt){ $conn->rollBack(); return false;}

    if($stmt->execute(["state" => $status])){
      
        return ($stmt->rowCount() > 0 );

    }else{

        return false;
    }
}

function connection()
{
    $dsn = 'mysql:host=localhost;dbname=bodacredit;';

    $user = 'root';

    $password = $_SERVER['REMOTE_ADDR'] == "::1" ? "" : "!Log10tan10";

    $conn =  new PDO($dsn, $user, $password);

    return $conn;
}