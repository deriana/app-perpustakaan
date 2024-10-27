<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['id_user'])) {
    // Jika pengguna belum login akan di direct ke login.php
    header("Location: login.php");
    exit();
}

include_once("template/header.php");
require_once("function.php");

$id_user = $_SESSION['id_user']; // Ambil ID user dari session
$role = $_SESSION['role'];

// Check if form is submitted

$judul = isset($buku['judul']) ? $buku['judul'] : 'Judul Tidak Tersedia';
$penulis = isset($buku['penulis']) ? $buku['penulis'] : 'Penulis Tidak Diketahui';
$tanggal = isset($buku['tanggal']) ? $buku['tanggal'] : 'Tanggal Tidak Tersedia';


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

    if (isset($_POST['add_to_cart'])) {
        $id_books = $_POST['id_books'];

        // Cek apakah buku sudah dipinjam
        if (is_already_borrowed($id_user, $id_books)) {
            echo "<script>alert('Anda sudah meminjam buku ini!');</script>";
        } elseif (is_already_in_cart($id_user, $id_books)) {
            // Cek apakah buku sudah ada di keranjang
            echo "<script>alert('Buku ini sudah ada di keranjang!');</script>";
        } else {
            if (add_to_cart($id_user, $id_books)) {
                echo "<script>
                        alert('Buku berhasil ditambahkan ke keranjang!');
                        document.location.href = 'cart.php'; // Redirect ke halaman keranjang
                    </script>";
            } else {
                echo "<script>
                        alert('Gagal menambahkan buku ke keranjang. Coba lagi.');
                    </script>";
            }
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
        background: white;
        border-radius: 20px;
        display: flex;
        flex-direction: row;
        overflow: hidden;
        cursor: pointer;
        transition: 0.5s;
    }

    .book-item>img {
        width: 250px;
        height: 350px;
        border-radius: 20px;
        object-fit: cover;
    }

    .book-item:hover {
        transform: scale(1.1);
        background-color: transparent;
        box-shadow: none;
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
    <h1>Perpustakaan Digital</h1>

    <!-- Search Bar -->
    <div class="search-container mb-4">
        <form action="" method="get" class="d-flex align-items-center">
            <input type="text" name="search" placeholder="Cari berdasarkan judul atau penulis" class="form-control mr-2">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="list_buku.php" class="btn btn-secondary ml-2">Reset</a>
        </form>
    </div>


    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <?php if ($_SESSION['role'] == 'admin') : ?>
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <h2 class="card-title mr-5">List Buku</h2>
                        <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#tambahBuku">Tambah Buku</button>
                    </div>
                <?php endif; ?>
                <div class="book-container">
                    <?php
                    $search_query = "";
                    if (isset($_GET['search'])) {
                        $search = htmlspecialchars($_GET['search']);
                        $search_query = "WHERE title LIKE '%$search%' OR author LIKE '%$search%'";
                    }
                    $table_buku = query("SELECT * FROM books $search_query");

                    foreach ($table_buku as $buku):
                        // Cek apakah buku sudah dipinjam oleh user ini
                        $borrowed = query("SELECT * FROM borrows WHERE id_user = '$id_user' AND id_books = '$buku[id_books]' AND status = 'borrowed'");
                    ?>
                        <!-- Daftar Buku -->
                        <div class="book-item"
                            data-book-id="<?= isset($buku['id_books']) ? htmlspecialchars($buku['id_books']) : ''; ?>"
                            data-book-title="<?= isset($buku['title']) ? htmlspecialchars($buku['title']) : 'Judul Tidak Tersedia'; ?>"
                            data-author="<?= isset($buku['author']) ? htmlspecialchars($buku['author']) : 'Penulis Tidak Diketahui'; ?>"
                            data-date="<?= isset($buku['book_date']) ? htmlspecialchars($buku['book_date']) : 'Tanggal Tidak Tersedia'; ?>"
                            data-synopsis="<?= isset($buku['synopsis']) ? htmlspecialchars($buku['synopsis']) : 'Sinopsis Tidak Tersedia'; ?>"
                            data-cover-path="uploads/<?= isset($buku['cover_path']) ? htmlspecialchars($buku['cover_path']) : 'default.jpg'; ?>"
                            data-borrowed="<?= isset($borrowed) && empty($borrowed) ? 'false' : 'true'; ?>"
                            data-in-cart="<?= isset($id_user) && is_already_in_cart($id_user, $buku['id_books']) ? 'true' : 'false'; ?>">

                            <img src="uploads/<?= isset($buku['cover_path']) ? htmlspecialchars($buku['cover_path']) : 'default.jpg'; ?>" alt="sampul buku" style="max-width: 100%; height: auto;">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DETAIL BUKU-->
<div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookModalLabel">Detail Buku</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="modalBookCover" src="" alt="Sampul Buku" style="width:100%; height:auto; margin-bottom: 15px;">
                <h3 id="modalBookTitle"></h3>
                <p><strong>Penulis:</strong> <span id="modalAuthor"></span></p>
                <p><strong>Tanggal:</strong> <span id="modalDate"></span></p>
                <p><strong>Sinopsis:</strong></p>
                <p id="modalSynopsis"></p>

                <div id="modalButtons" class="mt-4">
                    <a href="#" class="btn btn-success btn-sm mb-2 add-to-cart" data-id="<?= htmlspecialchars($buku['id_books']); ?>" id="modalAddToCart">Tambah ke Keranjang</a>
                    <a href="#" class="btn btn-primary btn-sm mb-2 add-to-borrow" data-id="<?= htmlspecialchars($buku['id_books']); ?>" id="modalBorrowBook">Pinjam Buku</a>
                    <span id="modalBookStatus" class="badge badge-warning" style="display: none;">Sudah Di Keranjang atau
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" id="modalEditBook"
                    data-toggle="modal"
                    data-target="#editBuku"
                    data-id="<?= htmlspecialchars($buku['id_books']); ?>"
                    data-title="<?= htmlspecialchars($buku['title']); ?>"
                    data-author="<?= htmlspecialchars($buku['author']); ?>"
                    data-synopsis="<?= htmlspecialchars($buku['synopsis']); ?>">
                    Edit Buku
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="modalDeleteBook"
                    onclick="deleteBook('<?= htmlspecialchars($buku['id_books']); ?>')">
                    Hapus Buku
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>



<!-- MODAL TAMBAH BUKU -->
<div class="modal fade" id="tambahBuku" tabindex="-1" aria-labelledby="tambahBukuLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahBukuLabel">Tambah Data Buku</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group row">
                        <label for="title" class="col-sm-3 col-form-label">Judul</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="author" class="col-sm-3 col-form-label">Author</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="author" name="author" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="synopsis" class="col-sm-3 col-form-label">Synopsis</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="synopsis" name="synopsis" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="foto" class="col-sm-3 col-form-label">Upload Sampul Foto</label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*" required>
                            <small class="form-text text-muted">Foto harus diunggah sebelum menyimpan.</small>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- Modal for Editing Book -->
<div class="modal fade" id="editBuku" tabindex="-1" aria-labelledby="editBukuLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBukuLabel">Edit Data Buku</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id_books" id="id_books">
                    <div class="form-group row">
                        <label for="title" class="col-sm-3 col-form-label">Judul</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="author" class="col-sm-3 col-form-label">Author</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="edit_author" name="author" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="synopsis" class="col-sm-3 col-form-label">Synopsis</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="edit_synopsis" name="synopsis" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="foto" class="col-sm-3 col-form-label">Upload Sampul Foto Baru (Opsional)</label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*">
                            <small class="form-text text-muted">Foto baru akan menggantikan foto lama jika diunggah.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                        <button type="submit" class="btn btn-primary" name="edit">Simpan Perubahan</button> <!-- Pastikan name="edit" ada -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".book-item").forEach(function(item) {
        item.addEventListener("click", function() {
            // Ambil data buku
            const bookTitle = this.getAttribute("data-book-title");
            const author = this.getAttribute("data-author");
            const date = this.getAttribute("data-date");
            const synopsis = this.getAttribute("data-synopsis");
            const coverPath = this.getAttribute("data-cover-path");
            const borrowed = this.getAttribute("data-borrowed") === 'true';
            const inCart = this.getAttribute("data-in-cart") === 'true';
            const id = this.getAttribute("data-book-id");

            // Mengatur detail modal buku
            document.getElementById("modalBookTitle").innerText = bookTitle;
            document.getElementById("modalAuthor").innerText = author;
            document.getElementById("modalDate").innerText = date;
            document.getElementById("modalSynopsis").innerText = synopsis;
            document.getElementById("modalBookCover").src = coverPath;

            // Set visibility based on role
            if ('<?= $role ?>' === 'admin') {
                document.getElementById("modalAddToCart").style.display = "none";
                document.getElementById("modalBorrowBook").style.display = "none";
                document.getElementById("modalEditBook").style.display = "inline-block";
                document.getElementById("modalDeleteBook").style.display = "inline-block";
            } else {
                document.getElementById("modalEditBook").style.display = "none";
                document.getElementById("modalDeleteBook").style.display = "none";
            }

            // Set tombol hapus
            document.getElementById('modalDeleteBook').setAttribute('onclick', `deleteBook(${id})`);

            // Set ID untuk tombol "Tambah ke Keranjang"
            const addToCartButton = document.querySelector('.add-to-cart');
            addToCartButton.setAttribute('data-id', id); // Mengatur ID yang tepat

            // Set ID untuk tombol "Pinjam Buku"
            const borrowButton = document.querySelector('.add-to-borrow');
            borrowButton.setAttribute('data-id', id); // Mengatur ID yang tepat

            // Tampilkan modal
            $('#bookModal').modal('show');
        });
    });

    // Event listener untuk tombol "Tambah ke Keranjang"
    document.querySelector('.add-to-cart').addEventListener('click', function(event) {
        event.preventDefault(); 
        const bookId = this.getAttribute('data-id'); // Mengambil ID dari tombol
        window.location.href = `tambah_cart.php?id_books=${bookId}`; // Mengarahkan ke halaman
    });

    // Event listener untuk tombol "Pinjam Buku"
    document.querySelector('.add-to-borrow').addEventListener('click', function(event) {
        event.preventDefault();
        const bookId = this.getAttribute('data-id'); // Mengambil ID dari tombol
        window.location.href = `pinjam.php?id_books=${bookId}`; // Mengarahkan ke halaman peminjaman
    });
});

    function deleteBook(id) {
        if (confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
            window.location.href = `hapus_buku.php?id_books=${id}`;
        }
    }

    function setEditBarang(id, title, author, synopsis) {
        document.getElementById('id_books').value = id;
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_author').value = author;
        document.getElementById('edit_synopsis').value = synopsis;
    }

    document.querySelectorAll('.btn-success').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const author = this.getAttribute('data-author');
            const synopsis = this.getAttribute('data-synopsis');

            setEditBarang(id, title, author, synopsis);
        });
    });
</script>

<?php include_once("template/footer.php");
?>