<?php


//include_once("./utils/dbaccess.php");


class BodaUser extends DbAccess
{

    public function store($array, $front, $back)
    {
        $name = $array['name'];
        $nin = $array['nin'];
        $bodaNumber = $array['bodaNumber'];
        $fuel = $array['fuelStationId'];
        $phone = $array['phoneNumber'];
        $stage = $array["stageId"];
        $role = $array['role'];
        //var_dump("The role is " . $array['role']);

        //die("done");
        $re = $this->insert(
            "bodauser",
            [
                'bodaUserName' => $name,
                'bodaUserNIN' => $nin,
                'bodaUserBodaNumber' => $bodaNumber,
                'bodaUserPhoneNumber' => $phone,
                "bodaUserBackPhoto" => $back,
                "bodaUserFrontPhoto" => $front,
                "bodaUserRole" => $array['role'],
                "alternativePhotoNumber" => $array['anotherNumber'],
                'fuelStationId' => $fuel,
                'stageId' => $stage,
                "bodaUserStatus" => "0"


            ]
        );

        var_dump($re);
        die("here");
    }



    public function updateInfo($array)
    {
        $name = $array['name'];
        $nin = $array['nin'];
        $bodaNumber = $array['bodaNumber'];
        $fuel = $array['fuelStationId'];
        $phone = $array['phoneNumber'];
        $stage = $array["stageId"];
        $id = $array['id'];
        //var_dump($array['id']);
        //die("here");
        return $this->update(
            "bodauser",
            [
                'bodaUserName' => $name,
                'bodaUserNIN' => $nin,
                'bodaUserBodaNumber' => $bodaNumber,
                'bodaUserPhoneNumber' => $phone,
                'fuelStationId' => $fuel,
                'stageId' => $stage,
                "bodaUserRole" => $array['role'],
                "alternativePhotoNumber" => $array['anotherNumber'],
                'bodaUserStatus' => "0"
            ],
            ["bodaUserId" => $id]
        );
    }
}
