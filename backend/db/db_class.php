<?php
include('db.php');
date_default_timezone_set('Asia/Manila');

class global_class extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }

    public function login($id)
    {
        $query = $this->conn->prepare("SELECT * FROM `tblusers` WHERE `ID` = '$id'");
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }

    public function getUsers()
    {
        $query = $this->conn->prepare("SELECT * FROM `tblusers` WHERE `USER_TYPE` <> 'admin'");
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }

    public function addNewUser($name, $team)
    {
        $userId = rand(10000000, 99999999);
        $checkUserId = $this->login($userId);
        while ($checkUserId->num_rows > 0) {
            $userId = rand(10000000, 99999999);
            $checkUserId = $this->login($userId);
        }

        $query = $this->conn->prepare("INSERT INTO `tblusers`(`ID`, `TEAM_ID`, `FULL_NAME`, `USER_TYPE`, `STATUS`) VALUES ('$userId','$team','$name','user','1')");
        if ($query->execute()) {
            return 200;
        } else {
            return 400;
        }
    }

    public function editUser($id, $name, $team)
    {
        $query = $this->conn->prepare("UPDATE `tblusers` SET `TEAM_ID`='$team',`FULL_NAME`='$name' WHERE `ID` = '$id'");
        if ($query->execute()) {
            return 200;
        } else {
            return 400;
        }
    }

    public function changeUserStatus($id, $newStatus)
    {
        $query = $this->conn->prepare("UPDATE `tblusers` SET `STATUS`='$newStatus' WHERE `ID` = '$id'");
        if ($query->execute()) {
            return 200;
        } else {
            return 400;
        }
    }
}
