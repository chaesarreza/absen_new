<?php
session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

if (!isset($_GET['barcode']) || trim($_GET['barcode']) === '') {
    echo json_encode(['success' => false, 'message' => 'Barcode tidak terbaca']);
    exit;
}

$barcode = trim($_GET['barcode']);
$stmt = $koneksi->prepare("SELECT nama_siswa, nisn FROM siswa WHERE qr_code_key = ? LIMIT 1");
$stmt->bind_param("s", $barcode);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    echo json_encode([
        'success' => true,
        'nama_siswa' => $data['nama_siswa'],
        'nisn' => $data['nisn']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Barcode tidak terdaftar']);
}
exit;
