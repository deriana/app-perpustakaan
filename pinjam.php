<?php
session_start();
include_once("template/header.php");
require_once 'function.php';


// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>
                window.addEventListener('load', function() {
                        Swal.fire({
                        title: 'Anda Harus Login dulu',
                        background: '#343a40',
                        color: '#ffffff'
                    }).then(function() {
                        document.location.href = 'login.php';
                    });                
                    })</script>";
    exit;
}

$id_user = $_SESSION['id_user'];
$id_books = $_GET['id_books'];

// Panggil fungsi untuk meminjam buku
if (pinjam_buku($id_user, $id_books)) {
    echo "<script>
    window.addEventListener('load', function() {
            Swal.fire({
            title: 'Buku dipinjam!',
            icon: 'success',
            background: '#343a40',
            color: '#ffffff'
        }).then(function() {
            document.location.href = 'buku_saya.php';
        });                
        })
</script>";
} else {
    echo "<script>
    window.addEventListener('load', function() {
            Swal.fire({
            title: 'Buku gagal dipinjam!',
            icon: 'error',
            background: '#343a40',
            color: '#ffffff'
        }).then(function() {
            document.location.href = 'buku_saya.php';
        });                
        })
</script>";
}
