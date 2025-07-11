<?php
session_start();
include '../../../functions.php';

class Siswa {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getSiswaDetail($id_siswa) {
        $stmt = $this->conn->prepare("
            SELECT nama, kelas, tanggal_lahir, created_at, updated_at 
            FROM siswa 
            WHERE id_siswa = ?
        ");
        $stmt->bind_param("i", $id_siswa);
        $stmt->execute();
        $result = $stmt->get_result();
        $siswaData = $result->fetch_assoc();
        $stmt->close();
        return $siswaData;
    }

    public function getNilaiSiswa($id_siswa) {
        $stmt = $this->conn->prepare("
            SELECT mp.nama_matpel, n.nilai
            FROM nilai n
            JOIN mata_pelajaran mp ON n.id_matpel = mp.id_matpel
            WHERE n.id_siswa = ?
        ");
        $stmt->bind_param("i", $id_siswa);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function __destruct() {
        $this->conn->close();
    }
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID siswa tidak valid!");
}

$id_siswa = intval($_GET['id']);
$siswaObj = new Siswa();
$siswaData = $siswaObj->getSiswaDetail($id_siswa);
$nilaiList = $siswaObj->getNilaiSiswa($id_siswa);

if (!$siswaData) {
    die("Data siswa tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Siswa</title>
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
                <a href="/view/pages/siswa/mysiswa.php" class="list-group-item list-group-item-action active">Data Siswa</a>
                <a href="/view/pages/matapelajaran/mymatapelajaran.php" class="list-group-item list-group-item-action">Data Mata Pelajaran</a>
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
                            <div class="card-header bg-primary text-white">Detail Siswa</div>
                            <div class="card-body">
                                <p><strong>Nama:</strong> <?= htmlspecialchars($siswaData['nama']) ?></p>
                                <p><strong>Kelas:</strong> <?= htmlspecialchars($siswaData['kelas']) ?></p>
                                <p><strong>Tanggal Lahir:</strong> <?= htmlspecialchars(date("d M Y", strtotime($siswaData['tanggal_lahir']))) ?></p>
                                <p><strong>Dibuat Pada:</strong> <?= htmlspecialchars($siswaData['created_at']) ?></p>
                                <p><strong>Terakhir Diperbarui:</strong> <?= htmlspecialchars($siswaData['updated_at']) ?></p>

                                <hr>
                                
                                <h5>Nilai dan Mata Pelajaran</h5>
                                <?php if (!empty($nilaiList)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nama Mata Pelajaran</th>
                                                    <th>Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($nilaiList as $row): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row['nama_matpel']) ?></td>
                                                        <td><?= htmlspecialchars($row['nilai']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">Belum ada nilai yang tersedia untuk siswa ini.</p>
                                <?php endif; ?>

                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <a href="mysiswa.php" class="btn btn-primary m-1">Kembali</a>
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