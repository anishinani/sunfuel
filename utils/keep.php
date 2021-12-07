<?php
class Katende
{

    // private $host = 'localhost';
    // private $username = 'root';
    // private $password = '';
    // private $database = 'creditplus';
    // private $mysqlKeyWords;

    // private $connection;


    // public function createConnection()
    // {
    //     $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

    //     if (!$this->connection) {
    //         return die("Connection failed: " . mysqli_connect_error());
    //     } else {
    //         return $this->connection;
    //     }
    // }

    // public function closeConnection()
    // {
    //     return mysqli_close($this->connection);
    // }

    // public function insert($table, $data = array())
    // {
    //     $this->mysqlKeyWords = ['CURRENT_TIMESTAMP'];
    //     $q = "insert into $table ";
    //     $values = "(";
    //     $cols = "(";
    //     foreach ($data as $key => $value) {
    //         $cols .= "$key,";
    //         // $values.="'" . mysqli_real_escape_string($this->conn, $value) . "',";
    //         if (in_array($value, $this->mysqlKeyWords)) {
    //             $values .= $value;
    //         } else {
    //             $values .= "'" . mysqli_real_escape_string($this->createConnection(), $value) . "',";
    //         }
    //         // $values.="'" . $value . "',";
    //     }

    //     $cols = substr($cols, 0, -1) . ")";
    //     $values = substr($values, 0, -1) . ")";
    //     $q .= $cols . " values $values";
    //     // echo $q."////</br>";
    //     $query = mysqli_query($this->createConnection(), $q);
    //     if ($query) {
    //         return mysqli_insert_id($this->createConnection());
    //     }
    //     //return $q;
    //     return mysqli_error($this->createConnection());
    // }
}
