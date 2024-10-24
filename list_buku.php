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
    <h1>Perpustakaan Digital</h1>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <?php if($_SESSION['role'] == 'admin') :?>
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <h2 class="card-title mr-5">List Buku</h2>
                        <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#tambahBuku">Tambah Buku</button>
                    </div>
                <?php endif; ?>
                <div class="book-container">
                    <?php
                    $table_buku = query("SELECT * FROM books");
                    foreach ($table_buku as $buku):
                        // Cek apakah buku sudah dipinjam oleh user ini
                        $borrowed = query("SELECT * FROM borrows WHERE id_user = '$id_user' AND id_books = '$buku[id_books]' AND status = 'borrowed'");
                    ?>
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
                                    <h3>Date: <?= htmlspecialchars($buku['book_date']) ?></h3>
                                </div>
                                <p><?= htmlspecialchars($buku['synopsis']) ?></p>
                                
                                    <?php if (empty($borrowed) && !is_already_in_cart($id_user, $buku['id_books'])): ?>
                                        <a href="tambah_cart.php?id_books=<?= htmlspecialchars($buku['id_books']); ?>" class="btn btn-success btn-sm">Tambah ke Keranjang</a>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Sudah Di Keranjang</span>

                                    <?php if (empty($borrowed) && !is_already_in_cart($id_user, $buku['id_books'])): ?>
                                        <a href="pinjam.php?id_books=<?= htmlspecialchars($buku['id_books']); ?>" class="btn btn-primary">Pinjam Buku</a>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Sudah Di Pinjam Atau Di Keranjang</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                <?php if($_SESSION['role'] == 'admin') :?>
                                    <div>
                                        <button class="btn btn-success btn-sm"
                                            data-toggle="modal"
                                            data-target="#editBuku"
                                            data-id="<?= htmlspecialchars($buku['id_books']); ?>"
                                            data-title="<?= htmlspecialchars($buku['title']); ?>"
                                            data-author="<?= htmlspecialchars($buku['author']); ?>"
                                            data-synopsis="<?= htmlspecialchars($buku['synopsis']); ?>">
                                            Edit Buku
                                        </button>
                                        <a onclick="return confirm('Apakah anda yakin ingin menghapus data ini ?')" class="btn btn-sm btn-danger" href="hapus_buku.php?id_books=<?= htmlspecialchars($buku['id_books']); ?>">Hapus</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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