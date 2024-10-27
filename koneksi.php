<?php
require 'vendor/autoload.php'; 

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

define("HOSTNAME", $_ENV['DB_HOST']);
define("USERNAME", $_ENV['DB_USER']);
define("PASSWORD", $_ENV['DB_PASS']);
define("DB_NAME", $_ENV['DB_NAME']);

$koneksi = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DB_NAME);