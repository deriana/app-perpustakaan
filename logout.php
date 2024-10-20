<?php 
// session dimulai
session_start();
// session di cancel
session_unset();
// session di hapus
session_destroy();
// menuju lokasi login.php
header("Location: login.php");
exit();
?>