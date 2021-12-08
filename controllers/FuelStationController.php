<?php


//include_once("./utils/dbaccess.php");


class FuelStation extends DbAccess
{

    public function store($array)
    {
        $name = $array['name'];
        $address = $array['address'];
        $person = $array['person'];
        $email = $array['email'];
        $phone = $array['phoneNumber'];

        //die($address);

        //$activity =  new ActivityLogger();
        //$activity->logActivity()




        return $this->insert("fuelstation", [
            'fuelStationName' => $name,
            'fuelStationAddress' => $address,
            'fuelStationContactPerson' => $person,
            'fuelStationContactPhone' => $email,
            'fuelStationContactEmail' => $phone,
            'fuelStationStatus' => "on"

        ]);
    }

    public function checkErrors($array)
    {
    }
}
