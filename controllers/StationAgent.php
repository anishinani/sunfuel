<?php


//include_once("./utils/dbaccess.php");


class StationAgent extends DbAccess
{

    public function store($array)
    {
        $name = $array['name'];
        $phone = $array['phoneNumber'];
        $nin = $array['nin'];
        $stationId = $array['station'];
        $anotherPhone =   $array['anotherPhone'];

        return $this->insert(
            "fuelagent",
            [
                'fuelAgentName' => $name,
                'fuelAgentPhoneNumber' => $phone,
                'fuelAgentNIN' => $nin,
                'stationId' => $stationId,
                'anotherPhoneNumber' => $anotherPhone,
                'status' => '0'

            ]
        );
    }



    public function updateInfo($array)
    {
        $name = $array['name'];
        $phone = $array['phoneNumber'];
        $nin = $array['nin'];
        $stationId = $array['station'];
        $anotherPhone =   $array['anotherPhone'];
        return $this->update(
            "fuelagent",
            [
                'fuelAgentName' => $name,
                'fuelAgentPhoneNumber' => $phone,
                'fuelAgentNIN' => $nin,
                'anotherPhoneNumber' => $anotherPhone,
                'stationId' => $stationId,


            ],
            ["fuelAgentId" => $array['id']]
        );
    }
}
