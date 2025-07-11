<?php
session_start();
include '../../../functions.php';

$conn = connectDB(); 

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); 
$offset = ($page - 1) * $limit;

$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM nilai");
$totalRow = $totalQuery->fetch_assoc();
$totalData = $totalRow['total'];
$totalPages = ceil($totalData / $limit);

$sql = "
    SELECT nilai.id_nilai, nilai.id_siswa, nilai.id_matpel, nilai.nilai, siswa.nama AS nama_siswa, mata_pelajaran.nama_matpel
    FROM nilai 
    JOIN siswa ON nilai.id_siswa = siswa.id_siswa
    JOIN mata_pelajaran ON nilai.id_matpel = mata_pelajaran.id_matpel
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute(); 
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Nilai Siswa</title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="shortcut icon" type="image/png" href="../../../assets/images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">Data Nilai Siswa</div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <a href="/view/pages/nilai/addnilai.php" class="btn btn-sm btn-outline-primary">Add</a>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Siswa</th>
                                                <th>Nama Mata Pelajaran</th>
                                                <th>Nilai</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            if ($result->num_rows > 0) {
                                                $no = 1;
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . $no++ . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nama_siswa']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nama_matpel']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nilai']) . "</td>";
                                                    echo "<td class='d-flex flex-wrap gap-1'>
                                                        <a href='/view/pages/nilai/detailnilai.php?id=" . $row['id_nilai'] . "' class='btn btn-sm w-auto m-1 btn-outline-info'>Detail</a>
                                                        <a href='/view/pages/nilai/updatenilai.php?id=" . $row['id_nilai'] . "' class='btn btn-sm w-auto m-1 btn-outline-warning'>Update</a>

                                                        <form action='/view/pages/nilai/deletenilai.php' method='POST' onsubmit='return confirm(\"Apakah kamu yakin ingin menghapus nilai ini?\")' '>
                                                            <input type='hidden' name='id_nilai' value='" . $row['id_nilai'] . "'>
                                                            <button type='submit' class='btn btn-sm w-auto m-1 btn-outline-danger'>Delete</button>
                                                        </form>
                                                    </td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5' class='text-center'>No mata pelajaran found</td></tr>";
                                            }
                                        ?>

                                        </tbody>
                                    </table>

                                    <nav>
                                        <ul class="pagination justify-content-center">
                                            <?php if ($page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                                                </li>
                                            <?php endif; ?>

                                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <?php if ($page < $totalPages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>              

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>