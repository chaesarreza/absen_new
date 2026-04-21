<?php
session_start();
$title = "Data Siswa";
require_once '../config/db.php';
require_once 'templates/header.php';

$kelas_result = $koneksi->query("SELECT id, nama_kelas FROM kelas ORDER BY nama_kelas ASC");

$selected_kelas = isset($_GET['kelas_id']) ? (int) $_GET['kelas_id'] : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$result = null;

if ($selected_kelas > 0) {
    $search_sql = "";

    if (!empty($search)) {
        $safe_search = $koneksi->real_escape_string($search);
        $search_sql = " AND (siswa.nama_siswa LIKE '%$safe_search%' 
                        OR siswa.nisn LIKE '%$safe_search%')";
    }

    $query = "
        SELECT siswa.*, kelas.nama_kelas
        FROM siswa
        LEFT JOIN kelas ON siswa.kelas_id = kelas.id
        WHERE siswa.kelas_id = $selected_kelas
        $search_sql
        ORDER BY siswa.nama_siswa ASC
    ";

    $result = $koneksi->query($query);
}
?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Data Siswa</h1>
        <div>
            <a href="impor_siswa.php" class="btn btn-primary">Impor dari Excel</a>
            <a href="tambah_siswa.php" class="btn btn-success ms-2">Tambah Siswa</a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Pilih Kelas</label>
        <select name="kelas_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- Pilih Kelas --</option>
            <?php while($kelas = $kelas_result->fetch_assoc()): ?>
                <option value="<?= $kelas['id']; ?>" <?= $selected_kelas == $kelas['id'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($kelas['nama_kelas']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <?php if ($selected_kelas > 0): ?>
    <div class="col-md-4">
        <label class="form-label">Cari Siswa</label>
        <input type="text"
               name="search"
               class="form-control"
               placeholder="Nama / NISN..."
               value="<?= htmlspecialchars($search); ?>">
    </div>

    <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-primary w-100">
            Cari
        </button>
    </div>
    <?php endif; ?>
</form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">

            <?php if ($selected_kelas <= 0): ?>
                <div class="alert alert-info">
                    Silakan pilih kelas terlebih dahulu.
                </div>
            <?php else: ?>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0): $no = 1; ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nisn']); ?></td>
                            <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
                            <td>
                                <a href="edit_siswa.php?id=<?= $row['id']; ?>" class="btn btn-warn</form>ing btn-sm">Edit</a>
                                <a href="hapus_siswa.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">
                                Siswa tidak ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php endif; ?>

        </div>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>