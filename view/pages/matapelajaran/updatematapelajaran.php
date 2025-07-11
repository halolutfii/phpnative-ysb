<?php
session_start();
include '../../../functions.php';

// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../auth/login.php");
//     exit;
// }

class MataPelajaran {
    private $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function getMatpel($id_matpel) {
        $stmt = $this->conn->prepare("SELECT nama_matpel FROM mata_pelajaran WHERE id_matpel = ?");
        $stmt->bind_param("i", $id_matpel);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_assoc();
    }

    public function updateMatpel($id_matpel, $nama_matpel) {
        $stmt = $this->conn->prepare("UPDATE mata_pelajaran SET nama_matpel = ?, updated_at = NOW() WHERE id_matpel = ?");
        $stmt->bind_param("si", $nama_matpel, $id_matpel);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID mata pelajaran tidak ditemukan!");
}

$id_matpel = intval($_GET['id']);
$matpelHandler = new MataPelajaran();
$matpelData = $matpelHandler->getMatpel($id_matpel);

if (!$matpelData) {
    die("Data mata kuliah tidak ditemukan!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_matpel = trim($_POST['nama_matpel']);

    if (!empty($nama_matpel)) {
        if ($matpelHandler->updateMatpel($id_matpel, $nama_matpel)) {
            header("Location: mymatapelajaran.php");
            exit;
        } else {
            echo "Gagal memperbarui data mata pelajaran!";
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
                            <div class="card-header bg-primary text-white">Update Mata Pelajaran</div>
                            <form method="POST">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="nama_matpel" class="form-label">Nama Mata Pelajaran</label>
                                        <input type="text" name="nama_matpel" class="form-control" required value="<?= htmlspecialchars($matpelData['nama_matpel']) ?>">
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <a href="mymatapelajaran.php" class="btn btn-secondary btn-sm m-1">Cancel</a>
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