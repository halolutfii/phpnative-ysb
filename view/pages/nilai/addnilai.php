<?php
session_start();
include '../../../functions.php';

$conn = connectDB();

class Nilai {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addNilai($id_siswa, $id_matpel, $nilai) {
        if (empty($id_siswa) || empty($id_matpel) || $nilai === '') {
            return "Semua field harus diisi!";
        }

        if (!is_numeric($nilai) || $nilai < 0 || $nilai > 100) {
            return "Nilai harus berupa angka antara 0 sampai 100!";
        }

        $stmt = $this->conn->prepare("INSERT INTO nilai (id_siswa, id_matpel, nilai, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("ssi", $id_siswa, $id_matpel, $nilai);

        if ($stmt->execute()) {
            header("Location: /view/pages/nilai/mynilai.php?success=1");
            exit;
        } else {
            return "Gagal menambahkan nilai siswa!";
        }
    }
}

$nilaiHandler = new Nilai($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_siswa = $_POST['id_siswa'];
    $id_matpel = $_POST['id_matpel'];
    $nilai = $_POST['nilai'];

    $message = $nilaiHandler->addNilai($id_siswa, $id_matpel, $nilai);
}
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
                <a href="/view/pages/matapelajaran/mymatapelajaran.php" class="list-group-item list-group-item-action">Data Mata Pelajaran</a>
                <a href="/view/pages/nilai/mynilai.php" class="list-group-item list-group-item-action active">Data Nilai Siswa</a>
            </div>
        </nav>

        <div id="content">
            <div class="overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-xl-12 mb-4 mb-lg-0">
                        <div class="card">
                            <div class="card-header bg-primary text-white">Add Nilai Siswa</div>
                                <form method="post">
                                    <div class="card-body">
                                        <?php if (isset($message)) echo "<div class='alert alert-danger'>$message</div>"; ?>

                                        <div class="mb-3">
                                            <label for="id_siswa" class="form-label">Nama Siswa</label>
                                            <select name="id_siswa" class="form-control" required>
                                                <option value="">-- Pilih Siswa --</option>
                                                <?php
                                                $resultSiswa = $conn->query("SELECT id_siswa, nama FROM siswa");
                                                while ($s = $resultSiswa->fetch_assoc()) {
                                                    echo "<option value='{$s['id_siswa']}'>{$s['nama']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="id_matpel" class="form-label">Mata Pelajaran</label>
                                            <select name="id_matpel" class="form-control" required>
                                                <option value="">-- Pilih Mata Pelajaran --</option>
                                                <?php
                                                $resultMapel = $conn->query("SELECT id_matpel, nama_matpel FROM mata_pelajaran");
                                                while ($m = $resultMapel->fetch_assoc()) {
                                                    echo "<option value='{$m['id_matpel']}'>{$m['nama_matpel']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="nilai" class="form-label">Nilai</label>
                                            <input type="number" name="nilai" class="form-control" required min="0" max="100">
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex justify-content-end">
                                        <a href="mynilai.php" class="btn btn-secondary m-1">Cancel</a>
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
<?php $conn->close(); ?>