<?php
session_start();

include 'db.php';

// Cek apakah $_SESSION['id_users'] ada
if (!isset($_SESSION['id_users'])) {
    echo "Error: id_users tidak ada dalam session";
    exit;
}

// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $current_password = isset($_POST['current_password']) ? $_POST['current_password'] : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Cek apakah password baru dan konfirmasi password sama
    if ($new_password != $confirm_password) {
        $_SESSION['error_msg'] = "Password baru dan konfirmasi password tidak sama";
        header('Location: change_password.php');
        exit;
    }

    // Ambil password saat ini dari database
    $sql = "SELECT password FROM users WHERE id_users = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['id_users']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Cek apakah password saat ini benar
    if (password_verify($current_password, $user['password'])) {
        // Jika benar, update password di database
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE id_users = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_password_hash, $_SESSION['id_users']);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Berhasil mengganti password";
            header('Location: change_password.php');
        } else {
            $_SESSION['error_msg'] = "Gagal mengganti password: " . $stmt->error;
            header('Location: change_password.php');
        }
    } else {
        $_SESSION['error_msg'] = "Password saat ini salah";
        header('Location: change_password.php');
    }
    $stmt->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ganti Password</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<style>
    .container{
        max-width:500px
    }
    body{
        background-color:rgba(219, 174, 77, 0.267);
    }
</style>
<body>
    <div class="container mt-5">
    <div class="card">
            <div class="card-header">
                <h3>Ganti Password</h3>
            </div>
            <div class="card-body">
                <?php
                if (isset($_SESSION['success_msg'])) {
                    echo "<div class='alert alert-success'>" . $_SESSION['success_msg'] . "</div>";
                    unset($_SESSION['success_msg']);
                }
                if (isset($_SESSION['error_msg'])) {
                    echo "<div class='alert alert-danger'>" . $_SESSION['error_msg'] . "</div>";
                    unset($_SESSION['error_msg']);
                }
                ?>
                <form action="change_password.php" method="post">
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini:</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Password Baru:</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password Baru:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Ganti Password</button>
                        <a href="profile.php" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>