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

    public function checkLoginId($id)
    {
        $query = $this->conn->prepare("SELECT * FROM `tblreport` WHERE `LOGIN_ID` = '$id'");
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }

    public function getNotLoginWithoutLogout()
    {
        $query = $this->conn->prepare("SELECT *
                                       FROM `tblreport` AS login
                                       WHERE `SUB_CATEGORY` = 'LOG IN'
                                       AND NOT EXISTS (
                                           SELECT 1
                                           FROM `tblreport` AS logout
                                           WHERE login.LOGIN_ID = logout.LOGIN_ID
                                           AND logout.SUB_CATEGORY = 'LOG OUT'
                                       );");
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }

    public function selectShift($id, $shift)
    {
        $sql = $this->login($id);
        if ($sql->num_rows > 0) {
            $user = $sql->fetch_array();
            $team = $user['TEAM_ID'];
            $date = date('Y-m-d');
            $time = date('H:i:s');

            $loginId = 'LOGIN_' . rand(00000, 99999);
            while ($this->checkLoginId($loginId)->num_rows > 0) {
                $loginId = 'LOGIN_' . rand(00000, 99999);
            }


            $query = $this->conn->prepare("INSERT INTO `tblreport` (`USER_ID`, `LOGIN_ID`,`TEAM`, `SHIFT`, `START_DATE`, `TASK_TYPE`, `ACTIVITY`, `CATEGORY`, `SUB_CATEGORY`, `REPORT_STATUS`, `VOLUME`, `TIME_START`, `TIME_STOP`) VALUES 
                                                                   ('$id','$loginId','$team','$shift','$date','SINGLE','OTHER ACTIVITIES','ATTENDANCE','LOG IN','SUBMITTED ON TIME','1.0','$time','$time')");
            if ($query->execute()) {
                $_SESSION['login_id'] = $loginId;
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
        if ($id == '') {
            $query = $this->conn->prepare("SELECT tr.*, tu.* FROM `tblreport` tr JOIN tblusers tu ON tr.USER_ID = tu.ID");
        } else {
            $query = $this->conn->prepare("SELECT * FROM `tblreport` WHERE `USER_ID` = '$id'");
        }
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }

    public function checkIfThereIsPendingEntry($userId)
    {
        $query = $this->conn->prepare("SELECT * FROM `tblreport` WHERE `REPORT_STATUS` = 'Pending' AND `USER_ID` = '$userId'");
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }

    public function checkMultiTaskId($id)
    {
        $query = $this->conn->prepare("SELECT * FROM `tblreport` WHERE `MULTI_TASK_ID` = '$id'");
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }

    public function addNewEntry($id, $loginId, $post)
    {

        $sql = $this->login($id);
        if ($sql->num_rows > 0) {
            $checkPendingEntry = $this->checkIfThereIsPendingEntry($id);
            if ($checkPendingEntry->num_rows > 0) {
                $pengingEntry = $checkPendingEntry->fetch_array();
                $multiTaskId = $pengingEntry['MULTI_TASK_ID'];
            } else {
                $multiTaskId = 'MULTI_' . rand(000000, 999999);
                $checkMultiTaskId = $this->checkMultiTaskId($multiTaskId);
                while ($checkMultiTaskId->num_rows > 0) {
                    $multiTaskId = 'MULTI_' . rand(000000, 999999);
                    $checkMultiTaskId = $this->checkMultiTaskId($multiTaskId);
                }
            }

            $team = $post['team'];
            $activity = $post['activity'];
            $category = $post['category'];
            $subCategory = $post['subCategory'];
            $date = date('Y-m-d');
            $time = date('H:i:s');

            $shift = 'To Add';

            $query = $this->conn->prepare("INSERT INTO `tblreport` (`USER_ID`,`LOGIN_ID`,`MULTI_TASK_ID`,`TEAM`, `SHIFT`, `START_DATE`, `TASK_TYPE`, `ACTIVITY`, `CATEGORY`, `SUB_CATEGORY`, `REPORT_STATUS`, `VOLUME`, `TIME_START`, `TIME_STOP`) VALUES 
                                                                   ('$id','$loginId','$multiTaskId','$team','$shift','$date','SINGLE','$activity','$category','$subCategory','Pending','','$time','')");
            if ($query->execute()) {
                return 200;
            } else {
                return 400;
            }
        } else {
            return 400;
        }
    }

    public function stopEntry($id)
    {
        $time = date('H:i:s');
        $query = $this->conn->prepare("UPDATE `tblreport` SET `REPORT_STATUS`='Submitted' ,`TIME_STOP`='$time' WHERE `ID` = '$id'");
        if ($query->execute()) {
            return 200;
        } else {
            return 400;
        }
    }

    public function getLoginDetails($id)
    {
        $query = $this->conn->prepare("SELECT * FROM `tblreport` WHERE `LOGIN_ID` = '$id' AND `SUB_CATEGORY` = 'LOG IN'");
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        }
    }

    public function logout($userId, $loginId)
    {
        $date = date('Y-m-d');
        $time = date('H:i:s');

        $getUser = $this->login($userId);
        $user = $getUser->fetch_array();

        $team = $user['TEAM_ID'];
        $getLoginDetails = $this->getLoginDetails($loginId);
        $loginDetails = $getLoginDetails->fetch_array();
        $shift = $loginDetails['SHIFT'];

        $query = $this->conn->prepare("INSERT INTO `tblreport` (`LOGIN_ID`,`USER_ID`,`TEAM`, `SHIFT`, `START_DATE`, `TASK_TYPE`, `ACTIVITY`, `CATEGORY`, `SUB_CATEGORY`, `REPORT_STATUS`, `VOLUME`, `TIME_START`, `TIME_STOP`) VALUES ('$loginId','$userId','$team','$shift','$date','SINGLE','OTHER ACTIVITIES','ATTENDANCE','LOG OUT','SUBMITTED ON TIME','','$time','$time')");

        if ($query->execute()) {
            return $user;
        } else {
            return 400;
        }
    }
}
