<?php
// Memulai session
session_start();
require 'koneksi.php';
require 'vendor/autoload.php'; // Pastikan Anda sudah mengautoload library PhpSpreadsheet

// Dapatkan tanggal awal dan tanggal akhir dari parameter GET
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : date('Y-m-d');
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : date('Y-m-d');

// Query untuk mendapatkan data tamu sesuai periode
$sql_buku_tamu = "
    SELECT * 
    FROM books 
    WHERE book_date BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
    ORDER BY book_date DESC
";
$result = mysqli_query($koneksi, $sql_buku_tamu);

// Buat objek spreadsheet
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header kolom
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Title');
$sheet->setCellValue('C1', 'Author');
$sheet->setCellValue('D1', 'Synopsis');
$sheet->setCellValue('E1', 'Date');

// Mengisi data ke dalam spreadsheet
$rowNumber = 2; // Mulai dari baris kedua
$no = 1; // Nomor urut tamu
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $rowNumber, $no++);
    $sheet->setCellValue('C' . $rowNumber, $row['title']);
    $sheet->setCellValue('D' . $rowNumber, $row['author']);
    $sheet->setCellValue('E' . $rowNumber, $row['synopsis']);
    $sheet->setCellValue('E' . $rowNumber, date('Y-m-d', strtotime($row['book_date'])));
;
    $rowNumber++;
}

// Set header untuk download file Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="laporan_buku.xlsx"');

// Buat writer dan simpan file ke output
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save('php://output');
exit();
?>
