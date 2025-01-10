<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM dosen WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $dosen = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dosen) {
        die("Data dosen tidak ditemukan.");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $nip = $_POST['nip'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $matkul = $_POST['matkul'];

    $stmt_update = $conn->prepare("UPDATE dosen SET nama = :nama, nip = :nip, email = :email, telepon = :telepon, matkul = :matkul WHERE id = :id");
    $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_update->bindParam(':nama', $nama);
    $stmt_update->bindParam(':nip', $nip);
    $stmt_update->bindParam(':email', $email);
    $stmt_update->bindParam(':telepon', $telepon);
    $stmt_update->bindParam(':matkul', $matkul);

    if ($stmt_update->execute()) {
        $_SESSION['message'] = "Data dosen berhasil diperbarui.";
        header('Location: data_dosen.php');
        exit();
    } else {
        $_SESSION['message'] = "Gagal memperbarui data dosen.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Data Dosen</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info"><?= $_SESSION['message']; ?></div>
            <?php unset($_SESSION['message']); ?>
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
            <div class="mb-3">
                <label for="matkul" class="form-label">Mata Kuliah</label>
                <input type="text" class="form-control" id="matkul" name="matkul" value="<?= htmlspecialchars($dosen['matkul']) ?>" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Update Data</button>
        </form>
    </div>
</body>
</html>
