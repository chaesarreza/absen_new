<?php
session_start();
require '../config/db.php';
include 'templates/header.php';

$pesan = '';

if (isset($_POST['ganti_password'])) {
    $id_user = $_SESSION['user_id']; // Mengambil ID dari session login
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // 1. Cek kecocokan password lama
    $query = mysqli_query($conn, "SELECT password FROM users WHERE id = '$id_user'");
    $data = mysqli_fetch_assoc($query);

    if (password_verify($password_lama, $data['password'])) {
        // 2. Cek apakah password baru & konfirmasi sama
        if ($password_baru === $konfirmasi_password) {
            // 3. Enkripsi password baru dan Update ke database
            $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
            $update = mysqli_query($conn, "UPDATE users SET password = '$password_hash' WHERE id = '$id_user'");
            
            if ($update) {
                $pesan = "<div class='alert alert-success'>Password berhasil diubah!</div>";
            } else {
                $pesan = "<div class='alert alert-danger'>Gagal mengubah password.</div>";
            }
        } else {
            $pesan = "<div class='alert alert-warning'>Konfirmasi password baru tidak cocok!</div>";
        }
    } else {
        $pesan = "<div class='alert alert-danger'>Password lama salah!</div>";
    }
}
?>

<div class="d-flex">
   
    
    <div class="content p-4" style="flex: 1;">
        <h2 class="mb-4">Ganti Password</h2>
        
        <?= $pesan; ?>

        <div class="card shadow-sm col-md-6">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="password_lama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password_baru" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="konfirmasi_password" class="form-control" required minlength="6">
                    </div>
                    <button type="submit" name="ganti_password" class="btn btn-primary">Simpan Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>