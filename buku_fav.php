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

$is_favorite = $_GET['is_favorite'];
$id_books = $_GET['id_books']; 

if (is_favorite($id_books, $is_favorite)) {
    echo "<script>window.addEventListener('load', function() {
                        Swal.fire({
                        title: 'Buku Masuk Favorite!',
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
                        title: 'Buku Gagal Masuk Favorite!',
                        icon: 'error',
                        background: '#343a40',
                        color: '#ffffff'
                    }).then(function() {
                        document.location.href = 'buku_saya.php';
                    });                
                    })</script>";
}
