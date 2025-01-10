<?php

function delete($id) {
    global $conn;

    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        die("ID tidak valid");
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id =id");

    try {
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log("Gagal menghapus data: " . $e->getMessage());
        return false;
    }
}


if (delete($id)) {
    echo "<script>
            alert('Data berhasil dihapus');
            document.location.href = 'index.php';
        </script>";
} else {
    echo "<script>
            alert('Terjadi kesalahan saat menghapus data. Silahkan coba lagi nanti.');
            document.location.href = 'index.php';
        </script>";
}