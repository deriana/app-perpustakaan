<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] == 'users') {
    header("Location: index.php");
    exit();
}

include_once("template/header.php");
require_once("koneksi.php");
require 'vendor/autoload.php';

$id_user = $_SESSION['id_user'];

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$bookItems = []; // Menyimpan item buku yang akan ditampilkan

if (isset($_GET['search'])) {
    $searchTerm = urlencode($_GET['search']);

    $apiKey = getenv('GOOGLE_BOOKS_API_KEY');
    $url = "https://www.googleapis.com/books/v1/volumes?q=" . $searchTerm . "&key=" . $apiKey;

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        echo "Error: " . curl_errno($curl);
    } else {
        $data = json_decode($response, true);

        if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $volumeInfo = $item['volumeInfo'];
                $coverPath = $volumeInfo['imageLinks']['thumbnail'] ?? '';
                $idBooks = uniqid();

                $bookItems[] = [
                    'id' => $idBooks,
                    'title' => htmlspecialchars($volumeInfo['title'] ?? 'Judul Tidak Tersedia'),
                    'author' => htmlspecialchars(implode(', ', $volumeInfo['authors'] ?? ['Penulis Tidak Diketahui'])),
                    'book_date' => htmlspecialchars($volumeInfo['publishedDate'] ?? 'Tanggal Tidak Tersedia'),
                    'synopsis' => htmlspecialchars($volumeInfo['description'] ?? 'Sinopsis Tidak Tersedia'),
                    'cover_path' => htmlspecialchars($coverPath),
                ];
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    // Atur book_date menjadi tanggal saat ini
    $bookDate = date('Y-m-d'); // Format sesuai kebutuhan Anda

    $stmt = $koneksi->prepare("INSERT INTO books(title, author, synopsis, cover_path, book_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $_POST['title'], $_POST['author'], $_POST['synopsis'], $_POST['cover_path'], $bookDate);

    if ($stmt->execute()) {
        echo "<script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Buku Berhasil Ditambahkan Ke Database',
            icon: 'success',
            background: '#343a40',
            color: '#ffffff'
        });
        </script>";
    } else {
        echo "<script>
        Swal.fire({
            title: 'Gagal!',
            text: 'Buku Gagal Ditambahkan Ke Database',
            icon: 'error',
            background: '#343a40',
            color: '#ffffff'
        });
        </script>";
    }

    $stmt->close();
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
<div class="main-panel m-4">
    <h1>Buku Dari API</h1>

    <div class="search-container mb-4">
        <form action="" method="GET" class="d-flex align-items-center">
            <input type="text" name="search" id="search" placeholder="Cari berdasarkan judul atau penulis" class="form-control mr-2">
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="list_buku_api.php" class="btn btn-secondary ml-2">Reset</a>
        </form>
    </div>

    <div class="book-container">
        <?php
        foreach ($bookItems as $item):
        ?>
            <div class="book-item"
                data-book-id="<?= $item['id']; ?>"
                data-book-title="<?= $item['title']; ?>"
                data-author="<?= $item['author']; ?>"
                data-date="<?= $item['book_date']; ?>"
                data-synopsis="<?= $item['synopsis']; ?>"
                data-cover-path="<?= $item['cover_path']; ?>">
                <img src="<?= $item['cover_path']; ?>" alt="<?= $item['title']; ?>">
            </div>
        <?php endforeach; ?>
    </div>

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
                        <form action="" method="POST">
                            <input type="hidden" name="title" value="<?= $item['title'] ?? '' ?>">
                            <input type="hidden" name="author" value="<?= $item['author'] ?? '' ?>">
                            <input type="hidden" name="synopsis" value="<?= $item['synopsis'] ?? '' ?>">
                            <input type="hidden" name="cover_path" value="<?= $item['cover_path'] ?? '' ?>">
                            <!-- Hapus baris ini -->
                            <!-- <input type="hidden" name="book_date" value="<?= $item['book_date'] ?? '' ?>"> -->
                            <button type="submit" class="btn btn-primary btn-sm mb-2" name="save_book">Simpan Ke Database</button>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bookItems = document.querySelectorAll('.book-item');
        const modalTitle = document.getElementById('modalBookTitle');
        const modalAuthor = document.getElementById('modalAuthor');
        const modalDate = document.getElementById('modalDate');
        const modalSynopsis = document.getElementById('modalSynopsis');
        const modalImg = document.getElementById('modalBookCover');

        bookItems.forEach(item => {
            item.addEventListener('click', function() {
                const title = item.getAttribute('data-book-title');
                const author = item.getAttribute('data-author');
                const date = item.getAttribute('data-date');
                const synopsis = item.getAttribute('data-synopsis');
                const coverPath = item.getAttribute('data-cover-path');

                modalTitle.textContent = title;
                modalAuthor.textContent = author;
                modalDate.textContent = date;
                modalSynopsis.textContent = synopsis;
                modalImg.src = coverPath;

                // Isi input hidden untuk form
                document.querySelector('input[name="title"]').value = title;
                document.querySelector('input[name="author"]').value = author;
                document.querySelector('input[name="synopsis"]').value = synopsis;
                document.querySelector('input[name="cover_path"]').value = coverPath;

                $('#bookModal').modal('show');
            });
        });
    });
</script>
<?php
include_once("template/footer.php");
?>
