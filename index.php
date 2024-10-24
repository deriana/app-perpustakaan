<?php
session_start();

// Cek apakah pengguna sudah login atau belum
if (!isset($_SESSION['id_user'])) {
    // Jika pengguna belum login akan di direct ke login.php
    header("Location: login.php");
    exit();
}

include_once("template/header.php");
require_once("koneksi.php");
require_once("function.php");

$books = query("SELECT * FROM books");

if ($books === false) {
    echo "Failed to fetch data from the database.";
    exit;
}

$id_user = $_SESSION['id_user']; // Get the logged-in user's ID

// Fetch only borrows for the logged-in user
$borrows = query("SELECT * FROM borrows WHERE id_user = '$id_user'");


$readCount = count(array_filter($borrows, fn($b) => $b['is_read'] == 1));
$unreadCount = count(array_filter($borrows, fn($b) => $b['is_read'] == 0));

$limitedBooks = array_slice($books, 0, 5);
?>

<div class="main-panel">
    <div class="main-panel">
        <div class="row">
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">
                                        <?= $readCount; ?>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box">
                                    <span class="mdi mdi-book-open icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="">Books Read</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">
                                        <?= $unreadCount; ?>
                                    </h3>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box">
                                    <span class="mdi mdi-bookmark-off icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="">Books Unread</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Reading Status</h4>
                        <canvas id="reading-status" class="transaction-chart"></canvas>
                        <div class="text-center mt-3">
                            <h6 class="mb-1">Books Read: <?= $readCount ?></h6>
                            <h6 class="mb-1">Books Unread: <?= $unreadCount ?></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-between">
                            <h4 class="card-title mb-1">Buku Hari Ini</h4>
                            <p class="text-muted mb-1"></p>
                        </div>
                        <div class="col-12">
                            <div class="preview-list">
                                <?php foreach ($limitedBooks as $book): ?>
                                    <div class="preview-item border-bottom">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon">
                                                <img src="uploads/<?= htmlspecialchars($book['cover_path']) ?>" alt="Book Cover" class="img-fluid" style="width: 50px; height: auto;">
                                            </div>
                                        </div>
                                        <div class="preview-item-content d-sm-flex flex-grow">
                                            <div class="flex-grow">
                                                <h6 class="preview-subject"><?= htmlspecialchars($book['title']) ?></h6>
                                                <h6 class="preview-subject"><?= htmlspecialchars($book['author']) ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Indeks Literasi Negara</h4>

                        <!-- Filter Dropdown -->
                        <div class="form-group">
                            <label for="filterLiteracy">Tampilkan Indeks:</label>
                            <select id="filterLiteracy" class="form-control" onchange="filterLiteracy()">
                                <option value="high">Tinggi</option>
                                <option value="low">Rendah</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-5">
                                <div class="table-responsive">
                                    <table id="literacyTable" class="table">
                                        <thead>
                                            <tr>
                                                <th>Negara</th>
                                                <th>Indeks Literasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div id="audience-map" class="vector-map"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const ctx = document.getElementById('reading-status').getContext('2d');
    const readingStatusChart = new Chart(ctx, {
        type: 'pie', // You can change this to 'doughnut' for a doughnut chart
        data: {
            labels: ['Books Read', 'Books Unread'],
            datasets: [{
                label: 'Reading Status',
                data: [<?= $readCount ?>, <?= $unreadCount ?>],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.6)', // Green for read books
                    'rgba(220, 53, 69, 0.6)' // Red for unread books
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)', // Green border for read books
                    'rgba(220, 53, 69, 1)' // Red border for unread books
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Books Reading Status'
                }
            }
        }
    });

    // Data indeks literasi
    const literacyData = [
        // Data literasi tinggi
        {
            country: 'Finlandia',
            value: 99.0,
            flag: 'fi'
        },
        {
            country: 'Jepang',
            value: 99.2,
            flag: 'jp'
        },
        {
            country: 'Norwegia',
            value: 98.8,
            flag: 'no'
        },
        {
            country: 'Korea Selatan',
            value: 97.9,
            flag: 'kr'
        },
        {
            country: 'Jerman',
            value: 99.0,
            flag: 'de'
        },
        {
            country: 'Amerika Serikat',
            value: 99.0,
            flag: 'us'
        },
        {
            country: 'Australia',
            value: 99.0,
            flag: 'au'
        },

        // Data literasi rendah
        {
            country: 'Afghanistan',
            value: 37.0,
            flag: 'af'
        },
        {
            country: 'Chad',
            value: 36.0,
            flag: 'td'
        },
        {
            country: 'Niger',
            value: 19.0,
            flag: 'ne'
        },
        {
            country: 'Sudan Selatan',
            value: 27.0,
            flag: 'ss'
        },
        {
            country: 'Guinea',
            value: 30.0,
            flag: 'gn'
        },
        {
            country: 'Mali',
            value: 33.0,
            flag: 'ml'
        },
        {
            country: 'Burkina Faso',
            value: 36.0,
            flag: 'bf'
        },
    ];

    // Fungsi untuk memfilter dan menampilkan tabel berdasarkan pilihan pengguna
    function filterLiteracy() {
        const filterValue = document.getElementById('filterLiteracy').value;
        const tableBody = document.querySelector('#literacyTable tbody');
        tableBody.innerHTML = ''; // Hapus semua baris dari tabel

        // Filter data berdasarkan pilihan
        const filteredData = literacyData.filter(data => {
            return (filterValue === 'high' && data.value >= 90) || (filterValue === 'low' && data.value < 90);
        });

        // Mengurutkan data berdasarkan pilihan
        filteredData.sort((a, b) => {
            return filterValue === 'high' ? b.value - a.value : a.value - b.value;
        });

        // Tambahkan baris baru ke tabel
        filteredData.forEach(data => {
            const newRow = tableBody.insertRow();
            const cell1 = newRow.insertCell(0);
            cell1.innerHTML = `<i class="flag-icon flag-icon-${data.flag}"></i> ${data.country}`;
            const cell2 = newRow.insertCell(1);
            cell2.className = 'text-right';
            cell2.innerText = `${data.value}%`;
        });
    }

    // Memanggil fungsi untuk menampilkan data saat halaman dimuat
    document.addEventListener('DOMContentLoaded', filterLiteracy);
</script>
<?php include_once("template/footer.php");
?>