<?php
$servername = "localhost";
$username = "root"; // username biasanya 'root' untuk localhost
$password = ""; // password biasanya kosong untuk localhost
$dbname = "kasir"; // nama database kamu

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>