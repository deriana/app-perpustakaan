<?php

require_once "function.php";

if (isset($_GET['id_books'])) {
    $id = $_GET['id_books'];
    if (hapus_buku($id) > 0) {
        echo "<script>alert('Buku berhasil dihapus')</script>";
        echo "<script>window.location.href='list_buku.php'</script>";
    } else {
        echo "<script>alert('Buku gagal dihapus')</script>";
        echo "<script>window.location.href='list_buku.php'</script>";
    }
}
