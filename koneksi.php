<?php
// Membuat koneksi databaase
define("HOSTNAME", "localhost");
define("USERNAME", "root");
define("PASSWORD", "root");
define("DB_NAME", "db_perpustakaan");

$koneksi = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DB_NAME);