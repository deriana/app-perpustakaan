<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['id_user'])) {
    // Jika pengguna belum login akan di direct ke login.php
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] == 'admin') {
    header("Location: index.php");
    exit();
}


include_once("template/header.php");
require_once("function.php");

$activity_type = isset($_GET['activity_type']) ? $_GET['activity_type'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

$id_user = $_SESSION['id_user'];

$activity_logs = get_activity_logs($id_user, $activity_type, $start_date, $end_date);
?>

<div class="main-panel m-4">
    <h1>Aktivitas Pengguna</h1>
    <form method="GET" action="">
        <div class="form-group">
            <label for="activity_type">Jenis Aktivitas</label>
            <select name="activity_type" class="form-control" id="activity_type">
                <option value="">Semua Aktivitas</option>
                <option value="borrow" <?= ($activity_type == 'borrow') ? 'selected' : '' ?>>Peminjaman</option>
                <option value="return" <?= ($activity_type == 'return') ? 'selected' : '' ?>>Pengembalian</option>
                <option value="remove" <?= ($activity_type == 'remove') ? 'selected' : '' ?>>Penghapusan</option>
                <option value="update" <?= ($activity_type == 'update') ? 'selected' : '' ?>>Pembaruan</option>
            </select>
        </div>

        <div class="form-group">
            <label for="start_date">Tanggal Awal</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $start_date ?>">
        </div>

        <div class="form-group">
            <label for="end_date">Tanggal Akhir</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $end_date ?>">
        </div>

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Tabel aktivitas -->
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pengguna</th>
                <th>Judul Buku</th>
                <th>Jenis Aktivitas</th>
                <th>Tanggal Aktivitas</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (!empty($activity_logs)): ?>
                <?php foreach ($activity_logs as $log): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($log['user_name']) ?></td>
                        <td><?= htmlspecialchars($log['title']) ?></td>
                        <td><?= htmlspecialchars($log['activity_type']) ?></td>
                        <td><?= htmlspecialchars($log['timestamp']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Tidak ada aktivitas yang ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<?php include_once("template/footer.php");
?>