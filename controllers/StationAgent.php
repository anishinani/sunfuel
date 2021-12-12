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

        return $this->insert(
            "fuelagent",
            [
                'fuelAgentName' => $name,
                'fuelAgentPhoneNumber' => $phone,
                'fuelAgentNIN' => $nin,
                'stationId' => $stationId,
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
        return $this->update(
            "fuelagent",
            [
                'fuelAgentName' => $name,
                'fuelAgentPhoneNumber' => $phone,
                'fuelAgentNIN' => $nin,
                'stationId' => $stationId,
                'status' => 0

            ],
            ["fuelAgentId" => $array['id']]
        );
    }
}
