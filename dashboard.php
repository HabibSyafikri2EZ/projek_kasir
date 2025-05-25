<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Redirect ke halaman login jika pengguna belum login
    header('Location: login.php');
    exit;
}

// Tampilkan pesan selamat datang yang berbeda berdasarkan role pengguna
if ($_SESSION['jabatan'] == 'admin') {
    $welcomeMessage = "Hai, " . $_SESSION['username'];
    $dashboardTitle = 'Admin';
    $name = 'Dashboard';
    $ket = 'Selamat datang di tampilan Dashboard Admin.';
} elseif ($_SESSION['jabatan'] == 'karyawan') {
    $welcomeMessage = "Hai, " . $_SESSION['username'];
    $dashboardTitle = 'Karyawan';
    $name = 'Dashboard';
    $ket = 'Selamat datang di tampilan Dashboard Karyawan.';
} else { 
    $welcomeMessage = "Hai, " . $_SESSION['username'];
    $dashboardTitle = 'Pimpinan';
    $name = 'Dashboard';
    $ket = 'Pimpinan.';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>UD. SEMBAKO MURAH BINJAI</title>
    <!-- Sisipkan CSS Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<style>
     /* Potongan kode yang ditambahkan */
     .jumbotron {
            background-color: grey;
            padding: 20px;
            border-radius: 10px;
        }

        .jumbotron h1 {
            color: black;
        }

        .jumbotron p {
            color: black;
        }
        /* Akhir potongan kode yang ditambahkan */

    body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-image: url('bg.jpg');
            background-size: cover;
            background-position: center;
            background-color: #000;
            color: black;
        }
    ul li a{
        margin: 40px 0 0px 0;
        padding: 20px;
    }
    .bg-custom{
        height:95vh;
        background-color: transparent;
    }
    .active{
        color:grey ;
        border-right: 2px solid;
    }
    h4{
        color:black;
        border-bottom:2px solid;
        border-radius:4px;
        padding:10px;
        background-color
    }
    .container-fluid{
        color: #red;
        background-color: transparent;

    }
    .bd{
        border-bottom: 3px solid black;
    }
    .nav-item{
        margin-top:-10px;
    }
    .div{
        float: left;
        text-align: right;
        color:brown;
        width:100%;
        border-bottom:3px solid;
        color: #000;
        height:5vh;
        padding:5px;
        font-size:12px;
        padding-right:160px;
    }
    .sidebar .nav-link:hover {
        background-color:grey;
        color: black;
        border-radius: 5px;
    }
    .sidebar .nav-link{
        margin-top:60px;
        border-radius: 5px;      
    }
    nav{
        
    }
    .div .copy{
        text-align: center;
        padding:5px;
        font-size:12px;
    }
</style>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-custom sidebar">
                        <div class="sidebar-sticky">
                    <h4 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1">
                        <?php echo $name; ?>
                    </h4>
                    <!-- Tampilkan menu yang berbeda berdasarkan role pengguna -->
                    <?php if ($_SESSION['jabatan'] == 'admin'): ?>
                        <!-- Tampilkan menu untuk admin -->
                        <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="mu.php">
                                <i class="fas fa-users-cog"></i>
                                Manajemen User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="mdb.php">
                                <i class="fas fa-boxes"></i>
                                Manajemen Data Barang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="transaksi.php">
                                <i class="fas fa-exchange-alt"></i>
                                Transaksi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="laporan.php">
                                <i class="fas fa-file-alt"></i>
                                Laporan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="pendaftaran.php">
                                <i class="fas fa-user-plus"></i>
                                Pendaftaran
                            </a>
                        </li>
                    </ul>
                    <?php elseif ($_SESSION['jabatan'] == 'karyawan'): ?>
                        <!-- Tampilkan menu untuk karyawan -->
                        <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="mdb.php">
                                <i class="fas fa-boxes"></i>
                                Manajemen Data Barang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="transaksi.php">
                                <i class="fas fa-exchange-alt"></i>
                                Transaksi
                            </a>
                        </li>
                    </ul>
                    <?php else: ?>
                        <!-- Tampilkan menu untuk pimpinan -->
                        <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="laporan.php">
                <i class="fas fa-file-alt"></i>
                Laporan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="pendaftaran.php">
                <i class="fas fa-user-plus"></i>
                Pendaftaran
            </a>
        </li>
    </ul>
<?php endif; ?>
                </div>
            </nav>

            <!-- Page Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo $dashboardTitle; ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group mr-2">
                            <a class="btn btn-outline-primary btn-light" href="profile.php"><i class="fas fa-user-circle"></i> Profile</a>
                            <a class="btn btn-outline-primary btn-light" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                </div>
                <!-- Konten dari setiap halaman akan ditampilkan di sini -->
                <div class="jumbotron">
                    <h1 class="display-4"><?php echo $welcomeMessage; ?></h1>
                    <p class="lead"><?php echo $ket; ?></p>
                    <hr class="my-4">
                    <p>Jika ada masalah, silahkan hubungi admin.</p>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Sisipkan script Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>