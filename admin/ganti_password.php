<?php
session_start();
$title = "Ganti Password";
require_once '../config/db.php';
require_once 'templates/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ganti_password'])) {
    $id_user = $_SESSION['user_id'];
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Ambil koneksi database (menyesuaikan variabel dari db.php, biasanya $koneksi atau $conn)
    $db_conn = isset($koneksi) ? $koneksi : $conn;

    $query = mysqli_query($db_conn, "SELECT password FROM users WHERE id = '$id_user'");
    $data = mysqli_fetch_assoc($query);

    if (password_verify($password_lama, $data['password'])) {
        if ($password_baru === $konfirmasi_password) {
            $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
            $update = mysqli_query($db_conn, "UPDATE users SET password = '$password_hash' WHERE id = '$id_user'");
            
            if ($update) {
                $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>Password berhasil diubah!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
            } else {
                $message = '<div class="alert alert-danger">Gagal menyimpan password ke database.</div>';
            }
        } else {
            $message = '<div class="alert alert-warning">Konfirmasi password baru tidak cocok!</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Password saat ini (lama) yang Anda masukkan salah!</div>';
    }
}
?>

<div class="page-header">
    <h1 class="h3"><i class="bi bi-shield-lock me-2"></i>Keamanan Akun</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ganti Password</li>
        </ol>
    </nav>
</div>

<div class="page-body">
    <div class="row">
        <div class="col-md-8 col-lg-6">
            <?= $message ?>
            
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0">Form Ganti Password</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Saat Ini</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password_lama" class="form-control" placeholder="Masukkan password lama" required>
                            </div>
                        </div>
                        
                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" name="password_baru" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Ulangi Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ketik ulang password baru" required minlength="6">
                            </div>
                        </div>

                        <div class="mt-3 text-end">
                            <a href="index.php" class="btn btn-light border me-2">Batal</a>
                            <button type="submit" name="ganti_password" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-3 small text-muted">
                <i class="bi bi-info-circle me-1"></i> Jika Anda lupa password, silakan hubungi Administrator sistem untuk melakukan reset (kecuali Anda adalah Admin).
            </div>
            
        </div>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>