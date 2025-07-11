<?php 
// Connection to DB
function connectDB() {
    $host = "localhost";
    $user = "root"; 
    $pass = "root";
    $dbname = "mysiswa";

    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    return $conn;
}
?>