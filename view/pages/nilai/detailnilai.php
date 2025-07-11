<?php
session_start();
include '../../../functions.php';

class Nilai {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getNilaiDetail($id_nilai) {
        $stmt = $this->conn->prepare("
            SELECT nilai.id_nilai, nilai.id_siswa, nilai.id_matpel, nilai.nilai, siswa.nama, mata_pelajaran.nama_matpel, nilai.created_at, nilai.updated_at 
            FROM nilai
            INNER JOIN siswa ON nilai.id_siswa = siswa.id_siswa
            INNER JOIN mata_pelajaran ON nilai.id_matpel = mata_pelajaran.id_matpel 
            WHERE id_nilai = ?
        ");
        $stmt->bind_param("i", $id_nilai);
        $stmt->execute();
        $result = $stmt->get_result();
        $nilaiData = $result->fetch_assoc();
        $stmt->close();
        return $nilaiData;
    }

    public function __destruct() {
        $this->conn->close();
    }
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID nilai tidak valid!");
}

$id_nilai = intval($_GET['id']);
$nilaiObj = new Nilai();
$nilaiData = $nilaiObj->getNilaiDetail($id_nilai);

if (!$nilaiData) {
    die("Data siswa tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Nilai</title>
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
                <a href="/view/pages/matapelajaran/mymatapelajaran.php" class="list-group-item list-group-item-action">Data Mata Pelajaran</a>
                <a href="/view/pages/nilai/mynilai.php" class="list-group-item list-group-item-action active">Data Nilai Siswa</a>
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
                                <p><strong>Nama Siswa:</strong> <?= htmlspecialchars($nilaiData['nama']) ?></p>
                                <p><strong>Nama Mata Pelajaran:</strong> <?= htmlspecialchars($nilaiData['nama_matpel']) ?></p>
                                <p><strong>Nilai:</strong> <?= htmlspecialchars($nilaiData['nilai']) ?></p>
                                <p><strong>Dibuat Pada:</strong> <?= htmlspecialchars($nilaiData['created_at']) ?></p>
                                <p><strong>Terakhir Diperbarui:</strong> <?= htmlspecialchars($nilaiData['updated_at']) ?></p>

                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <a href="mynilai.php" class="btn btn-primary m-1">Kembali</a>
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