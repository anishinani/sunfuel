<?php

class DbAccess
{
    public $conn;
    private $mysqlKeyWords;
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';

    //private $password = '!Log10tan10';
    private $database = 'bodacredit';




    public function __construct()
    {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        
        if ($ip_address == '::1') {

            $this->password = "!Log19tan88";
        } else {

            $this->password = '!Log19tan88';
        }

        // Create connection
        //$this->conn = new mysqli($servername, $username, $password);
        //here
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        // if (!$this->conn) {
        //     return die("Connection failed: " . mysqli_connect_error());
        // } else {
        //     return $this->connection;
        // }
        //here

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        //mysqli_select_db($this->conn, $this->db_name);

        $this->mysqlKeyWords = ['CURRENT_TIMESTAMP'];
    }


    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * @param string $table name of the table
     *@param Array $set array(key=value,key=>vale)
     */
    public function update($table, $set = array(), $where = array())
    {
        //UPDATE `booda`.`users` SET `lname` = 'jumix' WHERE `users`.`id` = 4;
        $q = "UPDATE $table SET ";
        if ($set) {
            foreach ($set as $key => $value) {
                if (in_array($value, $this->mysqlKeyWords)) {
                    $q .= $key . " =$value,";
                } else {
                    $q .= $key . " ='" . mysqli_real_escape_string($this->conn, $value) . "',";
                }
            }

            $q = substr($q, 0, -1) . " where ";
        }
        foreach ($where as $key1 => $value1) {
            $q .= "$key1 = '$value1' and ";
        }
        $q = substr($q, 0, -4);
        // echo '///'.$q."/////";
        //$sql = $this->con->prepare("SELECT {$_cols} FROM `{$table}`{$_cls} {$order} {$limit}");
        $query = mysqli_query($this->conn, $q);
        if ($query) {
            return mysqli_affected_rows($this->conn);
            //return $query;
            //return $rh;
        } else {
            echo 'Erorr ' . mysqli_error($this->conn);
            return mysqli_error($this->conn);
        }

        return $query;
    }

    public function insert($table, $data = array())
    {
        $q = "insert into $table ";
        $values = "(";
        $cols = "(";
        foreach ($data as $key => $value) {
            $cols .= "$key,";
            // $values.="'" . mysqli_real_escape_string($this->conn, $value) . "',";
            if (in_array($value, $this->mysqlKeyWords)) {
                $values .= $value;
            } else {
                $values .= "'" . mysqli_real_escape_string($this->conn, $value) . "',";
            }
            // $values.="'" . $value . "',";
        }

        $cols = substr($cols, 0, -1) . ")";
        $values = substr($values, 0, -1) . ")";
        $q .= $cols . " values $values";
        // echo $q."////</br>";
        $query = mysqli_query($this->conn, $q);
        if ($query) {
            return mysqli_insert_id($this->conn);
        }
        //return $q;
        return mysqli_error($this->conn);
    }

    public function sql($q)
    {
        $query = mysqli_query($this->conn, $q);
        if ($query) {
            return mysqli_insert_id($this->conn);
        }
        //return $q;
        return mysqli_error($this->conn);
    }
    public function select($table, $cols = array(), $where = [])
    {
        $q = "select ";
        if (empty($cols)) {
            $q = "select *";
        } else {
            $strCols = "";
            foreach ($cols as $col) {
                $strCols .= $col . ",";
            }

            $strColsfinal = substr($strCols, 0, -1);
            $q = "select " . $strColsfinal;
        }
        $q .= " from $table";

        if ($where) {
            // $keys=  array_keys($where);
            $wherestr = "";
            $orderbyValue = "";
            $orderby = FALSE;
            foreach ($where as $key => $value) {
                if ($key == "order by") {
                    //echo "True key=$key and ".($key=="order by");
                    $orderbyValue = $value;
                    $orderby = TRUE;
                } elseif ($key == "between") {
                    $wherestr .= " $value and";
                } else {
                    $checkEmail = $this->isValidEmail($value);
                    if ($checkEmail) {
                        $wherestr .= " $key ='$value' and";
                    } else {
                        $explodable = explode('.', $value);

                        //print_r($explodable);
                        if (count($explodable) > 1) { //if column in for l.id=m.id
                            $wherestr .= " $key =$value and";
                        } else {
                            if ($value[0] == "!") { //not equal to expression
                                $wherestr .= " $key !='$value' and";
                            } else {
                                $wherestr .= " $key ='" . mysqli_real_escape_string($this->conn, $value) . "' and";
                            }
                        }
                    }
                }
            }

            $wherestr = substr($wherestr, 0, -3);
            if ($wherestr && strlen($wherestr) > 3) {
                $q .= " where " . $wherestr;
            }

            if ($orderby) {

                $q .= " order by $orderbyValue";
            }
        }


        $query = mysqli_query($this->conn, $q);
        // echo $q.'</br>';
        $result = array();
        if ($query) {
            while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                $result[] = $row;
            }
            /* if (count($result) == 1) {
              return $result[0];
              } else {
              return $result;
              } */
            return $result;
        } else {
            echo 'Erorr ' . mysqli_error($this->conn);
        }

        return FALSE;
    }

    public function selectQuery($sql)
    {
        $query = mysqli_query($this->conn, $sql);
        // echo $q;
        $result = array();
        if ($query) {
            while ($row = mysqli_fetch_array($query)) {
                $result[] = $row;
            }
            /* if (count($result) == 1) {
              return $result[0];
              } else { */
            return $result;
            //}
        } else {
            echo 'Erorr ' . mysqli_error($this->conn);
        }

        return FALSE;
    }

    private function isValidEmail($string)
    {
        if (filter_var($string, FILTER_VALIDATE_EMAIL)) {
            //echo "This ($email_a) email address is considered valid.";
            return TRUE;
        }
        return FALSE;
    }

    public function delete($sql)
    {
        $query = mysqli_query($this->conn, $sql);
        
    }

    public function clean($input)
    {
        return mysqli_real_escape_string($this->conn, trim($input));
    }

    //activitylogger
    //acctivitylogger

    //count
    public function countRows($table, $row = "*", $where = [])
    {

        $table =  $this->clean($table);
        $row =  $this->clean($row);
        if (empty($table)) {
            return 0;
        }
        if (empty($where)) {
            $sql = "SELECT COUNT($row) AS total FROM $table";
            $results = mysqli_query($this->conn, $sql);
            $total = $results->fetch_assoc();
            return $total['total'];
        } else {
            $sql = "SELECT COUNT($row) AS total FROM $table WHERE  $where[0] = $where[1]";
            //SELECT COUNT(bodaUserStatus) FROM `bodauser` WHERE bodaUserStatus =1;
            $results = mysqli_query($this->conn, $sql);
            $total = $results->fetch_assoc();
            return $total['total'];
        }
    }
    //count

    //delete
    public function deleteRow($table, $field, $row)
    {
        $sql = "DELETE FROM $table WHERE $field='$row'";
        $results = mysqli_query($this->conn, $sql);
        return $results;
    }
    //delete

    /**
     * @method selectWithPagination
     * select data specifically for  datatable use
     * **/ 
    public function selectWithPagination($base_query , array $columns , array $limit ,array $extras = null , array $orderby = null ,  $searchParam = null ){

        $output = array();
        
        $total = mysqli_num_rows(mysqli_query($this->conn , $base_query));


        if(!is_null($searchParam)){
            
            $base_query .= ' WHERE ';

            foreach ($columns as $column){
                if(!isset($extras[$column])) {
                    $base_query .= $column . ' LIKE %'.$searchParam.'%  OR';
                }
            }

            $base_query  = rtrim($base_query , 'OR');
        } 

        if(!is_null($orderby)){

            $base_query .= ' ORDER BY '.$orderby['column'] . ' '.$orderby['order'];
        }

        if($limit['length'] != -1) {

            $base_query .=  ' LIMIT '.$limit['start'] . ','.$limit['length'];
        }


        $query = mysqli_query($this->conn , $base_query);

        $output['recordsTotal'] = $total;

        $output['recordsFiltered'] = !is_null($searchParam)? mysqli_num_rows($query): 0;

        $output['draw'] = $extras['draw'];     
        
        $output['data'] = array();

        
        while($row = mysqli_fetch_assoc($query)){

            // $output['data'][] = $row;

            $dataSet = array();

            foreach ($columns as $col){ 

                if(isset($row[$col])){ 
                    if(isset($extras[$col])){
                        $dataSet[] = call_user_func($extras[$col],$row);
                    }else{
                        $dataSet[] = $row[$col];
                    }
                }
                if(isset($extras[$col])){
                    $dataSet[] = call_user_func($extras[$col],$row);
                }
            }

            // if(isset($extras['action']) && is_callable($extras['action'])){
            //     $dataSet[] = call_user_func($extras['action'],$row);
            // }

            $output['data'][] = $dataSet;
        }
    
      


        return $output;





    }
}
