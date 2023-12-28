<?php
include('backend/db/db_class.php');
$db = new global_class();

session_start();
$userId = $_SESSION['id'];
$loginId = $_SESSION['login_id'];
$db->logout($userId, $loginId);

session_destroy();
header('Location: index.php');
exit;
