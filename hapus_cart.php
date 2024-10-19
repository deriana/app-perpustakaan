<?php
session_start();
require_once("function.php");

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID user dari session
$id_user = $_SESSION['id_user'];

// Cek apakah id_cart diterima
if (isset($_GET['id_cart'])) {
    $id_cart = $_GET['id_cart'];

    // Hapus buku dari cart
    if (query("DELETE FROM cart WHERE id_cart = '$id_cart' AND id_user = '$id_user'")) {
        // Redirect kembali ke halaman keranjang setelah berhasil dihapus
        header("Location: cart.php");
        exit();
    } else {
        echo "<script>alert('Gagal menghapus buku dari keranjang.');</script>";
        header("Location: cart.php"); // Kembali ke halaman cart meskipun gagal
        exit();
    }
} else {
    // Jika tidak ada id_cart, redirect ke cart
    header("Location: cart.php");
    exit();
}
?>
