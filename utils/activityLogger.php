<?php


class ActivityLogger extends DbAcess
{
    public  function logActivity($name, $activity, $description, $email, $gender)
    {
        // echo $db;
        $ip_address = $_SERVER['REMOTE_ADDR'];

        return $this->insert('logs', [
            'name' => $name,
            "email" => $email,
            "ip_address" => $ip_address,
            'activity' => $activity,
            'gender' => $gender,
            'description' => $description,
            'active_flag' => "1",
            'del_flag' => "0",

        ]);
    }
}
