<?php
include('components/header.php');
?>

<h5>Hello, <?= $user['FULL_NAME'] ?></h5>

<?php
include('components/footer.php');
?>
<script>
    $('.nav-home').addClass('active');
</script>