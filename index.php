<?php
session_start();

if (!isset($_SESSION['id_user'])) {
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

$id_user = $_SESSION['id_user'];

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
                <div class="card d-flex">
                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-between">
                            <h4 class="card-title mb-1">Buku</h4>
                            <div>
                                <button id="btnToday" class="btn btn-primary" onclick="showToday()">Hari Ini</button>
                                <button id="btnFavorite" class="btn btn-secondary" onclick="showFavorite()">Favorit</button>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="preview-list" id="bookList">
                                <!-- Books for Today -->
                                <div id="todayBooks">
                                    <?php
                                    $books = query("SELECT * FROM books ORDER BY RAND() LIMIT 5");
                                    foreach ($books as $book): ?>
                                        <div class="preview-item border-bottom">
                                            <div class="preview-thumbnail">
                                                <div class="preview-icon">
                                                    <?php
                                                    $coverPath = "uploads/" . htmlspecialchars($book['cover_path']);
                                                    if (file_exists($coverPath) && !empty($book['cover_path'])) {
                                                        echo '<img src="' . $coverPath . '" alt="Book Cover" class="img-fluid" style="width: 50px; height: auto;">';
                                                    } else {
                                                        echo "<img src='" . htmlspecialchars($book['cover_path']) . "' alt='" . htmlspecialchars($book['title']) . "'>";
                                                    }
                                                    ?>
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

                                <!-- Books for Favorite -->
                                <div id="favoriteBooks" style="display: none;">
                                    <?php
                                    $favorite = query("
                            SELECT books.title, books.author, books.cover_path, COUNT(borrows.id_books) AS favorite_count 
                            FROM borrows
                            JOIN books ON books.id_books = borrows.id_books
                            WHERE borrows.is_favorite = true
                            GROUP BY borrows.id_books
                            ORDER BY favorite_count DESC 
                            LIMIT 5
                        ");
                                    foreach ($favorite as $book): ?>
                                        <div class="preview-item border-bottom">
                                            <div class="preview-thumbnail">
                                                <div class="preview-icon">
                                                    <?php
                                                    $coverPath = isset($book['cover_path']) && file_exists("uploads/" . $book['cover_path'])
                                                        ? "uploads/" . htmlspecialchars($book['cover_path'])
                                                        : htmlspecialchars($book['cover_path']);

                                                    echo '<img src="' . $coverPath . '" alt="Book Cover" class="img-fluid" style="width: 50px; height: auto;">';
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="preview-item-content d-sm-flex flex-grow">
                                                <div class="flex-grow">
                                                    <h6 class="preview-subject"><?= htmlspecialchars($book['title'] ?? 'Judul Tidak Diketahui') ?></h6>
                                                    <p class="text-muted"><?= htmlspecialchars($book['author'] ?? 'Penulis Tidak Diketahui') ?></p>
                                                    <p class="text-muted">Favorited: <?= htmlspecialchars($book['favorite_count'] ?? '0') ?> times</p>
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
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Indeks Literasi Negara</h4>

                        <!-- Search Input -->
                        <div class="form-group">
                            <label for="searchLiteracy">Search Country:</label>
                            <input type="text" id="searchLiteracy" class="form-control" placeholder="Search by country..." oninput="filterTable()">
                        </div>

                        <!-- Filter Dropdown -->
                        <div class="form-group">
                            <label for="filterLiteracy">Tampilkan Indeks:</label>
                            <select id="filterLiteracy" class="form-control" onchange="filterLiteracy()">
                                <option value="high">Tinggi</option>
                                <option value="low">Rendah</option>
                            </select>
                        </div>

                        <!-- Pagination Controls -->
                        <div class="pagination mb-3">
                            <button id="prevBtn" class="btn btn-secondary mr-2" onclick="changePage(-1)">Previous</button>
                            <button id="nextBtn" class="btn btn-secondary" onclick="changePage(1)">Next</button>
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
        type: 'pie',
        data: {
            labels: ['Books Read', 'Books Unread'],
            datasets: [{
                label: 'Reading Status',
                data: [<?= $readCount ?>, <?= $unreadCount ?>],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.6)',
                    'rgba(220, 53, 69, 0.6)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(220, 53, 69, 1)'
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
    const literacyData = [{
            country: 'Finlandia',
            value: 100.0,
            flag: 'fi'
        },
        {
            country: 'Denmark',
            value: 100.0,
            flag: 'dk'
        },
        {
            country: 'Norwegia',
            value: 100.0,
            flag: 'no'
        },
        {
            country: 'Swedia',
            value: 100.0,
            flag: 'se'
        },
        {
            country: 'Jepang',
            value: 99.0,
            flag: 'jp'
        },
        {
            country: 'Korea Selatan',
            value: 99.0,
            flag: 'kr'
        },
        {
            country: 'Jerman',
            value: 99.0,
            flag: 'de'
        },
        {
            country: 'Kanada',
            value: 99.0,
            flag: 'ca'
        },
        {
            country: 'Amerika Serikat',
            value: 99.0,
            flag: 'us'
        },
        {
            country: 'Prancis',
            value: 99.0,
            flag: 'fr'
        },
        {
            country: 'Belanda',
            value: 99.0,
            flag: 'nl'
        },
        {
            country: 'Austria',
            value: 98.0,
            flag: 'at'
        },
        {
            country: 'Australia',
            value: 99.0,
            flag: 'au'
        },
        {
            country: 'Singapura',
            value: 97.0,
            flag: 'sg'
        },
        {
            country: 'Malaysia',
            value: 94.0,
            flag: 'my'
        },
        {
            country: 'Thailand',
            value: 93.0,
            flag: 'th'
        },
        {
            country: 'Chile',
            value: 97.0,
            flag: 'cl'
        },
        {
            country: 'Kolombia',
            value: 93.0,
            flag: 'co'
        },
        {
            country: 'Meksiko',
            value: 93.0,
            flag: 'mx'
        },
        {
            country: 'Turki',
            value: 95.0,
            flag: 'tr'
        },
        {
            country: 'Brazil',
            value: 92.0,
            flag: 'br'
        },
        {
            country: 'Argentina',
            value: 93.0,
            flag: 'ar'
        },
        {
            country: 'Ekuador',
            value: 93.0,
            flag: 'ec'
        },
        {
            country: 'Indonesia',
            value: 95.0,
            flag: 'id'
        },
        {
            country: 'Filipina',
            value: 96.0,
            flag: 'ph'
        },
        {
            country: 'Vietnam',
            value: 94.0,
            flag: 'vn'
        },
        {
            country: 'India',
            value: 74.0,
            flag: 'in'
        },
        {
            country: 'Bangladesh',
            value: 73.0,
            flag: 'bd'
        },
        {
            country: 'Pakistan',
            value: 60.0,
            flag: 'pk'
        },
        {
            country: 'Yaman',
            value: 56.0,
            flag: 'ye'
        },
        {
            country: 'Afganistan',
            value: 38.0,
            flag: 'af'
        },
        {
            country: 'Somalia',
            value: 37.0,
            flag: 'so'
        },
    ];

    const itemsPerPage = 10; // Items to display per page
    let currentPage = 1; // Track current page
    let filteredData = literacyData; // To store filtered data

    function renderTable() {
        const tableBody = document.querySelector('#literacyTable tbody');
        tableBody.innerHTML = ''; // Clear existing rows

        // Get start and end indices for pagination
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;

        // Slice the filtered data for the current page
        const currentData = filteredData.slice(startIndex, endIndex);

        // Populate the table with current data
        currentData.forEach(data => {
            const newRow = tableBody.insertRow();
            const cell1 = newRow.insertCell(0);
            cell1.innerHTML = `<i class="flag-icon flag-icon-${data.flag}"></i> ${data.country}`;
            const cell2 = newRow.insertCell(1);
            cell2.className = 'text-right';
            cell2.innerText = `${data.value}%`;
        });

        updatePagination(filteredData.length);
    }

    function filterTable() {
        const searchInput = document.getElementById('searchLiteracy').value.toLowerCase();

        // Filter the literacy data based on the search input
        filteredData = literacyData.filter(item => item.country.toLowerCase().includes(searchInput));

        // Reset currentPage for filtered results
        currentPage = 1;
        renderTable(); // Render the filtered table
    }

    function filterLiteracy() {
        const filterValue = document.getElementById('filterLiteracy').value;

        // Reset currentPage for filtered results
        currentPage = 1;

        if (filterValue === "high") {
            filteredData = literacyData.filter(item => item.value >= 90); // Adjust threshold for "high"
        } else if (filterValue === "low") {
            filteredData = literacyData.filter(item => item.value < 90); // Adjust threshold for "low"
        } else {
            filteredData = literacyData; // Reset to all data
        }

        renderTable(); // Render the filtered table
    }

    function changePage(direction) {
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        currentPage += direction;

        // Ensure currentPage stays within bounds
        if (currentPage < 1) currentPage = 1;
        if (currentPage > totalPages) currentPage = totalPages;

        renderTable(); // Render the table for the current page
    }

    function updatePagination(totalItems) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        document.getElementById("prevBtn").style.display = currentPage === 1 ? 'none' : 'inline';
        document.getElementById("nextBtn").style.display = currentPage === totalPages ? 'none' : 'inline';
    }

    // Call renderTable on page load
    document.addEventListener('DOMContentLoaded', () => {
        renderTable(); // Initial render
    });


    // JavaScript functions to show/hide book lists
    function showToday() {
        document.getElementById('todayBooks').style.display = 'block';
        document.getElementById('favoriteBooks').style.display = 'none';
        document.getElementById('btnToday').classList.add('btn-primary');
        document.getElementById('btnToday').classList.remove('btn-secondary');
        document.getElementById('btnFavorite').classList.remove('btn-primary');
        document.getElementById('btnFavorite').classList.add('btn-secondary');
    }

    function showFavorite() {
        document.getElementById('todayBooks').style.display = 'none';
        document.getElementById('favoriteBooks').style.display = 'block';
        document.getElementById('btnFavorite').classList.add('btn-primary');
        document.getElementById('btnFavorite').classList.remove('btn-secondary');
        document.getElementById('btnToday').classList.remove('btn-primary');
        document.getElementById('btnToday').classList.add('btn-secondary');
    }

    // Initialize to show "Buku Hari Ini" on page load
    document.addEventListener('DOMContentLoaded', showToday);
</script>


<?php include_once("template/footer.php"); ?>