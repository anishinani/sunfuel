<?php


class User extends DbAccess
{



    public function check_login($email, $password)
    {
        //$dbAccess = new DbAcess();
        //$helpers =  new HelperFunctions();
        //$helpers->checkEmail()
        $sql = "SELECT * FROM users WHERE email = ? ";

        $email = $this->clean($email);

        $smt = $this->getConnection()->prepare($sql);
        //$email = $this->cl
        $smt->bind_param("s", $email);
        $smt->execute();
        $results = $smt->get_result();

        if ($row = $results->fetch_assoc()) {

            //return $row;
            if ($this->checkPassword($password, $row['password'])) {
                //die("results");
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

    public function formatMobileInternational($mobile)
    {
        $length = strlen($mobile);
        $m = '+256';
        //format 1: +256752665888
        if ($length == 13)
            return $mobile;
        elseif ($length == 12) //format 2: 256752665888
            return "+" . $mobile;
        elseif ($length == 10) //format 3: 0752665888
            return $m .= substr($mobile, 1);
        elseif ($length == 9) //format 4: 752665888
            return $m .= $mobile;

        return $mobile;
    }


    //store method
    public function store($array, $hashedPass)
    {


        $name = $array['name'];
        $phone = $array['phone'];
        $email = $array['email'];
        $gender  = $array['gender'];
        $roles = $array['roles'];
        //sdie("login");
        return $this->insert(
            "users",
            [
                'name' => strtoupper($name),
                'email' => $email,
                'phoneNumber' =>formatPhoneNumber($phone),
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

        $user =  $this->select("users", ["name", "email", "roleId", "adminId", "gender", "roleId"], ["adminId" => $id])[0];
        //var_dump($user);
        //die("here");
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $result = $this->update("users", ['password' => $hashedPassword], ["adminId" => $id]);
        if ($result) {
            return $user;
        } else {
            return false;
        }
    }
}
