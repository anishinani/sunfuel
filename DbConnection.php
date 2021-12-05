<?php
class DbConnection
{

    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'creditplus';

    protected $connection;

    public function __construct()
    {

        if (!isset($this->connection)) {

            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

            if (!$this->connection) {
                die("Connection failed: " . mysqli_connect_error());
            } else {
                return $this->connection;
            }
        }

        return $this->connection;
    }

    public function closeConnection()
    {
        return mysqli_close($this->connection);
    }
}
