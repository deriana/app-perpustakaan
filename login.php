<?php
// Memulai sesi
session_start();
require("koneksi.php");

if (isset($_POST['login'])) {
    // Mengambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false; // Menangkap opsi remember

    // Query untuk memeriksa user di database
    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE user_name = '$username'");

    // Jika username ditemukan
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        // Verifikasi password yang diinput dengan password yang sudah di-hash di database
        if (password_verify($password, $row['user_password'])) {
            // Set sesi
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $row['role'];
            $_SESSION['pf_img'] = $row['pf_img'];


            // Jika 'Remember Me' dicentang, simpan username di cookie
            if ($remember) {
                setcookie('username', $username, time() + (86400 * 30), "/"); // cookie valid selama 30 hari
            } else {
                // Hapus cookie jika tidak dicentang
                if (isset($_COOKIE['username'])) {
                    setcookie('username', '', time() - 3600, "/");
                }
            }

            // Redirect ke halaman index
            header("Location: index.php");
            exit;
        } else {
            // Jika password salah
            $error_message = "Password Salah!";
        }
    } else {
        // Jika username tidak ditemukan
        $error_message = "Username tidak ditemukan";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Perpustakaan Digital</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/images/favicon.png" />
</head>

<style>
    .background-page {
        background-image: url("assets/background.jpg");
        background-size: cover;
    }
</style>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="row w-100 m-0">
                <div class="content-wrapper full-page-wrapper d-flex align-items-center auth background-page">
                    <div class="card col-lg-4 mx-auto">
                        <div class="card-body px-5 py-5">
                            <h3 class="card-title text-left mb-3">Login</h3>

                            <!-- Jika ada error, tampilkan notifikasi -->
                            <?php if (isset($error_message)) : ?>
                                <div class="alert alert-danger mt-3" role="alert">
                                    <?= htmlspecialchars($error_message) ?>
                                </div>
                            <?php endif; ?>

                            <form action="" method="POST">
                                <div class="form-group">
                                    <label>Username *</label>
                                    <input type="text" class="form-control p_input" id="username" name="username" value="<?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : ''; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Password *</label>
                                    <input type="password" class="form-control p_input" id="password" name="password" required>
                                </div>
                                <div class="form-group d-flex align-items-center justify-content-between">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="remember"> Remember me
                                        </label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" name="login" class="btn btn-primary btn-block enter-btn">Login</button>
                                </div>
                                <p class="sign-up">Don't have an Account?<a href="register.php"> Sign Up</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
</body>

</html>