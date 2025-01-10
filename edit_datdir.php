<?php
session_start();
include 'db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id = $_SESSION['user_id']; // ID pengguna yang login
    $nama = $_POST['nama'];
    $nip = $_POST['nip'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];

    // Update data di database
    try {
        $stmt = $conn->prepare("UPDATE dosen SET nama = :nama, nip = :nip, email = :email, telepon = :telepon WHERE user_id = :user_id");
        $stmt->bindParam(':nama', $nama);
        $stmt->bindParam(':nip', $nip);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telepon', $telepon);
        $stmt->bindParam(':user_id', $id);

        if ($stmt->execute()) {
            $message = "Data berhasil diperbarui!";
            $alert_type = "success";
        } else {
            $message = "Gagal memperbarui data.";
            $alert_type = "danger";
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
        $alert_type = "danger";
    }
}

// Ambil data dosen untuk ditampilkan di form
$stmt = $conn->prepare("SELECT dosen.id, dosen.nama, dosen.nip, dosen.email, dosen.telepon FROM dosen WHERE dosen.user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$dosen = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dosen) {
    die("Data dosen tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Diri Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Data Diri Dosen</h2>

        <?php if (isset($message)): ?>
            <div class="alert alert-<?= $alert_type ?>" role="alert">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($dosen['nama']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="nip" class="form-label">NIP</label>
                <input type="text" class="form-control" id="nip" name="nip" value="<?= htmlspecialchars($dosen['nip']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($dosen['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="telepon" class="form-label">Telepon</label>
                <input type="text" class="form-control" id="telepon" name="telepon" value="<?= htmlspecialchars($dosen['telepon']) ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>

        <a href="data_diri.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
