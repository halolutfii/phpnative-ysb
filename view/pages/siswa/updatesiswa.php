<?php
session_start();
include '../../../functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

class Siswa {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getSiswa($id_siswa) {
        $stmt = $this->conn->prepare("SELECT nama, kelas, tanggal_lahir FROM siswa WHERE id_siswa = ?");
        $stmt->bind_param("i", $id_siswa);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    public function updateSiswa($id_siswa, $nama, $kelas, $tanggal_lahir) {
        $stmt = $this->conn->prepare("UPDATE siswa SET nama = ?, kelas = ?, tanggal_lahir = ?, updated_at = NOW() WHERE id_siswa = ?");
        $stmt->bind_param("sssi", $nama, $kelas, $tanggal_lahir, $id_siswa);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID siswa tidak ditemukan!");
}

$id_siswa = intval($_GET['id']);
$siswaHandler = new Siswa();
$siswaData = $siswaHandler->getSiswa($id_siswa);

if (!$siswaData) {
    die("Data siswa tidak ditemukan!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST['nama']);
    $kelas = trim($_POST['kelas']);
    $tanggal_lahir = trim($_POST['tanggal_lahir']);

    if (!empty($nama) && !empty($kelas) && !empty($tanggal_lahir)) {
        if ($siswaHandler->updateSiswa($id_siswa, $nama, $kelas, $tanggal_lahir)) {
            header("Location: mysiswa.php");
            exit;
        } else {
            echo "Gagal memperbarui data siswa!";
        }
    } else {
        echo "Semua field wajib diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Siswa</title>
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
                            <div class="card-header bg-primary text-white">Update Siswa</div>
                            <form method="POST">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Siswa</label>
                                        <input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($siswaData['nama']) ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="kelas" class="form-label">Kelas</label>
                                        <input type="text" name="kelas" class="form-control" required value="<?= htmlspecialchars($siswaData['kelas']) ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" class="form-control" required value="<?= htmlspecialchars($siswaData['tanggal_lahir']) ?>">
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <a href="mysiswa.php" class="btn btn-secondary btn-sm m-1">Cancel</a>
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