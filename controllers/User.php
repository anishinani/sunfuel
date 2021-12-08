<?php



class User extends DbAccess
{



    public function check_login($email, $password)
    {
        //$dbAccess = new DbAcess();
        //$helpers =  new HelperFunctions();
        //$helpers->checkEmail()
        $sql = "SELECT * FROM administrators WHERE email = ? ";

        $email = $this->clean($email);

        $smt = $this->getConnection()->prepare($sql);
        //$email = $this->cl
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
