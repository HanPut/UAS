<?php
session_start();
include 'db.php';

// Menangani form submission untuk menambah dosen
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_dosen') {
    // Memastikan data dosen yang diperlukan ada
    if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['nama']) && !empty($_POST['nip']) && !empty($_POST['email'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Menyimpan password dengan hashing
        $role = 'dosen';  // Pastikan 'role' adalah dosen

        // Menyiapkan statement untuk memasukkan data ke tabel users
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);
        $stmt->bindParam(3, $role);

        try {
            // Menyimpan data pengguna
            if ($stmt->execute()) {
                // Menyimpan data dosen ke tabel dosen
                $user_id = $conn->lastInsertId();
                $nama = $_POST['nama'];
                $nip = $_POST['nip'];
                $email = $_POST['email'];
                $telepon = $_POST['telepon'];

                $stmt_dosen = $conn->prepare("INSERT INTO dosen (user_id, nama, nip, email, telepon) VALUES (?, ?, ?, ?, ?)");
                $stmt_dosen->bindParam(1, $user_id);
                $stmt_dosen->bindParam(2, $nama);
                $stmt_dosen->bindParam(3, $nip);
                $stmt_dosen->bindParam(4, $email);
                $stmt_dosen->bindParam(5, $telepon);

                if ($stmt_dosen->execute()) {
                    $message = "Dosen berhasil ditambahkan!";
                    $alert_type = "success";
                } else {
                    $message = "Gagal menambahkan data dosen.";
                    $alert_type = "danger";
                }
            } else {
                $message = "Gagal menambahkan pengguna.";
                $alert_type = "danger";
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
            $alert_type = "danger";
        }
    } else {
        $message = "Pastikan semua data dosen diisi dengan benar!";
        $alert_type = "warning";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 30px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .toast-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            pointer-events: none;
        }
    </style>
</head>

<body>

    <div class="container form-container">
        <h2 class="text-center mb-4">Input Data Dosen</h2>

        <!-- Form Input Data Dosen -->
        <form method="POST">
            <input type="hidden" name="action" value="add_dosen">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama Dosen</label>
                <input type="text" name="nama" id="nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nip">NIP</label>
                <input type="text" name="nip" id="nip" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="telepon">Telepon</label>
                <input type="text" name="telepon" id="telepon" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">Tambah Dosen</button>
        </form>

        <a href="dashboard_admin.php" class="btn btn-secondary w-100 mt-3">Kembali</a>
    </div>

    <!-- Toast Notification -->
    <div class="toast-container">
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
        var toastElList = [].slice.call(document.querySelectorAll('.toast'));
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl);
        });
        toastList.forEach(function (toast) {
            toast.show(); // Menampilkan toast
        });
    </script>

</body>

</html>
