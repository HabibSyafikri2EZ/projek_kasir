<?php
include 'db.php';

$nama_lengkap = $_POST['nama_lengkap'] ?? '';
$jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
$jabatan = $_POST['jabatan'] ?? '';
$username = $_POST['username'] ?? '';
$password = password_hash($_POST['password'] ?? '', PASSWORD_BCRYPT);

if ($nama_lengkap && $jenis_kelamin && $jabatan && $username && $password) {
    $sql = "INSERT INTO users (nama_lengkap, jenis_kelamin, jabatan, username, password) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssss", $nama_lengkap, $jenis_kelamin, $jabatan, $username, $password);

        if ($stmt->execute() === TRUE) {
            header('Location: dashboard.php');
            exit();
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>Error: All fields are required.</div>";
}

$conn->close();
?>