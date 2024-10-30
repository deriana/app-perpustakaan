<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] == 'admin') {
    header("Location: index.php");
    exit();
}

include_once("template/header.php");
require_once("function.php");

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
$status_pengembalian = isset($_GET['status_pengembalian']) ? $_GET['status_pengembalian'] : '';

$id_user = $_SESSION['id_user'];

// Panggil fungsi get_borrow_logs dengan filter status pengembalian
$borrow_logs = get_borrow_logs($id_user, $start_date, $end_date, $status_pengembalian);
?>

<div class="main-panel m-4">
    <h1>Log Peminjaman Buku</h1>
    <form method="GET" action="">
        <div class="form-group">
            <label for="start_date">Tanggal Awal</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $start_date ?>">
        </div>

        <div class="form-group">
            <label for="end_date">Tanggal Akhir</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $end_date ?>">
        </div>

        <div class="form-group">
            <label for="status_pengembalian">Status Pengembalian</label>
            <select name="status_pengembalian" class="form-control" id="status_pengembalian">
                <option value="" <?= ($status_pengembalian == '') ? 'selected' : '' ?>>Semua</option>
                <option value="dikembalikan" <?= ($status_pengembalian == 'dikembalikan') ? 'selected' : '' ?>>Dikembalikan</option>
                <option value="belum_dikembalikan" <?= ($status_pengembalian == 'belum_dikembalikan') ? 'selected' : '' ?>>Belum Dikembalikan</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Tabel log peminjaman -->
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pengguna</th>
                <th>Judul Buku</th>
                <th>Tanggal Peminjaman</th>
                <th>Tanggal Pengembalian</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (!empty($borrow_logs)): ?>
                <?php foreach ($borrow_logs as $log): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($log['user_name']) ?></td>
                        <td><?= htmlspecialchars($log['title']) ?></td>
                        <td><?= htmlspecialchars($log['borrow_date']) ?></td>
                        <td><?= !empty($log['return_date']) ? htmlspecialchars($log['return_date']) : "Belum Dikembalikan" ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Tidak ada data peminjaman yang ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include_once("template/footer.php"); ?>
