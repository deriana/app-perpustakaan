<?php
session_start();
require_once("function.php");

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

// Cek jika ada ID buku yang dikirim melalui GET
if (isset($_GET['id_books'])) {
    $id_books = $_GET['id_books'];

    // Cek apakah buku sudah dipinjam
    if (is_already_borrowed($id_user, $id_books)) {
        echo "<script>alert('Anda sudah meminjam buku ini!');</script>";
        echo "<script>window.location.href = 'list_buku.php';</script>";
        exit();
    }

    // Cek apakah buku sudah ada di keranjang
    if (is_already_in_cart($id_user, $id_books)) {
        echo "<script>alert('Buku ini sudah ada di keranjang!');</script>";
        echo "<script>window.location.href = 'list_buku.php';</script>";
        exit();
    }

    // Tambahkan buku ke keranjang
    // Tambahkan buku ke keranjang
    if (add_to_cart($id_user, $id_books)) {
        echo "<script>
            alert('Buku berhasil ditambahkan ke keranjang!');
            window.location.href = 'list_buku.php'; // Redirect ke halaman keranjang
          </script>";
    } else {
        echo "<script>alert('Gagal menambahkan buku ke keranjang. Buku sudah ada di keranjang.');</script>";
        echo "<script>window.location.href = 'list_buku.php';</script>";
    }
} else {
    echo "<script>alert('ID buku tidak valid.');</script>";
    echo "<script>window.location.href = 'list_buku.php';</script>";
}
