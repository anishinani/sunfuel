<?php


//include_once("./utils/dbaccess.php");


class Package extends DbAccess
{

    public function store($array)
    {
        $name = $array['name'];
        $amount = $array['amount'];

        return $this->insert(
            "package",
            [
                'packageName' => strtoupper($name),
                'packageAmount' => $amount,
                'packageStatus' => 1

            ]
        );
    }



    public function updateInfo($array)
    {
        $name = $array['name'];
        $amount = $array['amount'];
        return $this->update(
            "package",
            [
                'packageName' => strtoupper($name),
                'packageAmount' => $amount,
                'packageStatus' => '1'

            ],
            ["packageId" => $array['id']]
        );
    }
}
