<?php

require_once "function.php";

if (isset($_GET['id_user'])) {
    $id = $_GET['id_user'];
    if(hapus_user($id) > 0) {
        echo "<script>alert('Buku berhasil dihapus')</script>";
        echo "<script>window.location.href='users.php'</script>";
    } else {
        echo "<script>alert('Buku gagal dihapus')</script>";
        echo "<script>window.location.href='users.php'</script>";
    }
}