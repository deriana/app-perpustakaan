<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

include_once("template/header.php");
require_once("function.php");
include_once("koneksi.php");

$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-d');
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-d');

$sql_periode_buku = "
    SELECT
        COUNT(*) AS jumlah_buku
    FROM
        books
    WHERE
        book_date between '$tanggal_awal' AND '$tanggal_akhir'
";
$result_buku_periode = mysqli_query($koneksi, $sql_periode_buku);
$row = mysqli_fetch_assoc($result_buku_periode);
$jumlah_buku_periode = $row['jumlah_buku'];

$sql_buku = "
    SELECT * 
    FROM books 
    WHERE book_date BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
    ORDER BY book_date DESC
";
$buku = query($sql_buku);

?>

<div class="main-panel m-4">
    <h1>Aktivitas Buku Perpustakaan</h1>
    <form method="GET" action="">
        <div class="form-group">
            <label for="tanggal_awal">Tanggal Awal</label>
            <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="<?= $tanggal_awal ?>">
        </div>

        <div class="form-group">
            <label for="tanggal_akhir">Tanggal Akhir</label>
            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="<?= $tanggal_akhir ?>">
        </div>
    </form>
    <!-- Tabel aktivitas -->
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Author</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (!empty($buku)): ?>
                <?php foreach ($buku as $log): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($log['title']) ?></td>
                        <td><?= htmlspecialchars($log['author']) ?></td>
                        <td><?= htmlspecialchars($log['book_date']) ?></td>
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