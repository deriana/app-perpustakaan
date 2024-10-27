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

$is_read = $_GET['is_read'];
$id_books = $_GET['id_books'];   // Ambil ID buku dari URL (pastikan ini diambil dengan benar)

if (is_read($id_books, $is_read)) {
    echo "<script>window.addEventListener('load', function() {
        Swal.fire({
        title: 'Buku Sudah Dibaca!',
        icon: 'success',
        background: '#343a40',
        color: '#ffffff'
    }).then(function() {
        document.location.href = 'buku_saya.php';
    });                
    })</script>";
} else {
    echo "<script>window.addEventListener('load', function() {
        Swal.fire({
        title: 'Buku Gagal Dibaca',
        icon: 'error',
        background: '#343a40',
        color: '#ffffff'
    }).then(function() {
        document.location.href = 'buku_saya.php';
    });                
    })</script>";
}
