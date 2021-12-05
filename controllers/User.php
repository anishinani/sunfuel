<?php
//include_once("utils/dbaccess.php");
include("utils/helpers.php");

//$dbaccess = new DbAcess();
// $connection = $dbaccess->closeConnection();
//session_start();


class User extends DbAcess
{



    public function check_login($email, $password)
    {
        $dbAccess = new DbAcess();
        //$helpers =  new HelperFunctions();
        //$helpers->checkEmail()
        $sql = "SELECT * FROM administrators WHERE email = ? ";

        //escape string
        $email =  $this->createConnection()->real_escape_string($email);

        $smt = $this->createConnection()->prepare($sql);
        $smt->bind_param("s", $email);
        $smt->execute();
        $results = $smt->get_result();

        if ($row = $results->fetch_assoc()) {

            //return $row;
            if ($this->checkPassword($password, $row['password'])) {
                return $row;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }
    private function checkPassword($password, $hashedPassword)
    {
        if (password_verify($password, $hashedPassword)) {
            return true;
        } else {
            return false;
        }
    }
}
