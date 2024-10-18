<?php
session_start();
require_once 'function.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Anda harus login untuk mengembalikan buku.');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user']; // Ambil ID user dari session
$id_books = $_GET['id_books'];   // Ambil ID buku dari URL

// Panggil fungsi untuk mengembalikan buku
if (kembalikan_buku($id_user, $id_books)) {
    echo "<script>alert('Buku berhasil dikembalikan!');</script>";
    echo "<script>window.location.href='buku_saya.php';</script>"; // Redirect ke halaman buku saya
} else {
    echo "<script>alert('Terjadi kesalahan saat mengembalikan buku.');</script>";
    echo "<script>window.location.href='buku_saya.php';</script>";
}
?>
