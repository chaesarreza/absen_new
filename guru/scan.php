<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['kelas_id']) || !isset($_GET['mapel_id'])) {
    header("Location: index.php");
    exit();
}

$kelas_id = (int) $_GET['kelas_id'];
$mapel_id = (int) $_GET['mapel_id'];
require_once '../config/db.php';

$stmt = $koneksi->prepare("SELECT nama_kelas FROM kelas WHERE id = ?");
$stmt->bind_param("i", $kelas_id);
$stmt->execute();
$nama_kelas = $stmt->get_result()->fetch_assoc()['nama_kelas'] ?? 'Tidak Diketahui';

$stmt = $koneksi->prepare("SELECT nama_mapel FROM mata_pelajaran WHERE id = ?");
$stmt->bind_param("i", $mapel_id);
$stmt->execute();
$nama_mapel = $stmt->get_result()->fetch_assoc()['nama_mapel'] ?? 'Tidak Diketahui';

$title = "Scan Absensi " . $nama_kelas;
require_once 'templates/header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow border-0 mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-1">Scanner Absensi</h4>
                    <p class="text-muted mb-4 small"><?= htmlspecialchars($nama_mapel) ?> - Kelas <?= htmlspecialchars($nama_kelas) ?></p>

                    <div id="reader" style="width: 100%; border-radius: 10px; overflow: hidden; border: 2px solid #eee; background: #f8f9fa;"></div>

                    <div id="control-buttons" class="mt-3">
                        <button id="btn-start" class="btn btn-primary btn-lg w-100" onclick="startScanning()">
                            <i class="bi bi-camera me-2"></i>Aktifkan Kamera
                        </button>
                        <button id="btn-stop" class="btn btn-danger btn-lg w-100 d-none" onclick="stopScanning()">
                            <i class="bi bi-camera-video-off me-2"></i>Matikan Kamera
                        </button>
                    </div>

                    <div id="scan-status" class="mt-3 text-secondary small">
                        <i class="bi bi-info-circle me-1"></i> Klik tombol di atas untuk memulai absen
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 text-start">
                    <h6 class="m-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Siswa Baru Absen</h6>
                </div>
                <div class="card-body p-0">
                    <ul id="hadir-list" class="list-group list-group-flush text-start"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasi" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title mx-auto small fw-bold">KONFIRMASI KEHADIRAN</h5>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-person-badge text-primary" style="font-size: 3.5rem;"></i>
                </div>
                <h4 id="namaSiswaText" class="fw-bold mb-0">Nama Siswa</h4>
                <p id="nisSiswaText" class="text-muted mb-3">NISN: -</p>
                <input type="hidden" id="barcodeSiswaHidden">

                <div class="mb-3 text-start">
                    <label for="statusKehadiran" class="form-label fw-semibold">Status Kehadiran</label>
                    <select id="statusKehadiran" class="form-select">
                        <option value="Hadir" selected>Hadir</option>
                        <option value="Alpa">Alpa</option>
                        <option value="Izin">Ijin</option>
                        <option value="Sakit">Sakit</option>
                    </select>
                </div>

                <button type="button" onclick="simpanAbsensi()" class="btn btn-primary btn-lg w-100 mb-2">SIMPAN ABSENSI</button>
                <button type="button" onclick="closeModal()" class="btn btn-link text-muted w-100">Batal / Salah Scan</button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.4/html5-qrcode.min.js"></script>
<script>
let html5QrCode;
let isScanning = true;
let modalElement = null;

function getModalElement() {
    if (!modalElement) {
        const modalNode = document.getElementById('modalKonfirmasi');
        modalElement = new bootstrap.Modal(modalNode);
    }
    return modalElement;
}

function startScanning() {
    isScanning = true;
    html5QrCode = new Html5Qrcode('reader');

    html5QrCode.start(
        { facingMode: 'environment' },
        { fps: 15, qrbox: { width: 300, height: 200 }, aspectRatio: 1.0 },
        onScanSuccess
    ).then(() => {
        document.getElementById('btn-start').classList.add('d-none');
        document.getElementById('btn-stop').classList.remove('d-none');
        document.getElementById('scan-status').innerHTML = "<span class='text-success fw-bold'>Kamera Aktif. Dekatkan Barcode.</span>";
    }).catch(err => alert('Kamera Error: ' + err));
codex/fix-error-500-on-pdf-export-6atvd1
}

function stopScanning() {
    if (!html5QrCode) return;

    html5QrCode.stop().then(() => {
        document.getElementById('btn-stop').classList.add('d-none');
        document.getElementById('btn-start').classList.remove('d-none');
        document.getElementById('scan-status').innerHTML = "<span class='text-muted'>Kamera dimatikan.</span>";
    }).catch(err => alert('Gagal mematikan kamera: ' + err));
}

function onScanSuccess(decodedText) {
    if (!isScanning) return;

    isScanning = false;
    fetch('get_siswa_by_barcode.php?barcode=' + encodeURIComponent(decodedText))
        .then(response => {
            if (!response.ok) throw new Error('Status: ' + response.status);
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                alert(data.message || 'Barcode tidak terdaftar di sistem.');
                isScanning = true;
                return;
            }

            document.getElementById('namaSiswaText').innerText = data.nama_siswa;
            document.getElementById('nisSiswaText').innerText = 'NISN: ' + data.nisn;
            document.getElementById('barcodeSiswaHidden').value = decodedText;
            document.getElementById('statusKehadiran').value = 'Hadir';
            getModalElement().show();
        })
        .catch(err => {
            alert('ERROR SISTEM: ' + err.message);
            isScanning = true;
        });
}

document.addEventListener('DOMContentLoaded', () => {
    const modalNode = document.getElementById('modalKonfirmasi');
    if (modalNode) {
        modalNode.addEventListener('hidden.bs.modal', () => {
            isScanning = true;
        });
    }
});

function closeModal() {
    getModalElement().hide();
}

function simpanAbsensi() {
    const barcode = document.getElementById('barcodeSiswaHidden').value;
    const status = document.getElementById('statusKehadiran').value;

    const params = new URLSearchParams();
    params.append('qr_code_key', barcode);
    params.append('kelas_id', '<?= $kelas_id ?>');
    params.append('mapel_id', '<?= $mapel_id ?>');
    params.append('status', status);

    fetch('proses_absen.php', {
        method: 'POST',
        body: params
    })
    .then(response => response.json())
    .then(data => {
        if (data.status !== 'sukses') {
            alert(data.message || 'Gagal menyimpan absensi.');
            getModalElement().hide();
            isScanning = true;
            return;
        }

}

function stopScanning() {
    if (!html5QrCode) return;

    html5QrCode.stop().then(() => {
        document.getElementById('btn-stop').classList.add('d-none');
        document.getElementById('btn-start').classList.remove('d-none');
        document.getElementById('scan-status').innerHTML = "<span class='text-muted'>Kamera dimatikan.</span>";
    }).catch(err => alert('Gagal mematikan kamera: ' + err));
}

function onScanSuccess(decodedText) {
    if (!isScanning) return;

    isScanning = false;
    fetch('get_siswa_by_barcode.php?barcode=' + encodeURIComponent(decodedText))
        .then(response => {
            if (!response.ok) throw new Error('Status: ' + response.status);
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                alert(data.message || 'Barcode tidak terdaftar di sistem.');
                isScanning = true;
                return;
            }

            document.getElementById('namaSiswaText').innerText = data.nama_siswa;
            document.getElementById('nisSiswaText').innerText = 'NISN: ' + data.nisn;
            document.getElementById('barcodeSiswaHidden').value = decodedText;
            document.getElementById('statusKehadiran').value = 'Hadir';
            modalElement.show();
        })
        .catch(err => {
            alert('ERROR SISTEM: ' + err.message);
            isScanning = true;
        });
}

document.getElementById('modalKonfirmasi').addEventListener('hidden.bs.modal', () => {
    isScanning = true;
});

function closeModal() {
    modalElement.hide();
}

function simpanAbsensi() {
    const barcode = document.getElementById('barcodeSiswaHidden').value;
    const status = document.getElementById('statusKehadiran').value;

    const params = new URLSearchParams();
    params.append('qr_code_key', barcode);
    params.append('kelas_id', '<?= $kelas_id ?>');
    params.append('mapel_id', '<?= $mapel_id ?>');
    params.append('status', status);

    fetch('proses_absen.php', {
        method: 'POST',
        body: params
    })
    .then(response => response.json())
    .then(data => {
        if (data.status !== 'sukses') {
            alert(data.message || 'Gagal menyimpan absensi.');
            modalElement.hide();
            isScanning = true;
            return;
        }

 main
        const hadirList = document.getElementById('hadir-list');
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center bg-light border-start border-success border-4';
        li.innerHTML = `<div><i class='bi bi-check-circle-fill text-success me-2'></i><strong>${data.nama_siswa}</strong><span class='badge bg-secondary ms-2'>${status}</span></div><span class='badge bg-white text-dark border'>${new Date().toLocaleTimeString()}</span>`;
        hadirList.prepend(li);

 codex/fix-error-500-on-pdf-export-6atvd1
        getModalElement().hide();

        modalElement.hide();
 main
        setTimeout(() => { isScanning = true; }, 1200);
    })
    .catch(err => {
        alert('Terjadi kesalahan: ' + err.message);
 codex/fix-error-500-on-pdf-export-6atvd1
        getModalElement().hide();

        modalElement.hide();
main
        isScanning = true;
    });
}
</script>

<style>
    #reader video { border-radius: 10px; width: 100% !important; height: auto !important; }
    .list-group-item { transition: all 0.3s ease; }
</style>

<?php require_once 'templates/footer.php'; ?>
