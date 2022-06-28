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
               $this->deleteRow("territory_districts","territoryId", $id);
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


    public function getTerritory($territoryId){
   
        $data = array();
        

        $territory = $this->select('territories',['*'] , ["territoryId" => $territoryId]);

        if(count($territory) == 0) return null;

        $data['territory'] = $territory[0];

        $districts = $this->select('territory_districts',['*'] , ['territoryId' => $territoryId]);

        $data['districts'] = $districts;

        return $data;
        
    }

    // updates the territory
    function  updateTerritory($array){

        if(!isset($array['territoryDistricts']) && count($array['territoryDistricts']) < 1) return false;

        $update = $this->update("territories",['territoryName' => $array['territoryName'],"territoryManager" => $array['territoryManager']] , ["territoryId" => $array['territoryId'] ]);

        $this->delete("delete from territory_districts where territoryId = ".$array['territoryId']);

        $inserts = [];

        foreach($array['territoryDistricts'] as $district){
 
         $inserts[] = $this->insert('territory_districts',[
             'districtCode' => $district,
             'territoryId' => $array['territoryId'],
             'status' => 0
         ]);
        }

        return true;
    }



    function deleteTerritory($id){

        $stmt = $this->conn->query("delete from territories where".$id);   

        return true;

       $stmt = $this->conn->query("delete from territory_districts where territoryId =".$id);

       if(!is_bool($stmt) && $stmt->num_rows){

         $stmt = $this->conn->query("delete from territories where".$id);   

         return true;
       }
      return false;
    }

}
