<?php

function getTotalLoans(PDO $conn, $upto)
{

    $loans_taken = "select sum(amount) as total_consumed , fuelStationId from loan where created_at <= " . $upto . "  group by fuelStationId ";

    $stmt = $conn->query($loans_taken);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getTotalDeposit(PDO $conn, $upto)
{

    $todays_deposit = "select sum(amount) as total_float , fuelStationId from deposits where created_at <= " . $upto . "  group by fuelStationId ";

    $stmt = $conn->query($todays_deposit);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function connection()
{
    $dsn = 'mysql:host=localhost;port=3306;dbname=bodacredit;';

    $user = 'root';

    $password = $_SERVER['REMOTE_ADDR'] == "::1" ? "" : "!Log10tan10";

    $conn =  new PDO($dsn, $user, $password);

    return $conn;
}
