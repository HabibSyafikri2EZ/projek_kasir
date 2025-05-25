<?php
session_start();

include 'db.php';

// Cek apakah $_SESSION['id_user'] ada
if (!isset($_SESSION['id_users'])) {
    echo "Error: id_user tidak ada dalam session";
    exit;
}
// Tentukan URL dashboard berdasarkan role pengguna
$dashboardUrl = '';
switch ($_SESSION['jabatan']) {
    case 'admin':
        $dashboardUrl = 'dashboard.php';
        break;
    case 'karyawan':
        $dashboardUrl = 'dashboard.php';
        break;
    case 'pimpinan':
        $dashboardUrl = 'dashboard.php';
        break;
}
// Ambil data pengguna dari database
$sql = "SELECT * FROM users WHERE id_users = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['id_users']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Pengguna</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<style>
    h3{
        text-align:center;
    }
    strong{
        display: inline-block;
        width: 10px;
        text-align: right;
    
    }
    .container {
        max-width: 500px;
        background-color: white; /* Warna latar belakang putih */
        border-radius: 10px;
        padding: 10px;
        margin-top: center;
        display: auto;
    }
    table tr td{
        font-size:18px;
    }
    body{
        display: auto;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-image: url('bg.jpg');
            background-size: cover;
            background-position: center;
    }
</style>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3 class="text-success">Profil Pengguna</h3>
            </div>
            <div class="card-body">
            <table>
                <tr>
                    <td>Nama Lengkap&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td><?php echo $user['nama_lengkap']; ?></td>
                </tr>
                <tr>
                    <td>Jenis Kelamin&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td><?php echo $user['jenis_kelamin']; ?></td>
                </tr>
                <tr>
                    <td>Jabatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                    <td><?php echo $user['jabatan']; ?></td>
                </tr>
            </table>
                <hr>
                <p class="text-danger"><u>Anda hanya dapat mengganti password:</u></p>
                <a href="change_password.php" class="btn btn-primary float-right">Ganti Password</a>
                <div class="container">
                    <a href="<?php echo $dashboardUrl; ?>" class="btn btn-secondary float-left">
                    <i class="fas fa-arrow-left"></i></a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>