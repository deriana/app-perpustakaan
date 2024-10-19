<?php
session_start();
require_once 'function.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Anda harus login untuk mengembalikan buku.');</script>";
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$is_favorite = $_GET['is_favorite'];
$id_books = $_GET['id_books'];   // Ambil ID buku dari URL (pastikan ini diambil dengan benar)

// Panggil fungsi untuk mengembalikan buku
if (is_favorite($id_books, $is_favorite)) {
    echo "<script>alert('Buku berhasil Diperbarui!');</script>";
    echo "<script>window.location.href='buku_saya.php';</script>"; // Redirect ke halaman buku saya
} else {
    echo "<script>alert('Terjadi kesalahan saat Memperbarui buku.');</script>";
    echo "<script>window.location.href='buku_saya.php';</script>";
}
