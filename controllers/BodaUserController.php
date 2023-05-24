<?php


//include_once("./utils/dbaccess.php");


class BodaUser extends DbAccess
{

    public function store($array, $front, $back)
    {
        $name = $array['name'];
        $nin = $array['nin'];
        $bodaNumber = $array['bodaNumber'];
        //$number = $array['bodaNumber'];
        $phone = $array['phoneNumber'];

        if (strpos($phone, "+256") !== false) {
            $phone =   str_replace("+256", "0", $phone);
        }
        if (strpos($phone, "256") !== false) {
            $phone =  str_replace("256", "0", $phone);
        }



        $fuel = $array['fuelStationId'];

        $stage = $array["stageId"];



        if (strtoupper($array["role"]) == strtoupper("Chairman")) {
            //get the last id
            $selectQuery = "SELECT bodaUserId FROM bodauser ORDER BY bodaUserId desc LIMIT 1";
            //get the last id
            $results = $this->selectQuery($selectQuery)[0]['bodaUserId'];

            if ($results == NULL) {
                $results = 1;
            } else {
                $results += 1;
            }
            if ($this->update("stage", ['chairmanId' => $results], ['stageId' => $stage])) {
                return $this->insert(
                    "bodauser",
                    [
                        'bodaUserName' => strtoupper($name),
                        'bodaUserNIN' =>  strtoupper($nin),
                        'bodaUserBodaNumber' => strtoupper($bodaNumber),
                        'bodaUserPhoneNumber' => $phone,
                        "bodaUserBackPhoto" => $back,
                        "bodaUserFrontPhoto" => $front,
                        "bodaUserRole" => strtoupper($array['role']),
                        "alternativePhotoNumber" => $array['anotherNumber'],
                        'fuelStationId' => $fuel,
                        'stageId' => $stage,
                        "bodaUserStatus" => "0"


                    ]
                );
            }
        }



        //die("done");

    }



    public function updateInfo($array)
    {
         try {
         $name = $array['name'];
         $nin = $array['nin'];
         $bodaNumber = $array['bodaNumber'];
         $fuel = $array['fuelStationId'];
         $phone = $array['phoneNumber'];
         $stage = $array["stageId"];
         $id = $array['id'];

         
        $res = $this->update(
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

        return $res;
         } catch (\Throwable $th) {
            die("not updated");
             var_dump($th->getMessage());
         }

        
    }
}
