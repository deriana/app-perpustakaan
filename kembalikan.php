<?php
session_start();
include_once("template/header.php");
require_once 'function.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>window.addEventListener('load', function() {
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

$id_user = $_SESSION['id_user']; // Ambil ID user dari session
$id_books = $_GET['id_books'];   // Ambil ID buku dari URL (pastikan ini diambil dengan benar)

// Panggil fungsi untuk mengembalikan buku
if (kembalikan_buku($id_user, $id_books)) {
    echo "<script>
    window.addEventListener('load', function() {
            Swal.fire({
            title: 'Buku Dikembalikan!',
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
            title: 'Buku gagal Dikembalikan!',
            icon: 'error',
            background: '#343a40',
            color: '#ffffff'
        }).then(function() {
            document.location.href = 'buku_saya.php';
        });                
        })
</script>";
}
