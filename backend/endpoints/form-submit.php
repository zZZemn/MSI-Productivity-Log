<?php
include('../db/db_class.php');
$db = new global_class();

if (isset($_POST['submitType'])) {
    if ($_POST['submitType'] === 'Login') {
        $userId = $_POST['userId'];
        $sql = $db->login($userId);
        if ($sql->num_rows > 0) {
            $user = $sql->fetch_array();
            if ($user['STATUS']) {
                session_start();
                $_SESSION['id'] = $user['ID'];
                if ($user['USER_TYPE'] === 'admin') {
                    echo 'admin';
                } else {
                    $getLoginWithoutLogout = $db->getNotLoginWithoutLogout();
                    if ($getLoginWithoutLogout->num_rows > 0) {
                        $loginDetails = $getLoginWithoutLogout->fetch_array();
                        $_SESSION['login_id'] = $loginDetails['LOGIN_ID'];
                        echo 'No need to select shift';
                    } else {
                        echo 'user';
                    }
                }
            } else {
                echo '400';
            }
        } else {
            echo '400';
        }
    } elseif ($_POST['submitType'] === 'AddNewAccount') {
        $name = $_POST['name'];
        $team = $_POST['team'];
        echo $db->addNewUser($name, $team);
    } elseif ($_POST['submitType'] === 'EditAccount') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $team = $_POST['team'];
        echo $db->editUser($id, $name, $team);
    } elseif ($_POST['submitType'] === 'ChangeAccountStatus') {
        $id = $_POST['id'];
        $newStatus = $_POST['newStatus'];
        echo $db->changeUserStatus($id, $newStatus);
    } elseif ($_POST['submitType'] === 'SelectShift') {
        session_start();
        $id = $_SESSION['id'];
        $shift = $_POST['shift'];
        echo $db->selectShift($id, $shift);
    } elseif ($_POST['submitType'] === 'AddNewEntry') {
        session_start();
        $id = $_SESSION['id'];
        echo $db->addNewEntry($id, $_POST);
    } elseif ($_POST['submitType'] === 'StopEntry') {
        $id = $_POST['id'];
        echo $db->stopEntry($id);
    }
}
