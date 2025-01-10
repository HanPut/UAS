<?php
session_start();
include 'db.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil ID dosen yang sedang login
$user_id = $_SESSION['user_id'];

// Periksa apakah parameter id terdapat di URL
if (!isset($_GET['id'])) {
    die("ID jadwal tidak ditemukan.");
}

$jadwal_id = $_GET['id'];

// Ambil data jadwal dari database yang hanya milik dosen yang sedang login
$stmt = $conn->prepare("
    SELECT jadwal.id, jadwal.dosen_id, dosen.nama AS dosen_name, jadwal.tanggal, jadwal.mulai, jadwal.selesai, jadwal.ruangan
    FROM jadwal
    JOIN dosen ON jadwal.dosen_id = dosen.id
    WHERE jadwal.id = ? AND jadwal.dosen_id = ?
");
$stmt->bind_param("ii", $jadwal_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Jadwal tidak ditemukan atau Anda tidak memiliki hak untuk mengedit jadwal ini.");
}

$jadwal = $result->fetch_assoc();

// Jika form disubmit, update jadwal
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];
    $mulai = $_POST['mulai'];
    $selesai = $_POST['selesai'];
    $ruangan = $_POST['ruangan'];

    // Update jadwal
    $update_stmt = $conn->prepare("
        UPDATE jadwal SET tanggal = ?, mulai = ?, selesai = ?, ruangan = ? WHERE id = ?
    ");
    $update_stmt->bind_param("ssssi", $tanggal, $mulai, $selesai, $ruangan, $jadwal_id);

    if ($update_stmt->execute()) {
        echo "Jadwal berhasil diupdate!";
    } else {
        echo "Gagal mengupdate jadwal.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal</title>
    <!-- Memasukkan CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Penjadwalan Dosen</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard_admin.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="daftar_jadwal.php">Daftar Jadwal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="input_dosen.php">Input Dosen</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Jadwal</h2>

        <form method="POST">
            <div class="mb-3">
                <label for="dosen_name" class="form-label">Dosen</label>
                <input type="text" class="form-control" id="dosen_name" value="<?= htmlspecialchars($jadwal['dosen_name']) ?>" disabled>
            </div>
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
            <button type="submit" class="btn btn-primary w-100 mb-3">Update Jadwal</button>
        </form>
        <a href="daftar_jadwal.php" class="btn btn-secondary w-100">Kembali</a>
    </div>

    <!-- Memasukkan JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
