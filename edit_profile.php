<?php

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}


$id_user = $_SESSION['id_user'];

include_once("template/header.php");
?>

<div class="main-panel m-4">

</div>

<?php
include_once("template/footer.php");
?>