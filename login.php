<?php
session_start();

// Cek apakah user sudah login
if (isset($_SESSION['role'])) {
    // Jika login sebagai admin, arahkan ke dashboard admin
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/index.php");
        exit();
    } 
    // Jika login sebagai guru, arahkan ke panel guru
    elseif ($_SESSION['role'] === 'guru') {
        header("Location: guru/index.php");
        exit();
    }
}

// Jika belum login atau tidak ada session, langsung arahkan ke halaman login
header("Location: login.php");
exit();
?>