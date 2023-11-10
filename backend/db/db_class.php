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

    public function selectShift($id, $shift)
    {
        $sql = $this->login($id);
        // var_dump($sql);
        if ($sql->num_rows > 0) {
            $user = $sql->fetch_array();
            $team = $user['TEAM_ID'];
            $date = date('Y-m-d');
            $time = date('H:i:s');
            $query = $this->conn->prepare("INSERT INTO `tblreport` (`USER_ID`, `TEAM`, `SHIFT`, `START_DATE`, `TASK_TYPE`, `ACTIVITY`, `CATEGORY`, `SUB_CATEGORY`, `REPORT_STATUS`, `VOLUME`, `TIME_START`, `TIME_STOP`) VALUES 
                                                                   ('$id','$team','$shift','$date','SINGLE','OTHER ACTIVITIES','ATTENDANCE','LOG IN','SUBMITTED ON TIME','1.0','$time','$time')");
            if ($query->execute()) {
                return 200;
            } else {
                return 400;
            }
        } else {
            return 400;
        }
    }

    public function getMyReports($id)
    {
        $query = $this->conn->prepare("SELECT * FROM `tblreport` WHERE `USER_ID` = '$id'");
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }
}
