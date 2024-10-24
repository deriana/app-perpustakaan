<?php
session_start();
require 'koneksi.php';
require 'vendor/autoload.php'; // Pastikan Anda sudah mengautoload library TCPDF atau mPDF

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

// Inisialisasi PDF
$pdf = new \TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// membuat pdf menjadi landscape
$pdf->setPageOrientation('L');

// Judul
$pdf->Cell(0, 10, 'Laporan Buku Perpustakaan', 0, 1, 'C');

// Tanggal Periode
$pdf->Cell(0, 10, "Dari: $tanggal_awal - Sampai: $tanggal_akhir", 0, 1, 'C');

// Table Header
$pdf->Cell(10, 10, 'No', 1);
$pdf->Cell(40, 10, 'Title', 1);
$pdf->Cell(40, 10, 'Author', 1);
$pdf->Cell(40, 10, 'Synopsis', 1);
$pdf->Cell(30, 10, 'Book Date', 1);
$pdf->Ln();

// Mengisi data ke dalam PDF
$no = 1; // Nomor urut tamu
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(10, 10, $no++, 1);
    $pdf->Cell(40, 10, $row['title'], 1);
    $pdf->Cell(30, 10, $row['author'], 1);
    $pdf->Cell(30, 10, $row['synopsis'], 1);
    $pdf->Cell(30, 10, $row['book_date'], 1);
    $pdf->Cell(40, 10, date('Y-m-d', strtotime($row['book_date'])), 1);
    $pdf->Ln();
}

// Output PDF
$pdf->Output('laporan_buku.pdf', 'D');
exit();
?>
