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

    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
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

function hapus_buku($id) {
    global $koneksi;
    $query = "DELETE FROM books WHERE id_books = '$id'";
    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

function pinjam_buku($id_user, $id_books)
{
    global $koneksi;

    $query_cek = "SELECT * FROM borrows WHERE id_user = '$id_user' AND id_books = '$id_books' AND status ='borrowed'";
    $cek = mysqli_query($koneksi, $query_cek);

    if(mysqli_num_rows($cek) > 0) {
        return false;
    }

    $query = "INSERT INTO borrows (id_user, id_books, borrow_date, status) VALUES ('$id_user', '$id_books', NOW(), 'borrowed')";
    return mysqli_query($koneksi, $query);

}

function kembalikan_buku($id_user, $id_books) {
    global $koneksi;

    $query = "UPDATE borrows
                SET status = 'returned', return_date = NOW()
                WHERE id_user = '$id_user' AND '$id_books' AND status = 'borrowed'";
    
    return mysqli_query($koneksi, $query);
}