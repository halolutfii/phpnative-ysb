<?php
session_start();
include '../../../functions.php';

class Nilai {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getNilai($id_nilai) {
        $stmt = $this->conn->prepare("
            SELECT nilai.id_nilai, nilai.nilai, siswa.nama AS nama_siswa, mata_pelajaran.nama_matpel
            FROM nilai 
            JOIN siswa ON nilai.id_siswa = siswa.id_siswa
            JOIN mata_pelajaran ON nilai.id_matpel = mata_pelajaran.id_matpel
            WHERE nilai.id_nilai = ?
        ");
        $stmt->bind_param("i", $id_nilai);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    public function updateNilai($id_nilai, $nilai) {
        $stmt = $this->conn->prepare("UPDATE nilai SET nilai = ?, updated_at = NOW() WHERE id_nilai = ?");
        $stmt->bind_param("si", $nilai, $id_nilai);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID nilai tidak ditemukan!");
}

$id_nilai = intval($_GET['id']);
$nilaiHandler = new Nilai();
$nilaiData = $nilaiHandler->getNilai($id_nilai);

if (!$nilaiData) {
    die("Data nilai tidak ditemukan!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nilai = trim($_POST['nilai']);

    $$errorMessage = null;

    if (!empty($nilai)) {
        $result = $nilaiHandler->updateNilai($id_nilai, $nilai);
        if ($result === true) {
            header("Location: mynilai.php");
            exit;
        } else {
            $errorMessage = $result;
        }
    } else {
        $errorMessage = "Nilai wajib diisi!";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Mata Pelajaran</title>
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

                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger text-center">
                            <?= htmlspecialchars($errorMessage) ?>
                        </div>
                    <?php endif; ?>

                    <div class="col-12 col-xl-12 mb-4 mb-lg-0">
                        <div class="card">
                            <div class="card-header bg-primary text-white">Update Nilai</div>
                            <form method="POST">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Siswa</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($nilaiData['nama_siswa']) ?>" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Mata Pelajaran</label>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($nilaiData['nama_matpel']) ?>" disabled>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nilai</label>
                                        <input type="number" name="nilai" class="form-control" required min="0" max="100" value="<?= htmlspecialchars($nilaiData['nilai']) ?>">
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <a href="mynilai.php" class="btn btn-secondary btn-sm m-1">Cancel</a>
                                    <button type="submit" class="btn btn-success btn-sm m-1" style="width: 100px;">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Content -->
    </div>              
</body>
</html>