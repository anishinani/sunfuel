<?php

class Stage extends DbAccess
{

    public function store($array)
    {
        $name = $array['name'];
        $id = $array["fuelStationId"];

        return $this->insert(
            "stage",
            [
                'stageName' => $name,
                'fuelStationId' => $id,
                'stageStatus' => '0'

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
                'stageName' => $name,
                'fuelStationId' => $id,
                "chairmanId" => $chairman
            ],
            ["stageId" => $array['id']]
        );
    }
}
