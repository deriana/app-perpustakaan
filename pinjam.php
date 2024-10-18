<?php
session_start();
require_once 'function.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Anda harus login untuk meminjam buku.');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user']; 
$id_books = $_GET['id_books']; 

// Panggil fungsi untuk meminjam buku
if (pinjam_buku($id_user, $id_books)) {
    echo "<script>alert('Buku berhasil dipinjam!');</script>";
    echo "<script>window.location.href='buku_saya.php';</script>"; // Redirect ke halaman buku saya
} else {
    echo "<script>alert('Buku sudah dipinjam atau terjadi kesalahan.');</script>";
    echo "<script>window.location.href='list_buku.php';</script>";
}
?>
