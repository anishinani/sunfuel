<?php

class Deposit extends DbAccess
{
    public function store($array, $receipt)
    {


        //update fuelstation
        $amountCredentials = $this->select("fuelstation", ['currentAmount', 'totalAmount'], ['fuelStationId' => $array['fuelStationId']]);
        //update fuelstation 

        $totalAmount =  $amountCredentials[0]['totalAmount'] + $array['amount'];
        $currentAmount =  $amountCredentials[0]['currentAmount'] + $array['amount'];
        $this->update("fuelstation", ['totalAmount' => $totalAmount, 'currentAmount' => $currentAmount], ['fuelstationId' => $array['fuelStationId']]);

        return $this->insert(
            "deposits",
            [
                'fuelStationId' => $array['fuelStationId'],
                'depositedBy' => $array['name'],
                'amount' => $array['amount'],
                'receipt' => $receipt,

            ]
        );
    }
}
