<?php
// Mulai sesi
session_start();

// Periksa apakah pengguna telah login
if (!isset($_SESSION['username']) || !isset($_SESSION['login_status']) || $_SESSION['login_status'] !== 'true') {
    $_SESSION['error_msg'] = "Silakan login terlebih dahulu.";
    header("Location: login.php");
    exit();
}

// Tentukan URL dashboard berdasarkan role pengguna
$dashboardUrl = '';
switch ($_SESSION['jabatan']) {
    case 'admin':
    case 'karyawan':
    case 'pimpinan':
        $dashboardUrl = 'dashboard.php';
        break;
}

// Memeriksa apakah ada permintaan untuk menambah, mengedit, atau menghapus barang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db.php';

    // Menambah barang
    if (isset($_POST['tambah'])) {
        $nama_barang = trim($_POST['nama_barang']);
        $harga_barang = $_POST['harga_barang'];
        $stok = $_POST['stok'];
        $id_users = $_SESSION['id_users'];

        if (empty($nama_barang) || empty($harga_barang) || empty($stok)) {
            $_SESSION['error_message'] = "Harap isi semua field!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        // Cek apakah nama barang sudah ada (case insensitive)
        $check_sql = "SELECT COUNT(*) as count FROM barang WHERE LOWER(nama_barang) = LOWER('$nama_barang')";
        $check_result = $conn->query($check_sql);
        $check_row = $check_result->fetch_assoc();
        
        if ($check_row['count'] > 0) {
            $_SESSION['error_message'] = "Nama barang '$nama_barang' sudah ada! Silakan gunakan nama yang berbeda.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        $sql = "INSERT INTO barang (nama_barang, harga_barang, stok, id_users) VALUES ('$nama_barang', '$harga_barang', '$stok', '$id_users')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success_message'] = "Barang '$nama_barang' berhasil ditambahkan!";
            // Redirect untuk mencegah duplicate submission saat refresh
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['error_message'] = "Error: " . $conn->error;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        $conn->close();
    }

    // Mengedit barang
    if (isset($_POST['edit'])) {
        $id_barang = $_POST['id_barang'];
        $harga_barang = $_POST['harga_barang'];
        $stok = $_POST['stok'];

        $sql = "UPDATE barang SET harga_barang='$harga_barang', stok='$stok' WHERE id_barang='$id_barang'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success_message'] = "Data barang berhasil diupdate!";
            // Redirect untuk mencegah duplicate submission saat refresh
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['error_message'] = "Error: " . $conn->error;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        $conn->close();
    }

    // Menghapus barang
    if (isset($_POST['hapus'])) {
        $id_barang = $_POST['id_barang'];

        $hapusTransaksi = "DELETE FROM transaksi WHERE id_barang='$id_barang'";
        if ($conn->query($hapusTransaksi) === TRUE) {
            $hapusBarang = "DELETE FROM barang WHERE id_barang='$id_barang'";
            if ($conn->query($hapusBarang) === TRUE) {
                $_SESSION['success_message'] = "Barang berhasil dihapus!";
                // Redirect untuk mencegah duplicate submission saat refresh
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal hapus barang: " . $conn->error;
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Gagal hapus transaksi: " . $conn->error;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Data Barang</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<style>
    body {
        background-image: url('bg.jpg');
        background-size: cover;
        background-position: center;
    }
    .container {
        background-color: white;
        border-radius: 10px;
        padding: 10px;
    }
    .modal-content {
        padding: 30px;
        box-shadow: 0px 0px 30px darkslategrey;
    }
    table, thead, td, th {
        border: 3px solid;
    }
    .myid {
        color: brown;
        text-align: center;
    }
</style>
<body>
<div class="container">
    <h2 class="mt-4 mb-4">Manajemen Data Barang</h2>
    
    <!-- Alert Messages -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success_message']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error_message']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    
    <button class="btn btn-success mb-4 float-right mr-5" data-toggle="modal" data-target="#addBarangModal">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Modal Tambah -->
    <div class="modal" tabindex="-1" role="dialog" id="addBarangModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Tambah Barang</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <div class="form-group">
                            <label for="nama_barang">Nama Barang:</label>
                            <input type="text" class="form-control" name="nama_barang" required>
                        </div>
                        <div class="form-group">
                            <label for="harga_barang">Harga Barang:</label>
                            <input type="number" class="form-control" name="harga_barang" required>
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok Barang:</label>
                            <input type="number" class="form-control" name="stok" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="tambah">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID Barang</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Nama User</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php
        include 'db.php';
        $sql = "SELECT barang.id_barang, barang.nama_barang, barang.harga_barang, barang.stok, users.nama_lengkap FROM barang JOIN users ON barang.id_users = users.id_users";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><p class='myid'>" . $row['id_barang'] . "</p></td>";
            echo "<td>" . $row['nama_barang'] . "</td>";
            // Format harga tanpa desimal - menggunakan rtrim untuk menghilangkan .00
            $harga_formatted = "Rp. " . rtrim(rtrim(number_format($row['harga_barang'], 2, '.', ','), '0'), '.');
            echo "<td>" . $harga_formatted . "</td>";
            echo "<td>" . $row['stok'] . "</td>";
            echo "<td>" . $row['nama_lengkap'] . "</td>";
            echo "<td>
                    <button class='btn btn-warning fas fa-edit' data-toggle='modal' data-target='#editModal' data-id='" . $row['id_barang'] . "'></button>
                    <button class='btn btn-danger fas fa-trash' data-toggle='modal' data-target='#deleteModal' data-id='" . $row['id_barang'] . "'></button>
                  </td>";
            echo "</tr>";
        }
        $conn->close();
        ?>
        </tbody>
    </table>
</div>
<div class="text-center mt-3">
    <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
</div>

<!-- Modal Edit -->
<div class="modal" tabindex="-1" role="dialog" id="editModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Edit Barang</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="hidden" id="edit_id_barang" name="id_barang">
                    <div class="form-group">
                        <label>Harga Barang:</label>
                        <input type="number" class="form-control" id="edit_harga_barang" name="harga_barang" required>
                    </div>
                    <div class="form-group">
                        <label>Stok Barang:</label>
                        <input type="number" class="form-control" id="edit_stok" name="stok" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="edit">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal" tabindex="-1" role="dialog" id="deleteModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Hapus Barang</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <input type="hidden" id="delete_id_barang" name="id_barang">
                    <p>Apakah Anda yakin ingin menghapus barang ini?</p>
                    <button type="submit" class="btn btn-danger" name="hapus">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#editModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this);
            modal.find('#edit_id_barang').val(id);
        });

        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this);
            modal.find('#delete_id_barang').val(id);
        });
    });
</script>
</body>
</html>