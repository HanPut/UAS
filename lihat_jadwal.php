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

// Cek apakah pengguna adalah admin atau dosen
if ($user['role'] == 'admin') {
    // Jika admin, ambil semua jadwal
    $stmt = $conn->prepare("SELECT jadwal.id, dosen.nama AS dosen_name, jadwal.tanggal, jadwal.mulai, jadwal.selesai, jadwal.ruangan, jadwal.matkul 
                            FROM jadwal 
                            JOIN dosen ON jadwal.dosen_id = dosen.id");
} else {
    // Jika dosen, ambil dosen_id berdasarkan user_id yang ada di session
    $stmt = $conn->prepare("SELECT id FROM dosen WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $dosen = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika dosen ditemukan, ambil jadwal berdasarkan dosen_id
    if ($dosen) {
        $dosen_id = $dosen['id'];
        $stmt = $conn->prepare("SELECT jadwal.id, dosen.nama AS dosen_name, jadwal.tanggal, jadwal.mulai, jadwal.selesai, jadwal.ruangan, jadwal.matkul 
                                FROM jadwal 
                                JOIN dosen ON jadwal.dosen_id = dosen.id 
                                WHERE jadwal.dosen_id = :dosen_id");
        $stmt->bindParam(':dosen_id', $dosen_id);
    } else {
        // Jika dosen tidak ditemukan
        echo "Dosen tidak ditemukan.";
        exit();
    }
}

$stmt->execute();
$jadwal = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jadwal</title>
    <!-- Memasukkan CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Universitas XYZ</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard_dosen.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_diri.php">Data Diri</a>
                    </li>
                    <!-- Menampilkan tombol logout jika sudah login -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Daftar Jadwal</h2>
        <?php if (empty($jadwal)): ?>
            <p class="text-center">Tidak ada jadwal yang tersedia.</p>
        <?php else: ?>
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Dosen</th>
                        <th>Tanggal</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Ruangan</th>
                        <th>Mata Kuliah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jadwal as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['dosen_name']) ?></td>
                        <td><?= htmlspecialchars($row['tanggal']) ?></td>
                        <td><?= htmlspecialchars($row['mulai']) ?></td>
                        <td><?= htmlspecialchars($row['selesai']) ?></td>
                        <td><?= htmlspecialchars($row['ruangan']) ?></td>
                        <td><?= htmlspecialchars($row['matkul']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Memasukkan JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
