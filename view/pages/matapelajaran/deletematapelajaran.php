<?php
session_start();
include '../../../functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['id_matpel']) || empty($_POST['id_matpel'])) {
        echo "ID Mata Pelajaran tidak ditemukan!";
        exit;
    }

    $id_matpel = intval($_POST['id_matpel']);
    
    $conn = connectDB();

    $stmt = $conn->prepare("DELETE FROM mata_pelajaran WHERE id_matpel = ?");
    $stmt->bind_param("i", $id_matpel);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Data mata pelajaran berhasil dihapus!";
        header("Location: mymatapelajaran.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Gagal menghapus data mata pelajaran!";
        header("Location: detailmatpel.php?id=" . $id_matpel);
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Akses tidak valid!";
    exit;
}
?>