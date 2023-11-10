<?php
include('components/header.php');
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

<h5>Hello, <?= $user['FULL_NAME'] ?></h5>

<?php
include('components/footer.php');
?>
<script>
    $('.nav-home').addClass('active');
</script>