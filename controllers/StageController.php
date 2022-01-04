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
                'stageName' => strtoupper($name),
                'fuelStationId' => $id,
                'stageStatus' => '0',
                'districtCode' => $_POST['district'],
                'countyCode' => $_POST['county'],
                'subCountyCode' => $_POST['subcounty'],
                'parishCode' => $_POST['parish'],
                'villageCode' => $_POST['village']

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
