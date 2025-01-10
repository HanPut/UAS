<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {  // Periksa berdasarkan user_id
    header('Location: index.php');
    exit();
}

// Query untuk mengambil data dosen dari tabel dosen
$stmt = $conn->prepare("SELECT id, nama, nip, email, telepon FROM dosen");
$stmt->execute();
$dosen = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Menangani penghapusan data dosen
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Query untuk menghapus dosen berdasarkan id
    $stmt_delete = $conn->prepare("DELETE FROM dosen WHERE id = :id");
    $stmt_delete->bindParam(':id', $delete_id);
    if ($stmt_delete->execute()) {
        header("Location: data_dosen.php"); // Redirect setelah delete
        exit();
    } else {
        echo "Gagal menghapus data dosen.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="d-flex">
                <a href="javascript:history.back()" class="btn btn-outline-light m-2 p-2">Kembali</a>
                <i class="fas fa-arrow-left"></i>
            </div>
            <a class="navbar-brand" href="dashboard_admin.php">Universitas Danta 2025</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="lihat_jadwal.php">Jadwal</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="dosen.php">Dosen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profil.php">Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
    </nav>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Daftar Dosen</h2>
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dosen as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['nip']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['telepon']) ?></td>
                    <td>
                        <!-- Tombol Edit -->
                        <a href="edit_data.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <!-- Tombol Delete -->
                        <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Memasukkan JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
