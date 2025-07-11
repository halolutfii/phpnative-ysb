<?php
session_start();
include '../../../functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['id_nilai']) || empty($_POST['id_nilai'])) {
        echo "ID nilai tidak ditemukan!";
        exit;
    }

    $id_nilai = intval($_POST['id_nilai']);
    
    $conn = connectDB();

    $stmt = $conn->prepare("DELETE FROM nilai WHERE id_nilai = ?");
    $stmt->bind_param("i", $id_nilai);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Data nilai berhasil dihapus!";
        header("Location: mynilai.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Gagal menghapus data nilai!";
        header("Location: detailnilai.php?id=" . $id_nilai);
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Akses tidak valid!";
    exit;
}
?>