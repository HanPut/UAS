<?php
// Koneksi ke database
include 'config.php';

// Cek apakah pengguna yang sedang login adalah admin
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Jika bukan admin, redirect ke halaman lain (misalnya login)
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'admin'; // Role yang sudah ditentukan sebagai 'admin'

    // Periksa apakah username sudah ada
    if ($stmt = $conn->prepare("SELECT * FROM users WHERE username = ?")) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Username sudah terdaftar. <a href='index.php'>Kembali</a>";
        } else {
            // Tambahkan pengguna baru
            if ($stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)")) {
                $stmt->bind_param("sss", $username, $password, $role);
                if ($stmt->execute()) {
                    echo "Registrasi berhasil! <a href='login.php'>Login</a>";
                } else {
                    echo "Terjadi kesalahan saat menyimpan data.";
                }
            }
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
    <!-- Link ke CSS Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        background-color: #f4f4f9;
        color: #333;
    }

    .container {
        margin-top: 100px;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Daftar Pengguna</h2>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="reg-username">Username:</label>
                <input type="text" class="form-control" id="reg-username" name="username" required>
            </div>

            <div class="form-group">
                <label for="reg-password">Password:</label>
                <input type="password" class="form-control" id="reg-password" name="password" required>
            </div>

            <!-- Hilangkan bagian role dari form karena hanya admin yang bisa mendaftar -->
            <input type="hidden" name="role" value="admin">

            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
        </form>
        <p class="text-center">Sudah punya akun? <a href="index.php">Login di sini</a></p>
    </div>

    <!-- Link ke JS Bootstrap dan jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
