<?php
include('../backend/db/db_class.php');
$db = new global_class();

session_start();
if (isset($_SESSION['id'])) {
    $sql = $db->login($_SESSION['id']);
    if ($sql->num_rows > 0) {
        $user = $sql->fetch_array();
    } else {
        header('Location: ../index.php');
    }
} else {
    header('Location: ../index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSI Productivity Log</title>
    <link href="../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="../node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../css/alert.css">
    <link rel="stylesheet" href="css/index-style.css">
</head>

<body>
    <div class="alert alert-danger">
    </div>
    <div class="alert alert-success">
    </div>
    <main class="container-fluid p-4 pt-2">
        <ul class="nav nav-tabs m-3">
            <li class="nav-item">
                <a class="nav-link nav-home" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-logout text-danger" href="../logout.php">Logout</a>
            </li>
        </ul>
        <div class="container-fluid">