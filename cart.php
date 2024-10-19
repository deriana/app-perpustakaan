<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

include_once("template/header.php");
require_once("function.php");

$id_user = $_SESSION['id_user']; // Ambil ID user dari session

// Ambil buku-buku dalam keranjang
$cart_books = query("SELECT b.*, c.id_cart FROM books b JOIN cart c ON b.id_books = c.id_books WHERE c.id_user = '$id_user'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses peminjaman buku
    if (isset($_POST['borrow_books'])) {
        if (count($cart_books) == 0) {
            echo "<script>alert('Keranjang kosong!');</script>";
        } else {
            foreach ($cart_books as $book) {
                // Periksa apakah buku sudah dipinjam
                if (!is_already_borrowed($id_user, $book['id_books'])) {
                    // Tambahkan entri ke tabel peminjaman (borrows)
                    pinjam_buku($id_user, $book['id_books']);
                } else {
                    echo "<script>alert('Buku sudah dipinjam sebelumnya: " . htmlspecialchars($book['title']) . "');</script>";
                }
            }
            // Setelah peminjaman selesai, hapus semua buku dari keranjang
            clear_cart($id_user);
            echo "<script>alert('Peminjaman berhasil!');</script>";
            header("Location: some_page_after_borrowing.php"); // Ganti dengan halaman yang sesuai
            exit();
        }
    }
}
?>

<style>
    /* CSS Styling untuk tampilan keranjang buku */
    .book-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .book-item {
        width: 250px;
        height: 350px;
        background: white;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .book-item > img {
        width: 250px;
        height: 350px;
        border-radius: 20px;
        object-fit: cover;
    }
    .book-item:hover {
        transform: scale(1.05);
    }
    .book-desk {
        color: black;
        padding: 10px;
    }
</style>

<div class="main-panel m-4 d-flex flex-direction-column">
    <h1>Keranjang Buku</h1>

    <?php if (count($cart_books) > 0): ?>
        <ul class="book-container">
            <?php foreach ($cart_books as $book): ?>
                <li class="book-item">
                    <img src="uploads/<?= htmlspecialchars($book['cover_path']); ?>" alt="<?= htmlspecialchars($book['title']); ?>">
                    <div class="book-desk">
                        <h3><?= htmlspecialchars($book['title']); ?></h3>
                        <p><?= htmlspecialchars($book['author']); ?></p>
                        <form method="GET" action="hapus_cart.php" style="display:inline;">
                            <input type="hidden" name="id_cart" value="<?= htmlspecialchars($book['id_cart']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <form method="POST" action="">
            <button type="submit" name="borrow_books" class="btn btn-primary">Lanjutkan Peminjaman</button>
        </form>
    <?php else: ?>
        <p>Keranjang Anda kosong.</p>
    <?php endif; ?>
</div>

<?php include_once("template/footer.php"); ?>
