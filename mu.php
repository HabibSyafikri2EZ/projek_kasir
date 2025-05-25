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
        $dashboardUrl = 'dashboard.php';
        break;
    case 'karyawan':
        $dashboardUrl = 'dashboard.php';
        break;
    case 'pimpinan':
        $dashboardUrl = 'dashboard.php';
        break;
}

// Memeriksa apakah ada permintaan untuk menambah, mengedit, atau menghapus pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db.php';

    // Mengedit pengguna
    if (isset($_POST['edit'])) {
        $id_users = $_POST['id_users'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $username = $_POST['username'];
        $jabatan = $_POST['jabatan'];

        $sql = "UPDATE users SET nama_lengkap='$nama_lengkap', username='$username', jabatan='$jabatan' WHERE id_users='$id_users'";
        if ($conn->query($sql) === TRUE) {
            echo "";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }

    // Menghapus pengguna
    if (isset($_POST['hapus'])) {
        $id_users = $_POST['id_users'];

        $sql = "DELETE FROM users WHERE id_users='$id_users'";
        if ($conn->query($sql) === TRUE) {
            echo "";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Pengguna</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        background-image: url('bg.jpg');
        background-size: cover;
        background-position: center;
        margin: 0;
    }

    .container {
        max-width: 625px;
        margin-top: 25px;
        padding: 15px;
        background-color: #fff;
    }

    .heading {
        height: 65px;
        padding: 15px 0;
    }

    .heading .btn {
        margin: 15px 0;
    }

    .heading h2 {
        line-height: 65px;
    }

    h2 {
        border-bottom: 4px solid grey;
        border-radius: 4px;
        margin: 0 auto;
        padding: 20px 0;
        text-align: center;
    }

    th {
        text-align: center;
    }

    a {
        color: white;
    }

    h3 {
        padding: 25px;
        margin: 25px;
    }

    .container a {
        margin-bottom: 20px;
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

    .back-btn {
        margin-bottom: 20px;
    }
</style>
<body>
    <div class="container">
        <a href="dashboard.php" class="btn btn-secondary back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>

        <h2 class="mt-4 mb-4">Manajemen Data Pengguna</h2>

        <!-- Tampilkan tabel pengguna -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Pengguna</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'db.php';
                $sql = "SELECT * FROM users";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . "<p class='myid'>" . $row['id_users'] . "</p>" . "</td>";
                    echo "<td>" . $row['nama_lengkap'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['jabatan'] . "</td>";
                    echo "<td>
                            <button class='btn btn-warning fas fa-edit' data-toggle='modal' data-target='#editModal' data-id='" . $row['id_users'] . "'></button>
                            <button class='btn btn-danger fas fa-trash' data-toggle='modal' data-target='#deleteModal' data-id='" . $row['id_users'] . "'></button>
                        </td>";
                    echo "</tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal untuk edit -->
    <div class="modal" tabindex="-1" role="dialog" id="editModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Edit Pengguna</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" id="edit_id_users" name="id_users">
                        <div class="form-group">
                            <label for="edit_nama_lengkap">Nama Lengkap:</label>
                            <input type="text" class="form-control" id="edit_nama_lengkap" name="nama_lengkap">
                        </div>
                        <div class="form-group">
                            <label for="edit_username">Username:</label>
                            <input type="text" class="form-control" id="edit_username" name="username">
                        </div>
                        <div class="form-group">
                                <label for="jabatan">Posisi</label>
                                <select id="jabatan" name="jabatan" class="form-control">
                                    <option value="admin">Admin</option>
                                    <option value="pimpinan">Pimpinan</option>
                                    <option value="karyawan">Karyawan</option>
                                </select>
                            </div>
                        <button type="submit" class="btn btn-warning" name="edit">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk delete -->
    <div class="modal" tabindex="-1" role="dialog" id="deleteModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Hapus Pengguna</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" id="delete_id_users" name="id_users">
                        <p>Apakah Anda yakin ingin menghapus pengguna ini?</p>
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
                modal.find('#edit_id_users').val(id);
            });

            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);
                modal.find('#delete_id_users').val(id);
            });
        });
    </script>
</body>
</html>