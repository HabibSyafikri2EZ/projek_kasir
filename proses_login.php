<?php
session_start();

include 'db.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username && $password) {
    $sql = "SELECT * FROM users WHERE username = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);

        if ($stmt->execute() === TRUE) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Kata sandi sudah benar, simpan id_user, jabatan, dan username ke sesi
                    $_SESSION['id_users'] = $user['id_users'];
                    $_SESSION['jabatan'] = $user['jabatan'];
                    $_SESSION['username'] = $username;
                    $_SESSION['login_status'] = 'true'; // Tambahkan ini untuk menandai bahwa pengguna sudah login

                    // Alihkan ke halaman registrasi jika pengguna belum mengisi jabatan
                    if (empty($user['jabatan'])) {
                        header('Location: registration.php');
                        exit();
                    }

                    // Alihkan ke dasbor yang sama untuk semua peran pengguna
                    header('Location: dashboard.php');
                    exit();
                } else {
                    echo "<div class='alert alert-danger' role='alert'>Password yang anda masukan salah, silahkan dicoba lagi.</div>";
                }
            } else {
                echo "<div class='alert alert-danger' role='alert'>Error: Username tidak ditemukan.</div>";
            }
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