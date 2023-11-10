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
                    echo 'user';
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
    }
}