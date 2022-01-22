<?php


class ActivityLogger extends DbAccess
{
    public  function logActivity($name, $activity, $description, $email, $account_id)
    {
        // echo $db;
        $ip_address = $_SERVER['REMOTE_ADDR'];

        return $this->insert('logs', [
            'name' => $name,
            "email" => $email,
            "ipAddress" => $ip_address,
            'activity' => $activity,
            'account_id' => $account_id,
            'description' => $description

        ]);
    }
}
