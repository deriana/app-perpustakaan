<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
include_once ("template/header.php");
require_once "function.php";

if (isset($_GET['id_user'])) {
    $id = $_GET['id_user'];
    if (hapus_user($id) > 0) {
        echo "<script>
                window.addEventListener('load', function() {
                        Swal.fire({
                        title: 'User berhasil dihapus!',
                        icon: 'success',
                        background: '#343a40',
                        color: '#ffffff'
                    }).then(function() {
                        document.location.href = 'users.php';
                    });                
                    })
            </script>";
    } else {
        echo "<script>
                window.addEventListener('load', function() {
                        Swal.fire({
                        title: 'User Gagal dihapus!',
                        icon: 'error',
                        background: '#343a40',
                        color: '#ffffff'
                    }).then(function() {
                        document.location.href = 'users.php';
                    });                
                    })
            </script>";
    }
}

?>