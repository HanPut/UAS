<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitasi dan validasi input pengguna
    $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
    $password = trim($_POST['password']);

    // Siapkan query untuk mencari pengguna berdasarkan username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Mengambil hasil query
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $user = $stmt->fetch();

    // Cek apakah pengguna ditemukan dan passwordnya valid
    if ($user && password_verify($password, $user['password'])) {
        // Mengatur ulang ID sesi untuk mencegah session fixation
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        // Redirect ke halaman yang sesuai berdasarkan role
        if ($user['role'] == 'admin') {
            header("Location: dashboard_admin.php");
        } elseif ($user['role'] == 'dosen') {
            header("Location: dashboard_dosen.php");
        }
        exit();
    } else {
        // Jika login gagal
        $error = "Username atau password tidak valid!";
    }
    $_SESSION['username'] = $user['username'];  // Simpan username di sesi

}
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f9;
            color: #333;
        }

        .container {
            margin-top: 100px;
        }

        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="POST" action="">
            <h2 class="text-center">Login</h2>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
            <?php if (isset($error)) echo "<p class='error-message text-center'>$error</p>"; ?>
        </form>
        <p class="text-center">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
