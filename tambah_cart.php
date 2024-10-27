<?php
session_start();
include_once("template/header.php");
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
        echo "<script>
            window.addEventListener('load', function() {
                Swal.fire({
                    title: 'Anda Sudah Meminjam Buku Ini',
                    background: '#343a40',
                    color: '#ffffff'
                }).then(function() {
                    document.location.href = 'list_buku.php';
                });
            });
        </script>";
        exit();
    }

    // Cek apakah buku sudah ada di keranjang
    if (is_already_in_cart($id_user, $id_books)) {
        echo "<script>
            window.addEventListener('load', function() {
                Swal.fire({
                    title: 'Buku Sudah Di Keranjang',
                    background: '#343a40',
                    color: '#ffffff'
                }).then(function() {
                    document.location.href = 'list_buku.php';
                });
            });
        </script>";
        exit();
    }

    // Tambahkan buku ke keranjang
    if (add_to_cart($id_user, $id_books)) {
        echo "<script>
            window.addEventListener('load', function() {
                Swal.fire({
                    title: 'Buku Sudah Masuk Keranjang!',
                    icon: 'success',
                    background: '#343a40',
                    color: '#ffffff'
                }).then(function() {
                    document.location.href = 'list_buku.php';
                });
            });
        </script>";
    } else {
        echo "<script>
            window.addEventListener('load', function() {
                Swal.fire({
                    title: 'Buku Gagal Masuk Keranjang!',
                    icon: 'error',
                    background: '#343a40',
                    color: '#ffffff'
                }).then(function() {
                    document.location.href = 'list_buku.php';
                });
            });
        </script>";
    }
} else {
    echo "<script>
        window.addEventListener('load', function() {
            Swal.fire({
                title: 'Buku yang kamu maksud gak ada nih',
                icon: 'error',
                background: '#343a40',
                color: '#ffffff'
            }).then(function() {
                document.location.href = 'list_buku.php';
            });
        });
    </script>";
}
?>
