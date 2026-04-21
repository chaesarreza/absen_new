<?php

require_once '../config/check_license.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once '../config/db.php';

// Ambil data admin yang sedang login
$admin_id = $_SESSION['user_id'];
$result_admin = $koneksi->query("SELECT nama_lengkap FROM users WHERE id = $admin_id");
$admin_data = $result_admin->fetch_assoc();
$nama_admin = $admin_data['nama_lengkap'] ?? 'Admin';

// Ambil Pengaturan Madrasah
$result_settings = $koneksi->query("SELECT setting_value FROM settings WHERE setting_name = 'nama_madrasah'");
$nama_madrasah_header = $result_settings->fetch_assoc()['setting_value'] ?? 'Nama Madrasah';

// Ambil Nama Aplikasi dari DB (untuk sidebar)
$result_app = $koneksi->query("SELECT setting_value FROM settings WHERE setting_name = 'nama_aplikasi'");
$nama_aplikasi = $result_app->fetch_assoc()['setting_value'] ?? 'Aplikasi Absensi';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard Admin' ?> - <?= htmlspecialchars($nama_madrasah_header); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    
    <link rel="shortcut icon" href="../assets/img/logo.png" type="image/x-icon">
</head>

<body class="admin-panel">

    <div class="admin-panel-wrapper">
        
        <aside class="sidebar">
            <div class="sidebar-header text-center">
                <img src="../assets/img/logo.png" alt="Logo" class="sidebar-logo mb-3">
                <h5 class="sidebar-title"><?= htmlspecialchars($nama_aplikasi); ?></h5>
                <p class="sidebar-subtitle"><?= htmlspecialchars($nama_madrasah_header); ?></p>
            </div>
            
            <ul class="nav nav-pills flex-column sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php">
                        <i class="bi bi-grid-fill"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'analitik.php' ? 'active' : '' ?>" href="analitik.php">
                        <i class="bi bi-bar-chart-line-fill"></i> Dasbor Analitik
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'guru.php' ? 'active' : '' ?>" href="guru.php">
                        <i class="bi bi-person-video3"></i> Data Guru
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'kelas.php' ? 'active' : '' ?>" href="kelas.php">
                        <i class="bi bi-building"></i> Data Kelas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'siswa.php' ? 'active' : '' ?>" href="siswa.php">
                        <i class="bi bi-person-bounding-box"></i> Data Siswa
                    </a>
                </li>
                <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'mapel.php' ? 'active' : '' ?>" href="mapel.php">
                    <i class="bi bi-journal-text"></i> Data Mata Pelajaran
                </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'matriks_jadwal.php' ? 'active' : '' ?>" href="matriks_jadwal.php">
                        <i class="bi bi-grid-3x3-gap-fill"></i> Matriks Jadwal
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'impor_historis.php' ? 'active' : '' ?>" href="impor_historis.php">
                        <i class="bi bi-cloud-upload-fill"></i> Impor Data Historis
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'cetak_kartu_siswa.php' ? 'active' : '' ?>" href="cetak_kartu_siswa.php">
                        <i class="bi bi-credit-card"></i> Cetak Kartu Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'laporan_absen.php' ? 'active' : '' ?>" href="laporan_absen.php">
                        <i class="bi bi-file-earmark-bar-graph"></i> Laporan Absensi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pengaturan.php' ? 'active' : '' ?>" href="pengaturan.php">
                        <i class="bi bi-gear-fill"></i> Pengaturan
                    </a>
                </li>
            </ul>
        </aside>

        <main class="content-area">
            
            <nav class="navbar navbar-expand-lg navbar-floating">
                <div class="container-fluid">
                    
                    <button class="btn btn-outline-secondary d-lg-none" type="button" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>

                    <ul class="navbar-nav ms-auto">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle"></i> <?= $_SESSION['nama'] ?? 'Administrator'; ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="pengaturan.php"><i class="bi bi-person"></i> Profil Saya</a></li>
            <li><a class="dropdown-item" href="ganti_password.php"><i class="bi bi-key"></i> Ganti Password</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
    </li>
</ul>
                </div>
            </nav>

            <nav class="mobile-admin-nav d-lg-none" aria-label="Navigasi cepat admin">
                <a class="mobile-admin-nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php">
                    <i class="bi bi-grid-fill"></i><span>Home</span>
                </a>
                <a class="mobile-admin-nav-link <?= basename($_SERVER['PHP_SELF']) == 'siswa.php' ? 'active' : '' ?>" href="siswa.php">
                    <i class="bi bi-people-fill"></i><span>Siswa</span>
                </a>
                <a class="mobile-admin-nav-link <?= basename($_SERVER['PHP_SELF']) == 'laporan_absen.php' ? 'active' : '' ?>" href="laporan_absen.php">
                    <i class="bi bi-file-earmark-text-fill"></i><span>Laporan</span>
                </a>
                <a class="mobile-admin-nav-link <?= basename($_SERVER['PHP_SELF']) == 'pengaturan.php' ? 'active' : '' ?>" href="pengaturan.php">
                    <i class="bi bi-gear-fill"></i><span>Akun</span>
                </a>
                <button type="button" class="mobile-admin-nav-link mobile-admin-nav-menu" id="mobileMenuTrigger" aria-label="Buka menu utama">
                    <i class="bi bi-list"></i><span>Menu</span>
                </button>
            </nav>

            <div class="content-body">