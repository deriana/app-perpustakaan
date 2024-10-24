<?php
require_once("koneksi.php");

function register($data)
{
    global $koneksi;

    // Mengamankan input dari user
    $username = htmlspecialchars($data['user_nama']);
    $password = htmlspecialchars($data['user_pass']);

    // Meng-hash password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Query untuk menyimpan user baru dengan role default sebagai 'users'
    $query = "INSERT INTO users (user_name, user_password, role) VALUES (?, ?, 'users')";

    // Menggunakan prepared statement untuk menghindari SQL Injection
    $stmt = mysqli_prepare($koneksi, $query);

    // Mengikat parameter dan menjalankan query
    mysqli_stmt_bind_param($stmt, "ss", $username, $password_hash);
    mysqli_stmt_execute($stmt);

    // Mengembalikan jumlah baris yang terpengaruh (berhasil/tidak)
    return mysqli_stmt_affected_rows($stmt);
}

function query($query)
{
    global $koneksi;

    // Eksekusi kueri
    $result = mysqli_query($koneksi, $query);

    // Check if the query execution was successful
    if (!$result) {
        // Output MySQL error for debugging
        echo "Query Error: " . mysqli_error($koneksi);
        return false; // Return false on failure
    }

    // Jika kueri SELECT, kita ambil hasilnya
    if (stripos($query, 'SELECT') === 0) {
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows; // Return the array of rows
    }

    // Untuk kueri INSERT, UPDATE, DELETE, kita hanya mengembalikan true/false
    return $result; // This will return true if successful, false if failed
}


function tambah_buku($data)
{
    global $koneksi;

    $judul = mysqli_real_escape_string($koneksi, htmlspecialchars($data["title"]));
    $author = mysqli_real_escape_string($koneksi, htmlspecialchars($data["author"]));
    $synopsis = mysqli_real_escape_string($koneksi, htmlspecialchars($data["synopsis"]));
    $book_date = date("Y-m-d");

    $cover_path = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $namaFile = $_FILES['foto']['name'];
        $tmpName = $_FILES['foto']['tmp_name'];
        $namaFileBaru = uniqid() . '_' . $namaFile;
        $destination = "uploads/" . $namaFileBaru;

        if (move_uploaded_file($tmpName, $destination)) {
            $cover_path = $namaFileBaru;
        }
    }

    // Insert query
    $query = "INSERT INTO books(title, author, synopsis, cover_path, book_date) 
              VALUES ('$judul', '$author', '$synopsis', '$cover_path', '$book_date')";

    // Debugging query to check for errors
    if (!mysqli_query($koneksi, $query)) {
        die("Error: " . mysqli_error($koneksi));  // Debugging tool
    }

    return mysqli_affected_rows($koneksi);
}

function edit_buku($data)
{
    global $koneksi;

    $id_books = htmlspecialchars($data["id_books"]);
    $judul = htmlspecialchars($data["title"]);
    $author = htmlspecialchars($data["author"]);
    $synopsis = htmlspecialchars($data["synopsis"]);

    $cover_path = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $namaFile = $_FILES['foto']['name'];
        $tmpName = $_FILES['foto']['tmp_name'];
        $namaFileBaru = uniqid() . '_' . $namaFile;
        $destination = "uploads/" . $namaFileBaru;

        if (move_uploaded_file($tmpName, $destination)) {
            $cover_path = $namaFileBaru;
        }
    }

    $query = "UPDATE books SET
                title = '$judul',
                author = '$author',
                synopsis = '$synopsis'";

    if ($cover_path !== '') {
        $query .= ", cover_path = '$cover_path'";
    }

    $query .= " WHERE id_books = '$id_books'";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}



function hapus_buku($id)
{
    global $koneksi;

    // Hapus semua borrows terkait buku ini
    $query_hapus_borrows = "DELETE FROM borrows WHERE id_books = '$id'";
    mysqli_query($koneksi, $query_hapus_borrows);

    // Hapus semua borrows terkait buku ini
    $query_hapus_logs = "DELETE FROM activity_logs WHERE id_books = '$id'";
    mysqli_query($koneksi, $query_hapus_logs);

    // Hapus buku
    $query_hapus_buku = "DELETE FROM books WHERE id_books = '$id'";
    mysqli_query($koneksi, $query_hapus_buku);

    return mysqli_affected_rows($koneksi);
}



function pinjam_buku($id_user, $id_books)
{
    global $koneksi;

    $query_cek = "SELECT * FROM borrows WHERE id_user = '$id_user' AND id_books = '$id_books' AND status ='borrowed'";
    $cek = mysqli_query($koneksi, $query_cek);

    if (mysqli_num_rows($cek) > 0) {
        return false;
    }

    $query = "INSERT INTO borrows (id_user, id_books, borrow_date, status) VALUES ('$id_user', '$id_books', NOW(), 'borrowed')";
    if (mysqli_query($koneksi, $query)) {
        log_activity($id_user, 'borrow', $id_books);
        return true;
    }
    return false;
}


function kembalikan_buku($id_user, $id_books)
{
    global $koneksi;

    $query = "UPDATE borrows
              SET status = 'returned', return_date = NOW() 
              WHERE id_user = '$id_user' AND id_books = '$id_books' AND status = 'borrowed'";

    if (mysqli_query($koneksi, $query)) {
        log_activity($id_user, 'return', $id_books);
        return true;
    }
    return false;
}

function is_read($id_books, $is_read)
{
    global $koneksi;
    $id_user = $_SESSION['id_user'];
    $query = "UPDATE borrows SET is_read = " . ($is_read ? 'TRUE' : 'FALSE') . " 
              WHERE id_user = '$id_user' AND id_books = '$id_books'";

    return mysqli_query($koneksi, $query);
}


function is_favorite($id_books, $is_favorite)
{
    global $koneksi;
    $id_user = $_SESSION['id_user']; // Get the logged-in user ID

    if ($is_favorite) {
        // Mark as favorite
        $query = "UPDATE borrows SET is_favorite = TRUE WHERE id_user = '$id_user' AND id_books = '$id_books'";
    } else {
        // Unmark as favorite
        $query = "UPDATE borrows SET is_favorite = FALSE WHERE id_user = '$id_user' AND id_books = '$id_books'";
    }

    return mysqli_query($koneksi, $query);
}

function add_to_cart($id_user, $id_books)
{
    global $koneksi;

    // Cek apakah buku sudah ada di keranjang
    if (is_already_in_cart($id_user, $id_books)) {
        return false; // Jika sudah ada, jangan lakukan apa-apa
    }

    $query = "INSERT INTO cart (id_user, id_books) VALUES ('$id_user', '$id_books')";
    if (!mysqli_query($koneksi, $query)) {
        die("Add to cart Error: " . mysqli_error($koneksi));
    }

    log_activity($id_user, 'add', $id_books); // Panggil log_activity hanya jika berhasil menambahkan

    return true; // Mengembalikan true jika berhasil
}


function get_cart_books($id_user)
{
    return query("SELECT * FROM cart WHERE id_user = '$id_user'");
}

function is_already_borrowed($id_user, $id_books)
{
    $result = query("SELECT * FROM borrows WHERE id_user = '$id_user' AND id_books = '$id_books' AND status = 'borrowed'");
    return count($result) > 0;
}

function remove_from_cart($id_cart)
{
    $query = "DELETE FROM cart WHERE id_cart = '$id_cart'";
    return query($query);
}

function clear_cart($id_user)
{
    $cart_items = get_cart_books($id_user);
    foreach ($cart_items as $item) {
        log_activity($id_user, 'remove', $item['id_books']);
    }

    $query = "DELETE FROM cart WHERE id_user = '$id_user'";
    return query($query);
}

function is_already_in_cart($id_user, $id_books)
{
    $result = query("SELECT * FROM cart WHERE id_user = '$id_user' AND id_books = '$id_books'");
    return count($result) > 0;
}

function log_activity($id_user, $activity_type, $id_books)
{
    global $koneksi;
    $query = "INSERT INTO activity_logs(id_user, activity_type, id_books, timestamp) VALUES ('$id_user', '$activity_type', '$id_books', NOW())";
    return mysqli_query($koneksi, $query);
}

function get_activity_logs($id_user, $activity_type = '', $start_date = '', $end_date = '')
{
    global $koneksi;

    $query = "SELECT al.*, u.user_name, b.title
              FROM activity_logs al
              JOIN users u ON al.id_user = u.id_user
              JOIN books b ON al.id_books = b.id_books
              WHERE al.id_user = '$id_user'";

    if (!empty($activity_type)) {
        $query .= "AND al.activity_type = '$activity_type'";
    }

    if (!empty($start_date) && !empty($end_date)) {
        $query .= "AND al.timestamp BETWEEN '$start_date' AND '$end_date'";
    }

    $query .= "ORDER BY al.timestamp DESC";

    return query($query);
}

function tambah_user($data)
{
    global $koneksi;

    $username = htmlspecialchars($data["user_name"]);
    $user_password = htmlspecialchars($data["user_password"]);
    $role = htmlspecialchars($data["role"]);
    $password_hash = password_hash($user_password, PASSWORD_BCRYPT);

    $pf_img = ''; // Variabel default untuk foto profil

    // Memeriksa apakah ada file yang diunggah
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $namaFile = $_FILES['foto']['name'];
        $tmpName = $_FILES['foto']['tmp_name'];

        // Membuat nama file baru agar unik
        $namaFileBaru = uniqid() . '_' . $namaFile;
        $destination = "pf_img/" . $namaFileBaru;

        // Memindahkan file yang di-upload ke direktori tujuan
        if (move_uploaded_file($tmpName, $destination)) {
            $pf_img = $namaFileBaru; // Set $pf_img dengan nama file yang telah diunggah
        } else {
            die("Error: Failed to upload image.");
        }
    }

    // Validasi nilai role
    if ($role != 'admin' && $role != 'user') {
        die("Error: Invalid role value.");
    }

    // Query untuk memasukkan data user
    $query = "INSERT INTO users (user_name, user_password, role, pf_img) 
              VALUES ('$username', '$password_hash', '$role', '$pf_img')";

    // Debugging untuk cek error pada SQL
    if (!mysqli_query($koneksi, $query)) {
        die("SQL Error: " . mysqli_error($koneksi));
    }

    return mysqli_affected_rows($koneksi);
}

function ubah_user($data)
{
    global $koneksi;

    $id_user = htmlspecialchars($data['id_user']);
    $username = htmlspecialchars($data["user_name"]);
    $role = htmlspecialchars($data["role"]);
    $pf_img = '';

    $valid_roles = ['admin', 'users'];

    if (!in_array($role, $valid_roles)) {
        die("Invalid role value.");
    }

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $namaFile = $_FILES['foto']['name'];
        $tmpName = $_FILES['foto']['tmp_name'];

        $namaFileBaru = uniqid() . '_' . $namaFile;
        $destination = "pf_img/" . $namaFileBaru;

        if (move_uploaded_file($tmpName, $destination)) {
            $pf_img = $namaFileBaru;
        } else {
            die("Error: Failed to upload image.");
        }
    }

    $query = "UPDATE users SET
                user_name = '$username',
                role = '$role'";

    if ($pf_img !== '') {
        $query .= ", pf_img = '$pf_img'";
    }

    $query .= " WHERE id_user = '$id_user'";

    if (!mysqli_query($koneksi, $query)) {
        die("Error updating user: " . mysqli_error($koneksi));
    }

    return mysqli_affected_rows($koneksi);
}

function hapus_user($id)
{
    global $koneksi;
    $query = "DELETE FROM users WHERE id_user = '$id'";
    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

function ganti_password($data)
{
    global $koneksi;

    $id_user = htmlspecialchars($data['id_user']);
    $password = htmlspecialchars($data['user_password']);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $query = "UPDATE users SET
            user_password = '$password_hash'
            WHERE id_user = '$id_user'";
    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}