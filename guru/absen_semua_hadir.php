<?php
session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    echo json_encode([
        'status' => 'gagal',
        'message' => 'Akses ditolak.'
    ]);
    exit();
}

if (
    !isset($_POST['siswa_ids']) ||
    !isset($_POST['tanggal']) ||
    !isset($_POST['mapel_id'])
) {
    echo json_encode([
        'status' => 'gagal',
        'message' => 'Data tidak lengkap.'
    ]);
    exit();
}

$siswa_ids = $_POST['siswa_ids'];
$tanggal   = $_POST['tanggal'];
$mapel_id  = (int) $_POST['mapel_id'];
$id_guru   = $_SESSION['user_id'];

if (!is_array($siswa_ids) || empty($siswa_ids)) {
    echo json_encode([
        'status' => 'gagal',
        'message' => 'Daftar siswa kosong.'
    ]);
    exit();
}

$stmt = $koneksi->prepare("
    INSERT INTO absensi (siswa_id, tanggal, mapel_id, status, dicatat_oleh)
    VALUES (?, ?, ?, 'Hadir', ?)
    ON DUPLICATE KEY UPDATE
        status = 'Hadir',
        dicatat_oleh = VALUES(dicatat_oleh)
");

$total = 0;

foreach ($siswa_ids as $siswa_id) {
    $siswa_id = (int) $siswa_id;
    $stmt->bind_param("isii", $siswa_id, $tanggal, $mapel_id, $id_guru);
    $stmt->execute();
    $total++;
}

echo json_encode([
    'status' => 'sukses',
    'message' => "$total siswa berhasil ditandai hadir."
]);