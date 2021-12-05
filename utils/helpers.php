<?php
class HelperFunctions
{

    public function checkEmptyFields($field)
    {
        if (empty($field)) {
            return true;
        } else return false;
    }

    public function checkDesiredLength($data, $desiredLength, $field)
    {
        if (strlen($data) < $desiredLength) {
            return "The " . $data . " must be greater than " . $desiredLength . "characters";
        } else {
            return false;
        }
    }

    public function checkEmail($email)
    {
        //$emailRegex = /^(('[\w - \s] + ')|([\w-]+(?:\.[\w-]+)*)|('[\w - \s] + ')([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i;

        $newEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            return $newEmail;
        } else {
            return NULL;
        }
    }



    public function confirmPassword($pass1, $pass2)
    {
        if (strcmp($pass1, $pass2) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function checkPassword($password)
    {
        $passwordRegex = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/';
        if (preg_match($password, $passwordRegex)) {
            return true;
        } else {
            return "Passsword must contain both lower case and upper case letters";
        }
    }

    public function checkNumber($number)
    {
        $numberRegex = ' /^[\s()+-]*([0-9][\s()+-]*){9,10}$/';
        if (preg_match($number, $numberRegex)) {
            return $number;
        } else {
            return "The phone number must be 10 characters long";
        }
        # code...
    }
}
