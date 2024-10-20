<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['id_user'])) {
    // Jika pengguna belum login akan di direct ke login.php
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != 'users') {
    header("Location: index.php");
    exit();
}

include_once("template/header.php");
require_once("function.php");

$id_user = $_SESSION['id_user']; // Ambil ID user dari session

// Check if form is submitted

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle adding a book
    if (isset($_POST['simpan'])) {
        if (tambah_buku($_POST) > 0) {
            echo "<script>
                    alert('Data berhasil ditambahkan!');
                    document.location.href = 'list_buku.php'; // Redirect to the book list page
                  </script>";
        } else {
            echo "<script>
                    alert('Data gagal ditambahkan. Coba lagi!');
                  </script>";
        }
    }

    // Handle editing a book
    if (isset($_POST['edit'])) {
        if (edit_buku($_POST) > 0) {
            echo "<script>
                    alert('Data berhasil diperbarui!');
                    document.location.href = 'list_buku.php'; // Redirect ke halaman daftar buku
                  </script>";
        } else {
            echo "<script>
                    alert('Data gagal diperbarui. Coba lagi!');
                  </script>";
        }
    }
}

$query = "SELECT b.id_books, b.title, b.author, b.synopsis, b.cover_path, 
                 br.is_read, br.is_favorite, br.borrow_date 
          FROM borrows br
          JOIN books b ON br.id_books = b.id_books
          WHERE br.id_user = '$id_user' AND br.status = 'borrowed'";

?>
<style>
    .book-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        /* 2 kolom */
        gap: 20px;
        /* Jarak antar item */
    }

    .book-item {
        width: 250px;
        height: 350px;
        background: white;
        border-radius: 20px;
        display: flex;
        flex-direction: row;
        transition: 0.5s ease-in-out;
        overflow: hidden;
    }

    .book-item>img {
        width: 250px;
        height: 350px;
        border-radius: 20px;
        object-fit: cover;
    }

    .book-item:hover {
        transform: scale(1.1);
        border-radius: 20px 50px 20px 20px;
        width: 100%;

        .book-desk {
            display: block;
            display: flex;
            gap: 10px;
            justify-content: space-around;
            padding: 0 10px;
            overflow: hidden;
        }
    }

    .book-desk {
        color: black;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 10px;
        flex-grow: 1;
    }

    .book-desk-item {
        display: flex;
        flex-direction: row;
        align-items: center;
        background-color: gray;
        gap: 10px;
        padding: 0 5px;
        border-radius: 10px;
    }

    .book-desk-item>i {
        font-size: 20px;
        color: white;
    }

    .book-desk-item>h3 {
        font-size: 1em !important;
    }

    .book-desk>.book-desk-item>div {
        width: 20px;
        height: 20px;
        background-color: white;
        border-radius: 50%;
    }

    .book-desk {
        display: none;
    }

    .book-desk>p {
        overflow-y: auto;
    }
</style>

<div class="main-panel m-4 d-flex flex-direction-column">
    <h1>Daftar Buku yang Dipinjam</h1>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="book-container">
                    <?php
                    $query = "SELECT b.id_books, b.title, b.author, b.synopsis, b.cover_path, 
                            br.is_read, br.is_favorite, br.borrow_date 
                            FROM borrows br
                            JOIN books b ON br.id_books = b.id_books
                            WHERE br.id_user = '$id_user' AND br.status = 'borrowed'";

                    // Execute the query and display results
                    $borrowed_books = mysqli_query($koneksi, $query);

                    foreach ($borrowed_books as $buku): ?>
                        <div class="book-item">
                            <img src="uploads/<?= htmlspecialchars($buku['cover_path']); ?>" alt="sampul buku">
                            <div class="book-desk">
                                <h1><?= htmlspecialchars($buku['title']) ?></h1>
                                <div class="book-desk-item">
                                    <i class="mdi mdi-grease-pencil"></i>
                                    <h3>Author: <?= htmlspecialchars($buku['author']) ?></h3>
                                </div>
                                <div class="book-desk-item">
                                    <i class="mdi mdi-calendar"></i>
                                    <h3>Date Borrowed: <?= htmlspecialchars($buku['borrow_date']) ?></h3>
                                </div>
                                <p><?= htmlspecialchars($buku['synopsis']) ?></p>

                                <!-- Tombol tandai sudah dibaca -->
                                <?php if ($buku['is_read']): ?>
                                    <a href="sudah_dibaca.php?id_books=<?= htmlspecialchars($buku['id_books']); ?>&is_read=0" class="btn btn-warning">
                                        Tandai Belum Dibaca
                                    </a>
                                <?php else: ?>
                                    <a href="sudah_dibaca.php?id_books=<?= htmlspecialchars($buku['id_books']); ?>&is_read=1" class="btn btn-success">
                                        Tandai Sudah Dibaca
                                    </a>
                                <?php endif; ?>

                                <!-- Tombol Buku Favorite -->
                                <?php if ($buku['is_favorite']): ?>
                                    <a href="buku_fav.php?id_books=<?= htmlspecialchars($buku['id_books']); ?>&is_favorite=0" class="btn btn-warning">
                                        Delete Fav
                                    </a>
                                <?php else: ?>
                                    <a href="buku_fav.php?id_books=<?= htmlspecialchars($buku['id_books']); ?>&is_favorite=1" class="btn btn-success">
                                        Favorite
                                    </a>
                                <?php endif; ?>

                                <!-- Tombol Kembalikan Buku -->
                                <a href="kembalikan.php?id_books=<?= htmlspecialchars($buku['id_books']); ?>" class="btn btn-danger">
                                    Kembalikan Buku
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once("template/footer.php"); ?>