<?php
session_start();
include '../../../functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['id_siswa']) || empty($_POST['id_siswa'])) {
        echo "ID Siswa tidak ditemukan!";
        exit;
    }

    $id_siswa = intval($_POST['id_siswa']);
    
    $conn = connectDB();

    $stmt = $conn->prepare("DELETE FROM siswa WHERE id_siswa = ?");
    $stmt->bind_param("i", $id_siswa);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Data siswa berhasil dihapus!";
        header("Location: mysiswa.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Gagal menghapus data siswa!";
        header("Location: detailsiswa.php?id=" . $id_siswa);
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Akses tidak valid!";
    exit;
}
?>