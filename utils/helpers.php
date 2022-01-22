<?php
class HelperFunctions
{

    public function checkEmptyFields($field)
    {
        //die("The field is " . $field);
        if (empty($field)) {

            return $field . "field is required";
        } else return NULL;
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
            return "Password must contain both lower case and upper case letters";
        }
    }

    public function checkNumber($number)
    {
        //die("am dying" . $number);
        //$numberRegex = "/^[0-9][0-9]$/";
        if (preg_match("/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $number) && strlen($number) == 10) {
            return  "phone number is valid";
        } else {
            return NULL;
        }
        # code...
    }
}
