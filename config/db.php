<?php

// Konfigurasi Database
$host = 'localhost';
$user = 'atimschi_absen';
$pass = 'madrasah123456789';
$db_name = 'atimschi_absen';

// Membuat koneksi ke database
$koneksi = new mysqli($host, $user, $pass, $db_name);

// Memeriksa apakah koneksi berhasil atau gagal
if ($koneksi->connect_error) {
    // Jika koneksi gagal, hentikan skrip dan tampilkan pesan error
    die("Koneksi ke database gagal: " . $koneksi->connect_error);
}

// Mengatur zona waktu default ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

?>