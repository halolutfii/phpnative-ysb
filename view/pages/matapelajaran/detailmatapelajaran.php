<?php
session_start();
include '../../../functions.php';

class MataPelajaran {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getMatpelDetail($id_matpel) {
        $stmt = $this->conn->prepare("
            SELECT nama_matpel, created_at, updated_at 
            FROM mata_pelajaran 
            WHERE id_matpel = ?
        ");
        $stmt->bind_param("i", $id_matpel);
        $stmt->execute();
        $result = $stmt->get_result();
        $siswaData = $result->fetch_assoc();
        $stmt->close();
        return $siswaData;
    }

    public function __destruct() {
        $this->conn->close();
    }
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID mata pelajaran tidak valid!");
}

$id_matpel = intval($_GET['id']);
$matapelajaranObj = new MataPelajaran();
$matpelData = $matapelajaranObj->getMatpelDetail($id_matpel);

if (!$matpelData) {
    die("Data siswa tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mata Pelajaran</title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="shortcut icon" type="image/png" href="../../../assets/images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Start Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a href="/view/pages/dashboard.php" class="navbar-brand">YAYASAN SYIAR BANGSA</a>
        </div>
    </nav>
    <!-- End Header -->

    <div class="wrapper">
        <!-- Start Sidebar -->
        <nav id="sidebar" class="bg-light sidebar">
            <div class="text-center py-3">
                <img src="../../../assets/images/favicon.png" alt="Logo" class="img-fluid" style="max-width: 80px;">
            </div>
            <div class="list-group">
                <a href="/view/pages/dashboard.php" class="list-group-item list-group-item-action">Dashboard</a>
                <a href="/view/pages/siswa/mysiswa.php" class="list-group-item list-group-item-action">Data Siswa</a>
                <a href="/view/pages/matapelajaran/mymatapelajaran.php" class="list-group-item list-group-item-action active">Data Mata Pelajaran</a>
                <a href="/view/pages/nilai/mynilai.php" class="list-group-item list-group-item-action">Data Nilai Siswa</a>
            </div>
        </nav>
        <!-- End Sidebar -->

        <!-- Start Content -->
        <div id="content">
            <div class="overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-xl-12 mb-4 mb-lg-0">
                        <div class="card">
                            <div class="card-header bg-primary text-white">Detail Mata Pelajaran</div>
                            <div class="card-body">
                                <p><strong>Nama:</strong> <?= htmlspecialchars($matpelData['nama_matpel']) ?></p>
                                <p><strong>Dibuat Pada:</strong> <?= htmlspecialchars($matpelData['created_at']) ?></p>
                                <p><strong>Terakhir Diperbarui:</strong> <?= htmlspecialchars($matpelData['updated_at']) ?></p>

                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <a href="mymatapelajaran.php" class="btn btn-primary m-1">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Content -->
    </div>              
</body>
</html>