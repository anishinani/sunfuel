<?php

class Stage extends DbAccess
{

    public function store($array)
    {
        $name = $array['name'];
        $fuelStationId = $array["fuelStationId"];
        $territoryId = $array["territoryId"];
        $location = $array["location"] ?? '';

        return $this->insert(
            "stage",
            [
                'stageName' => strtoupper($name),
                'stageLocation' => $location,
                'fuelStationId' => $fuelStationId,
                'territoryId' => $territoryId,
                'stageStatus' => '1' // Active by default
            ]
        );
    }
    public function updateInfo($array)
    {
        $name = $array['name'];
        $id = $array["fuelStationId"];
        $chairman = $array['chairman'];
        //die($array['id']);
        return $this->update(
            "stage",
            [
                'stageName' => strtoupper($name),
                'fuelStationId' => $id,
                "chairmanId" => $chairman
            ],
            ["stageId" => $array['id']]
        );
    }
}
