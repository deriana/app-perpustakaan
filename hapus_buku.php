<?php
include_once("template/header.php");
require_once "function.php";

if (isset($_GET['id_books'])) {
    $id = $_GET['id_books'];
    if (hapus_buku($id) > 0) {
        echo "<script>
                window.addEventListener('load', function() {
                        Swal.fire({
                        title: 'Buku berhasil dihapus!',
                        icon: 'success',
                        background: '#343a40',
                        color: '#ffffff'
                    }).then(function() {
                        document.location.href = 'list_buku.php';
                    });                
                    })
            </script>";
    } else {
        echo "<script>
                window.addEventListener('load', function() {
                        Swal.fire({
                        title: 'Buku gagal dihapus!',
                        icon: 'error',
                        background: '#343a40',
                        color: '#ffffff'
                    }).then(function() {
                        document.location.href = 'list_buku.php';
                    });                
                    })
            </script>";
    }
}
