<?php


class ActivityLogger extends DbAccess
{
    public  function logActivity($name, $activity, $description, $email, $gender)
    {
        // echo $db;
        $ip_address = $_SERVER['REMOTE_ADDR'];

        return $this->insert('logs', [
            'name' => $name,
            "email" => $email,
            "ipAddress" => $ip_address,
            'activity' => $activity,
            'gender' => $gender,
            'description' => $description

        ]);
    }
}
