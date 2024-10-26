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
        grid-template-columns: repeat(1, 1fr);
        gap: 20px;
    }

    @media (min-width: 576px) {

        .book-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 768px) {

        .book-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (min-width: 992px) {

        .book-container {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .book-item {
        width: 250px;
        height: 350px;
        border-radius: 20px;
        display: flex;
        transition: 0.5s ease-in-out;
        cursor: pointer;
    }

    .book-item:hover {
        transform: scale(1.1);
    }

    .book-item>img {
        width: 100%;
        height: 100%;
        border-radius: 20px 20px 0 0;
        object-fit: cover;
    }

    .book-desk {
        color: black;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 10px;
        flex-grow: 1;
        padding: 10px;
    }

    #modal-img {
        width: 100%;
        height: auto;
        border-radius: 10px;
        margin-bottom: 15px;
    }
</style>

<div class="main-panel m-4 d-flex flex-direction-column">
    <h1>Daftar Buku yang Dipinjam</h1>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">

                <div class="search-container mb-4">
                    <form action="" method="get" class="d-flex align-items-center">
                        <input type="text" name="search" placeholder="Cari berdasarkan judul atau penulis" class="form-control mr-2">
                        <button type="submit" class="btn btn-primary">Cari</button>
                        <a href="buku_saya.php" class="btn btn-secondary ml-2">Reset</a>
                    </form>
                </div>

                <div class="book-container">
                    <?php
                    $borrowed_books = mysqli_query($koneksi, $query);

                    foreach ($borrowed_books as $buku): ?>
                        <div class="book-item" data-toggle="modal" data-target="#detailModal" data-title="<?= htmlspecialchars($buku['title']) ?>"
                            data-author="<?= htmlspecialchars($buku['author']) ?>" data-borrow-date="<?= htmlspecialchars($buku['borrow_date']) ?>"
                            data-synopsis="<?= htmlspecialchars($buku['synopsis']) ?>" data-id="<?= htmlspecialchars($buku['id_books']) ?>"
                            data-cover-path="<?= htmlspecialchars($buku['cover_path']) ?>" data-is-read="<?= $buku['is_read'] ?>" data-is-favorite="<?= $buku['is_favorite'] ?>">
                            <img src="uploads/<?= htmlspecialchars($buku['cover_path']); ?>" alt="sampul buku">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modal-img" src="" alt="Gambar Buku">
                <h3 id="modal-title"></h3>
                <p id="modal-author"></p>
                <p id="modal-borrow-date"></p>
                <p id="modal-synopsis"></p>
                <div id="modal-buttons">
                    <a id="read-toggle" href="#" class="btn btn-primary mb-2"></a>
                    <a id="favorite-toggle" href="#" class="btn btn-primary mb-2"></a>
                </div>
                <a id="return-button" href="#" class="btn btn-danger">Kembalikan Buku</a>
            </div>
        </div>
    </div>
</div>

<?php include_once("template/footer.php"); ?>

<script>
    // Script untuk mengisi modal dengan detail buku
    document.addEventListener('DOMContentLoaded', function() {
        const bookItems = document.querySelectorAll('.book-item');
        const modalTitle = document.getElementById('modal-title');
        const modalAuthor = document.getElementById('modal-author');
        const modalBorrowDate = document.getElementById('modal-borrow-date');
        const modalSynopsis = document.getElementById('modal-synopsis');
        const modalImg = document.getElementById('modal-img');
        const readToggle = document.getElementById('read-toggle');
        const favoriteToggle = document.getElementById('favorite-toggle');
        const returnButton = document.getElementById('return-button');

        bookItems.forEach(item => {
            item.addEventListener('click', function() {
                const title = item.getAttribute('data-title');
                const author = item.getAttribute('data-author');
                const borrowDate = item.getAttribute('data-borrow-date');
                const synopsis = item.getAttribute('data-synopsis');
                const bookId = item.getAttribute('data-id');
                const coverPath = item.getAttribute('data-cover-path');
                const isRead = item.getAttribute('data-is-read') === '1';
                const isFavorite = item.getAttribute('data-is-favorite') === '1';

                modalTitle.textContent = title;
                modalAuthor.textContent = "Author: " + author;
                modalBorrowDate.textContent = "Date Borrowed: " + borrowDate;
                modalSynopsis.textContent = synopsis;
                modalImg.src = `uploads/${coverPath}`; // Menetapkan sumber gambar di modal

                // Set read toggle button
                readToggle.textContent = isRead ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca';
                readToggle.href = `sudah_dibaca.php?id_books=${bookId}&is_read=${isRead ? '0' : '1'}`;

                // Set favorite toggle button
                favoriteToggle.textContent = isFavorite ? 'Hapus Dari Favorit' : 'Tandai Sebagai Favorit';
                favoriteToggle.href = `buku_fav.php?id_books=${bookId}&is_favorite=${isFavorite ? '0' : '1'}`;

                // Set return button link
                returnButton.href = `kembalikan.php?id_books=${bookId}`;
            });
        });
    });
</script>