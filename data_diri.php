<?php
session_start();
include 'db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) { 
    header('Location: login.php');
    exit();
}

try {
    // Query untuk mendapatkan data dosen dengan join tabel users
    $stmt = $conn->prepare("SELECT dosen.id, dosen.nama, dosen.nip, dosen.email, dosen.telepon, users.username 
                            FROM dosen 
                            JOIN users ON dosen.user_id = users.id
                            WHERE dosen.user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $dosen = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Kesalahan: " . $e->getMessage());
}

if (!$dosen) {
    die("Data dosen tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Diri Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Data Diri Dosen</h2>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($dosen['nama']) ?></h5>
                <p class="card-text"><strong>NIP:</strong> <?= htmlspecialchars($dosen['nip']) ?></p>
                <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($dosen['email']) ?></p>
                <p class="card-text"><strong>Telepon:</strong> <?= htmlspecialchars($dosen['telepon']) ?></p>
                <p class="card-text"><strong>Username:</strong> <?= htmlspecialchars($dosen['username']) ?></p>
                
                <!-- Tombol Edit untuk mengubah data diri -->
                <a href="edit_datdir.php" class="btn btn-primary">Edit</a>
            </div>
        </div>

        <a href="dashboard_dosen.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>