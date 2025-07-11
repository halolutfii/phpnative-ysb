<?php
session_start();
include '../../../functions.php';

class MataPelajaran {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addMatpel($namamatpel) {
        if (empty($namamatpel)) {
            return "Nama mata pelajaran tidak boleh kosong!";
        }

        $stmt = $this->conn->prepare("INSERT INTO mata_pelajaran (nama_matpel, created_at, updated_at) VALUES (?, NOW(), NOW())");
        $stmt->bind_param("s", $namamatpel);
        
        if ($stmt->execute()) {
            header("Location: /view/pages/matapelajaran/mymatapelajaran.php?success=1");
            exit;
        } else {
            return "Gagal menambahkan mata pelajaran!";
        }

        $stmt->close();
    }
}

$conn = connectDB();
$matapelajaranHandler = new MataPelajaran($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namamatpel = trim($_POST['nama_matpel']);

    $message = $matapelajaranHandler->addMatpel($namamatpel);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Siswa</title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="shortcut icon" type="image/png" href="../../../assets/images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a href="/view/pages/dashboard.php" class="navbar-brand">YAYASAN SYIAR BANGSA</a>
        </div>
    </nav>

    <div class="wrapper">
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

        <div id="content">
            <div class="overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-xl-12 mb-4 mb-lg-0">
                        <div class="card">
                            <div class="card-header bg-primary text-white">Add Mata Pelajaran</div>
                            <form method="post">
                                <div class="card-body">
                                    <?php if (isset($message)) echo "<div class='alert alert-danger'>$message</div>"; ?>
                                    <div class="m-2">
                                        <label for="nama_matpel" class="form-label">Nama Mata Pelajaran</label>
                                        <input type="text" class="form-control" name="nama_matpel" required>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    <a href="mymatapelajaran.php" class="btn btn-primary m-1">Cancel</a>
                                    <button type="submit" class="btn btn-success btn-sm w-auto m-1">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>              
</body>
</html>