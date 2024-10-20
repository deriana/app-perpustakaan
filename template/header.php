<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("koneksi.php");

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = 'Guest';
}

$id_user = $_SESSION['id_user'];

$query = "SELECT pf_img FROM users WHERE id_user = '$id_user'";
$result = mysqli_query($koneksi, $query);

// Menetapkan gambar default
$default_image = 'assets/images/aigis.jpg'; // Gambar default jika tidak ada gambar profil
$pf_img = $default_image; // Awalnya set ke gambar default

// Cek jika query berhasil
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    // Jika pf_img tidak kosong, gunakan gambar yang ada
    if (!empty($row['pf_img'])) {
        $pf_img = $row['pf_img'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Perpustakaan Digital</title>
    <!-- plugins:css -->
    <link
        rel="stylesheet"
        href="assets/vendors/mdi/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css" />
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link
        rel="stylesheet"
        href="assets/vendors/jvectormap/jquery-jvectormap.css" />
    <link
        rel="stylesheet"
        href="assets/vendors/flag-icon-css/css/flag-icon.min.css" />
    <link
        rel="stylesheet"
        href="assets/vendors/owl-carousel-2/owl.carousel.min.css" />
    <link
        rel="stylesheet"
        href="assets/vendors/owl-carousel-2/owl.theme.default.min.css" />
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<style>
    /* Membuat background modal menjadi transparan dengan warna gelap */
    .modal-content {
        background-color: rgba(0, 0, 0, 0.7);
        /* Warna hitam dengan transparansi 70% */
        border-radius: 10px;
        /* Opsional: menambahkan sedikit rounded corner */
        color: white;
        /* Menjadikan teks di dalam modal berwarna putih */
    }

    /* Mengubah latar belakang overlay modal */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
        /* Overlay yang lebih gelap namun tetap transparan */
    }
</style>


<body>
    <div class="container-scroller">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <div
                class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
                <a class="sidebar-brand brand-logo" href="index.html"><img src="assets/images/ziebook.png" alt="logo" /></a>
                <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="assets/images/zb.png" alt="logo" /></a>
            </div>
            <ul class="nav">
                <li class="nav-item profile">
                    <div class="profile-desc">
                        <div class="profile-pic">
                            <div class="count-indicator">
                                <img class="img-xs rounded-circle" src="pf_img/<?= $pf_img; ?>" alt="Profile Image" />
                                <span class="count bg-success"></span>
                            </div>
                            <div class="profile-name">
                                <h5 class="mb-0 font-weight-normal"><?= $username; ?></h5>
                                <span>Pengunjung</span>
                            </div>
                        </div>
                    </div>
                </li>

                <?php if (isset($_SESSION['username'])) : ?>
                    <li class="nav-item nav-category">
                        <span class="nav-link">Navigation</span>
                    </li>
                    <li class="nav-item menu-items">
                        <a class="nav-link" href="index.php">
                            <span class="menu-icon">
                                <i class="mdi mdi-speedometer"></i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item menu-items">
                        <a class="nav-link" href="list_buku.php">
                            <span class="menu-icon">
                                <i class="mdi mdi-book"></i>
                            </span>
                            <span class="menu-title">List Buku</span>
                        </a>
                    </li>

                    <?php if ($_SESSION['role'] == 'users') : ?>
                        <li class="nav-item menu-items">
                            <a class="nav-link" href="cart.php">
                                <span class="menu-icon">
                                    <i class="mdi mdi-cart"></i>
                                </span>
                                <span class="menu-title">Keranjang</span>
                            </a>
                        </li>
                        <li class="nav-item menu-items">
                            <a class="nav-link" href="buku_saya.php">
                                <span class="menu-icon">
                                    <i class="mdi mdi-book-open-page-variant"></i>
                                </span>
                                <span class="menu-title">Buku Saya</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['role'] == 'admin') : ?>
                        <li class="nav-item menu-items">
                            <a class="nav-link" href="laporan.php">
                                <span class="menu-icon">
                                    <i class="mdi mdi-chart-bar"></i>
                                </span>
                                <span class="menu-title">Laporan</span>
                            </a>
                        </li>
                        <li class="nav-item menu-items">
                            <a class="nav-link" href="riwayat_user.php">
                                <span class="menu-icon">
                                    <i class="mdi mdi mdi-clock"></i>
                                </span>
                                <span class="menu-title">Riwayat User</span>
                            </a>
                        </li>
                        <li class="nav-item menu-items">
                            <a
                                class="nav-link"
                                href="users.php">
                                <span class="menu-icon">
                                    <i class="mdi mdi-account"></i>
                                </span>
                                <span class="menu-title">Users</span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

            </ul>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar p-0 fixed-top d-flex flex-row">
                <div
                    class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
                    <a class="xnavbar-brand brand-logo-mini" href="index.php"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
                </div>
                <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
                    <button
                        class="navbar-toggler navbar-toggler align-self-center"
                        type="button"
                        data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>
                    <ul class="navbar-nav w-100">
                        <li class="nav-item w-100">
                            <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Search Book" />
                            </form>
                        </li>
                    </ul>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item dropdown">
                            <a
                                class="nav-link"
                                id="profileDropdown"
                                href="#"
                                data-toggle="dropdown">
                                <div class="navbar-profile">
                                    <img class="img-xs rounded-circle" src="pf_img/<?= $pf_img; ?>" alt="Profile Image" />
                                    <p class="mb-0 d-none d-sm-block navbar-profile-name">
                                        <?= $username; ?>
                                    </p>
                                    <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                                </div>
                            </a>
                            <div
                                class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                                aria-labelledby="profileDropdown">
                                <h6 class="p-3 mb-0">Profile</h6>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item" href="logout.php">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-dark rounded-circle">
                                            <i class="mdi mdi-logout text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <p class="preview-subject mb-1">Log out</p>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <p class="p-3 mb-0 text-center">Advanced settings</p>
                            </div>
                        </li>
                    </ul>
                    <button
                        class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
                        type="button"
                        data-toggle="offcanvas">
                        <span class="mdi mdi-format-line-spacing"></span>
                    </button>
                </div>
            </nav>