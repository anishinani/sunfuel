<?php

class TerritoryController extends DbAccess {


    public function store($data){
      // ensure each territory is saved with atleast a district
      if(!isset($data['territoryDistricts']) && count($data['territoryDistricts']) < 1) return false;

       $id = $this->insert("territories",[

            'territoryName' => $data['territoryName'],
            'territoryManager' => $data['territoryManager'],
            "status" => 0
        ]);

       if(!$id) return false;

       $inserts = [];

       foreach($data['territoryDistricts'] as $district){

        $inserts[] = $this->insert('territory_districts',[
            'districtCode' => $district,
            'territoryId' => $id,
            'status' => 0
        ]);
       }

       if(count($inserts) != count($data['territoryDistricts'])){
            // clearing the junk
            if(!empty($inserts)){
               $this->deleteRow("territoryDistricts","territoryId", $id);
            }

            $this->deleteRow("territories","territoryId",$id);

            return false;
       }

       return true;

    }
    /***
     * @method exists 
     * checks if the territory name exists
     * */ 
    public function exists($territoryName){

        $count = $this->selectQuery("select count(territoryId) as total from territories where territoryName = '".$territoryName . "'");

        if(count($count) == 0) return false; 

        return $count[0]['total'] > 0;
    }





}
