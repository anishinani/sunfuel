<?php


//include_once("./utils/dbaccess.php");


class BodaUser extends DbAccess
{

    public function store($array)
    {
        $name = $array['name'];
        $nin = $array['nin'];
        $bodaNumber = $array['bodaNumber'];
        $fuel = $array['fuelStationId'];
        $phone = $array['phoneNumber'];
        $stage = $array["stageId"];
        $variable  = $this->insert(
            "bodauser",
            [
                'bodaUserName' => $name,
                'bodaUserNIN' => $nin,
                'bodaUserBodaNumber' => $bodaNumber,
                'bodaUserPhoneNumber' => $phone,
                'fuelStationId' => $fuel,
                'stageId' => $stage,
                'bodaUserStatus' => "active"

            ]
        );
        die($variable);
    }



    public function updateInfo($array)
    {
        $name = $array['name'];
        $nin = $array['nin'];
        $bodaNumber = $array['bodaNumber'];
        $fuel = $array['fuelStationId'];
        $phone = $array['phoneNumber'];
        $stage = $array["stageId"];
        return $this->update(
            "bodauser",
            [
                'bodaUserName' => $name,
                'bodaUserNIN' => $nin,
                'bodaUserBodaNumber' => $bodaNumber,
                'bodaUserPhoneNumber' => $phone,
                'fuelStationId' => $fuel,
                'stageId' => $stage,
                'bodaUserStatus' => "active"

            ] .
                ["fuelStationId" => $array['id']]
        );
    }
}
