<?php
error_reporting(0); // Matikan pesan error agar JSON tidak rusak
require_once '../config/db.php';

// Pastikan menggunakan variabel $koneksi sesuai config/db.php Anda
$db = isset($koneksi) ? $koneksi : $conn;

header('Content-Type: application/json');

$barcode = isset($_GET['barcode']) ? mysqli_real_escape_string($db, $_GET['barcode']) : '';

if (empty($barcode)) {
    echo json_encode(['success' => false, 'message' => 'Barcode tidak terbaca']);
    exit;
}

// Tambahkan NIS ke dalam query agar tidak 'Unknown column'
$query = mysqli_query($db, "SELECT nama_siswa, nis FROM siswa WHERE barcode = '$barcode' LIMIT 1");
$data = mysqli_fetch_assoc($query);

if ($data) {
    echo json_encode([
        'success' => true, 
        'nama_siswa' => $data['nama_siswa'], 
        'nis' => $data['nis']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Barcode tidak terdaftar']);
}
exit;