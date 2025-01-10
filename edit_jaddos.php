<?php
session_start();
include 'db.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data peran pengguna (admin atau dosen) dari tabel users
$stmt = $conn->prepare("SELECT role FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika pengguna bukan dosen, redirect ke halaman utama
if ($user['role'] != 'dosen') {
    header('Location: lihat_jadwal.php');
    exit();
}

// Ambil data jadwal berdasarkan ID
if (isset($_GET['id'])) {
    $jadwal_id = $_GET['id'];
    
    // Cek apakah jadwal yang diminta milik dosen yang sedang login
    $stmt = $conn->prepare("SELECT * FROM jadwal WHERE id = :id AND dosen_id = :dosen_id");
    $stmt->bindParam(':id', $jadwal_id);
    $stmt->bindParam(':dosen_id', $_SESSION['user_id']);
    $stmt->execute();
    $jadwal = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika jadwal tidak ditemukan atau dosen_id tidak cocok, redirect ke daftar jadwal
    if (!$jadwal) {
        echo "Tidak ditemukan jadwal atau akses ditolak!";
        header('Location: lihat_jadwal.php');
        exit();
    }
} else {
    echo "ID jadwal tidak ditemukan.";
    header('Location: lihat_jadwal.php');
    exit();
}

// Proses form saat disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $tanggal = $_POST['tanggal'];
    $mulai = $_POST['mulai'];
    $selesai = $_POST['selesai'];
    $ruangan = $_POST['ruangan'];
    $matkul = $_POST['matkul'];

    // Update jadwal di database, hanya jika dosen_id cocok
    $stmt = $conn->prepare("UPDATE jadwal SET tanggal = :tanggal, mulai = :mulai, selesai = :selesai, ruangan = :ruangan, matkul = :matkul WHERE id = :id AND dosen_id = :dosen_id");
    $stmt->bindParam(':tanggal', $tanggal);
    $stmt->bindParam(':mulai', $mulai);
    $stmt->bindParam(':selesai', $selesai);
    $stmt->bindParam(':ruangan', $ruangan);
    $stmt->bindParam(':matkul', $matkul);
    $stmt->bindParam(':id', $jadwal_id);
    $stmt->bindParam(':dosen_id', $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        // Jika berhasil, redirect ke daftar jadwal
        header('Location: lihat_jadwal.php');
        exit();
    } else {
        echo "Gagal memperbarui jadwal. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Jadwal</h2>
        <form action="edit_jadwal.php?id=<?= $jadwal_id ?>" method="POST">
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= htmlspecialchars($jadwal['tanggal']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="mulai" class="form-label">Jam Mulai</label>
                <input type="time" class="form-control" id="mulai" name="mulai" value="<?= htmlspecialchars($jadwal['mulai']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="selesai" class="form-label">Jam Selesai</label>
                <input type="time" class="form-control" id="selesai" name="selesai" value="<?= htmlspecialchars($jadwal['selesai']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="ruangan" class="form-label">Ruangan</label>
                <input type="text" class="form-control" id="ruangan" name="ruangan" value="<?= htmlspecialchars($jadwal['ruangan']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="matkul" class="form-label">Mata Kuliah</label>
                <input type="text" class="form-control" id="matkul" name="matkul" value="<?= htmlspecialchars($jadwal['matkul']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Perbarui Jadwal</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
