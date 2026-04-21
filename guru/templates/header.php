<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Panel Guru' ?></title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="guru-panel">

<nav class="navbar navbar-expand-lg navbar-guru">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="index.php">
            <img src="../assets/img/logo.png" alt="Logo" style="height: 35px;" class="me-2">
            Guru Workspace
        </a>
        <div class="ms-auto d-flex align-items-center">
            <div id="theme-toggle" class="theme-toggle me-3" style="cursor: pointer;">
                <i class="bi bi-moon-stars-fill"></i>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle px-2 py-1 small" type="button" id="dropdownGuru" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i> <?= $_SESSION['nama_lengkap'] ?? (isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Guru'); ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownGuru">
                    <li>
                        <a class="dropdown-item" href="index.php">
                            <i class="bi bi-person me-2"></i> Profil Saya
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="ganti_password.php">
                            <i class="bi bi-key me-2"></i> Ganti Password
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="../logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</nav>

<div class="guru-mobile-hub d-lg-none" aria-label="Akses cepat guru">
    <div class="guru-mobile-hub-head">
        <span>Quick Access</span>
        <button type="button" id="mobileThemeToggle" class="guru-mobile-theme-btn">
            <i class="bi bi-circle-half"></i> Tema
        </button>
    </div>
    <div class="guru-mobile-shortcuts">
        <a href="index.php" class="guru-shortcut-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">
            <i class="bi bi-house-door-fill"></i><span>Dashboard</span>
        </a>
        <a href="ganti_password.php" class="guru-shortcut-link <?= basename($_SERVER['PHP_SELF']) == 'ganti_password.php' ? 'active' : '' ?>">
            <i class="bi bi-key-fill"></i><span>Password</span>
        </a>
        <a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;" class="guru-shortcut-link">
            <i class="bi bi-arrow-up-circle-fill"></i><span>Ke Atas</span>
        </a>
        <a href="../logout.php" class="guru-shortcut-link text-danger">
            <i class="bi bi-box-arrow-right"></i><span>Logout</span>
        </a>
    </div>
</div>
