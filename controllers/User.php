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

    //store method
    public function store($array, $hashedPass)
    {


        $name = $array['name'];
        $phone = $array['phone'];
        $email = $array['email'];
        $gender  = $array['gender'];
        $roles = $array['roles'];
        return $this->insert(
            "administrators",
            [
                'name' => $name,
                'email' => $email,
                'phoneNumber' => $phone,
                'gender' => $gender,
                'roleId' => $roles,
                "setPassword" => $hashedPass,


            ]
        );
    }
    //store method

    //hash
    //hash

    public function setPassword($email, $password, $id)
    {

        $user =  $this->select("administrators", ["name", "email", "roleId", "adminId", "gender", "roleId"], ["adminId" => $id])[0];
        //var_dump($user);
        //die("here");
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $result = $this->update("administrators", ['password' => $hashedPassword], ["adminId" => $id]);
        if ($result) {
            return $user;
        } else {
            return false;
        }
    }
}
