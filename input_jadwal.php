<?php
session_start();
include 'db.php';

// Menangani form submission untuk menambah jadwal
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_jadwal') {
    if (!empty($_POST['dosen_id']) && !empty($_POST['jadwal_tanggal']) && !empty($_POST['jadwal_mulai']) && !empty($_POST['jadwal_selesai']) && !empty($_POST['jadwal_ruangan']) && !empty($_POST['jadwal_matkul'])) {
        $dosen_id = $_POST['dosen_id'];
        $tanggal = $_POST['jadwal_tanggal'];
        $mulai = $_POST['jadwal_mulai'];
        $selesai = $_POST['jadwal_selesai'];
        $ruangan = $_POST['jadwal_ruangan'];
        $matkul = $_POST['jadwal_matkul'];

        $stmt = $conn->prepare("INSERT INTO jadwal (dosen_id, tanggal, mulai, selesai, ruangan, matkul) VALUES (:dosen_id, :tanggal, :mulai, :selesai, :ruangan, :matkul)");
        $stmt->bindParam(':dosen_id', $dosen_id);
        $stmt->bindParam(':tanggal', $tanggal);
        $stmt->bindParam(':mulai', $mulai);
        $stmt->bindParam(':selesai', $selesai);
        $stmt->bindParam(':ruangan', $ruangan);
        $stmt->bindParam(':matkul', $matkul);  // Memperbaiki parameter matkul yang sebelumnya salah

        try {
            if ($stmt->execute()) {
                $message = "Jadwal berhasil ditambahkan!";
                $alert_type = "success";
            } else {
                $message = "Gagal menambahkan jadwal.";
                $alert_type = "danger";
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
            $alert_type = "danger";
        }
    } else {
        $message = "Pastikan semua form diisi dengan benar!";
        $alert_type = "warning";
    }
}

// Mengambil data dosen untuk form
$stmt = $conn->prepare("SELECT id, nama FROM dosen");
$stmt->execute();
$dosen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Penjadwalan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container form-container">
        <h2 class="text-center mb-4">Input Penjadwalan Dosen</h2>

        <!-- Form Input Jadwal -->
        <form method="POST">
            <input type="hidden" name="action" value="add_jadwal">
            <div class="form-group">
                <label for="dosen_id">Pilih Dosen</label>
                <select name="dosen_id" id="dosen_id" class="form-control" required>
                    <option value="" disabled selected>Pilih Dosen</option>
                    <?php foreach ($dosen as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= $d['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="jadwal_tanggal">Tanggal</label>
                <input type="date" name="jadwal_tanggal" id="jadwal_tanggal" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="jadwal_mulai">Waktu Mulai</label>
                <input type="time" name="jadwal_mulai" id="jadwal_mulai" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="jadwal_selesai">Waktu Selesai</label>
                <input type="time" name="jadwal_selesai" id="jadwal_selesai" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="jadwal_matkul">Mata Kuliah</label>
                <input type="text" name="jadwal_matkul" id="jadwal_matkul" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="jadwal_ruangan">Ruang</label>
                <input type="text" name="jadwal_ruangan" id="jadwal_ruangan" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Simpan Jadwal</button>
        </form>

        <!-- Tombol Kembali Menggunakan JavaScript -->
        <button onclick="goBack()" class="btn btn-secondary w-100 mt-3">Kembali</button>
    </div>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed top-50 start-50 translate-middle-x" style="z-index: 1050;">
        <?php if (isset($message)): ?>
            <div class="toast align-items-center text-bg-<?= $alert_type ?>" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?= $message ?>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Menampilkan toast setelah halaman selesai dimuat jika ada pesan
        var toastElList = [].slice.call(document.querySelectorAll('.toast'));
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl);
        });
        toastList.forEach(function (toast) {
            toast.show();
        });

        function goBack() {
            window.history.back();
        }
    </script>

</body>

</html>
