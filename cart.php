<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['id_user'])) {
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

// Ambil buku-buku dalam keranjang dengan opsi pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT b.*, c.id_cart 
          FROM books b 
          JOIN cart c ON b.id_books = c.id_books 
          WHERE c.id_user = '$id_user' AND b.title LIKE '%$search%'";
$cart_books = query($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses peminjaman buku
    if (isset($_POST['borrow_books'])) {
        if (count($cart_books) == 0) {
            echo "<script>
                    Swal.fire({
                        title: 'Keranjang kosong!',
                        text: 'Silakan tambahkan buku ke keranjang.',
                        icon: 'info',
                        background: '#343a40', // Latar belakang abu-abu kehitaman
                        color: '#ffffff' // Warna teks putih
                    });
                  </script>";
        } else {
            foreach ($cart_books as $book) {
                // Periksa apakah buku sudah dipinjam
                if (!is_already_borrowed($id_user, $book['id_books'])) {
                    // Tambahkan entri ke tabel peminjaman (borrows)
                    pinjam_buku($id_user, $book['id_books']);
                } else {
                    echo "<script>
                            Swal.fire({
                                title: 'Buku sudah dipinjam sebelumnya: " . htmlspecialchars($book['title']) . "',
                                icon: 'info',
                                background: '#343a40',
                                color: '#ffffff'
                            });
                          </script>";
                }
            }
            clear_cart($id_user);
            echo "<script>
                    Swal.fire({
                        title: 'Peminjaman berhasil!',
                        icon: 'success',
                        background: '#343a40',
                        color: '#ffffff'
                    }).then(() => { window.location.href = 'buku_saya.php'; });
                  </script>";
            exit();
        }
    }
}

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
        flex-direction: column;
        transition: 0.5s;
        overflow: hidden;
        cursor: pointer;
    }

    .book-item>img {
        width: 100%;
        height: auto;
        border-radius: 20px;
        object-fit: cover;
    }

    .book-item:hover {
        transform: scale(1.1);
    }

    .modal-body {
        text-align: center;
    }
</style>

<div class="main-panel m-4 d-flex flex-direction-column">
    <h1>Keranjang Buku</h1>

    <!-- Form Pencarian -->
    <form method="GET" action="" class="mb-5">
        <input type="text" name="search" placeholder="Cari buku..." value="<?= htmlspecialchars($search); ?>" class="form-control mb-3">
        <button type="submit" class="btn btn-primary">Cari</button>
        <a href="cart.php" class="btn btn-secondary ml-2">Reset</a>
    </form>

    <?php if (count($cart_books) > 0): ?>
        <ul class="book-container">
            <?php foreach ($cart_books as $book): ?>
                <li class="book-item" data-toggle="modal" data-target="#bookModal"
                    data-title="<?= htmlspecialchars($book['title']); ?>"
                    data-author="<?= htmlspecialchars($book['author']); ?>"
                    data-synopsis="<?= htmlspecialchars($book['synopsis']); ?>"
                    data-cover="<?= htmlspecialchars($book['cover_path']); ?>"
                    data-id="<?= htmlspecialchars($book['id_books']); ?>"
                    data-cart-id="<?= htmlspecialchars($book['id_cart']); ?>">
                    <img src="uploads/<?= htmlspecialchars($book['cover_path']); ?>" alt="<?= htmlspecialchars($book['title']); ?>">
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

<!-- Modal -->
<div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookModalLabel">Detail Buku</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="modal-img" src="" alt="Gambar Buku" class="img-fluid">
                <h3 id="modal-title"></h3>
                <p id="modal-author"></p>
                <p id="modal-synopsis"></p>
                <form id="return-form" method="GET" action="hapus_cart.php">
                    <input type="hidden" name="id_cart" id="modal-cart-id" value="">
                    <button type="submit" class="btn btn-danger">Kembalikan Buku</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Menampilkan detail buku di modal saat buku diklik
    const bookItems = document.querySelectorAll('.book-item');

    bookItems.forEach(item => {
        item.addEventListener('click', () => {
            document.getElementById('modal-img').src = 'uploads/' + item.getAttribute('data-cover');
            document.getElementById('modal-title').textContent = item.getAttribute('data-title');
            document.getElementById('modal-author').textContent = "Author: " + item.getAttribute('data-author');
            document.getElementById('modal-synopsis').textContent = item.getAttribute('data-synopsis');
            document.getElementById('modal-cart-id').value = item.getAttribute('data-cart-id'); // Set ID cart di modal
        });
    });
</script>

<?php include_once("template/footer.php"); ?>
