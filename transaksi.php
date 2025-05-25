<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['login_status'] !== 'true') {
    $_SESSION['error_msg'] = "Silakan login terlebih dahulu.";
    header("Location: login.php");
    exit();
}

include 'db.php';
$dashboardUrl = 'dashboard.php';

// TAMBAH TRANSAKSI
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $tgl_transaksi = $_POST['tgl_transaksi'];
    $jlh_terjual = intval($_POST['jlh_terjual']);
    $id_barang = intval($_POST['id_barang']);
    $id_users = $_SESSION['id_users'];

    $cekStok = $conn->query("SELECT stok FROM barang WHERE id_barang = '$id_barang'");
    if ($cekStok && $cekStok->num_rows > 0) {
        $stok_sekarang = intval($cekStok->fetch_assoc()['stok']);
        if ($stok_sekarang >= $jlh_terjual) {
            $sql = "INSERT INTO transaksi (tgl_transaksi, jlh_terjual, id_barang, id_users) 
                    VALUES ('$tgl_transaksi', '$jlh_terjual', '$id_barang', '$id_users')";
            if ($conn->query($sql)) {
                $conn->query("UPDATE barang SET stok = " . ($stok_sekarang - $jlh_terjual) . " WHERE id_barang = '$id_barang'");
                header("Location: transaksi.php");
                exit();
            } else {
                echo "Error insert transaksi: " . $conn->error;
            }
        } else {
            echo "<script>alert('Stok tidak mencukupi!'); window.location.href='transaksi.php';</script>";
        }
    }
}

// EDIT TRANSAKSI
if (isset($_POST['edit'])) {
    $id_transaksi = intval($_POST['id_transaksi']);
    $tgl_transaksi = $_POST['tgl_transaksi'];
    $jlh_terjual = intval($_POST['jlh_terjual']);

    $sql = "UPDATE transaksi SET tgl_transaksi='$tgl_transaksi', jlh_terjual='$jlh_terjual' WHERE id_transaksi='$id_transaksi'";
    if ($conn->query($sql)) {
        header("Location: transaksi.php");
        exit();
    } else {
        echo "Error update: " . $conn->error;
    }
}

// HAPUS TRANSAKSI
if (isset($_POST['hapus'])) {
    $id_transaksi = intval($_POST['id_transaksi']);
    $sql = "DELETE FROM transaksi WHERE id_transaksi='$id_transaksi'";
    if ($conn->query($sql)) {
        header("Location: transaksi.php");
        exit();
    } else {
        echo "Error delete: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Transaksi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
    body.modal-open {
        overflow: hidden;
    }
    </style>
</head>
<body class="bg-light <?php if (isset($_POST['edit_form'])) echo 'modal-open'; ?>">
<div class="container mt-5 bg-white p-4 rounded shadow">
    <h2 class="mb-4">Manajemen Data Transaksi</h2>

    <!-- Form Tambah Transaksi -->
    <form method="post" class="mb-4">
        <h5 class="mb-3">Tambah Transaksi</h5>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Tanggal</label>
                <input type="date" name="tgl_transaksi" class="form-control" required>
            </div>
            <div class="form-group col-md-3">
                <label>Jumlah Terjual</label>
                <input type="number" name="jlh_terjual" class="form-control" step="1" required>
            </div>
            <div class="form-group col-md-4">
                <label>Barang</label>
                <select name="id_barang" class="form-control" required>
                    <?php
                    $barang = $conn->query("SELECT id_barang, nama_barang FROM barang");
                    while ($b = $barang->fetch_assoc()) {
                        echo "<option value='{$b['id_barang']}'>{$b['nama_barang']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-2 align-self-end">
                <button type="submit" name="tambah" class="btn btn-success btn-block">Tambah</button>
            </div>
        </div>
    </form>

    <!-- Tabel Transaksi -->
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Jumlah Terjual</th>
                <th>Barang</th>
                <th>User</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT t.*, b.nama_barang, u.nama_lengkap FROM transaksi t 
                JOIN barang b ON t.id_barang = b.id_barang 
                JOIN users u ON t.id_users = u.id_users";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td><?= $row['id_transaksi'] ?></td>
                <td><?= $row['tgl_transaksi'] ?></td>
                <td><?= $row['jlh_terjual'] ?></td>
                <td><?= $row['nama_barang'] ?></td>
                <td><?= $row['nama_lengkap'] ?></td>
                <td>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="id_transaksi" value="<?= $row['id_transaksi'] ?>">
                        <input type="hidden" name="tgl_transaksi" value="<?= $row['tgl_transaksi'] ?>">
                        <input type="hidden" name="jlh_terjual" value="<?= $row['jlh_terjual'] ?>">
                        <button type="submit" name="edit_form" class="btn btn-warning btn-sm">Edit</button>
                    </form>
                    <form method="post" class="d-inline">
                        <input type="hidden" name="id_transaksi" value="<?= $row['id_transaksi'] ?>">
                        <button type="submit" name="hapus" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus transaksi ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <a href="<?= $dashboardUrl ?>" class="btn btn-secondary mt-3">← Kembali ke Dashboard</a>
</div>

<!-- Modal Edit -->
<?php if (isset($_POST['edit_form'])): ?>
<div class="modal fade show d-block" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header">
          <h5 class="modal-title">Edit Transaksi</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" name="id_transaksi" value="<?= $_POST['id_transaksi'] ?>">
            <div class="form-group">
                <label>Tanggal Transaksi</label>
                <input type="date" name="tgl_transaksi" value="<?= $_POST['tgl_transaksi'] ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jumlah Terjual</label>
                <input type="number" name="jlh_terjual" value="<?= $_POST['jlh_terjual'] ?>" class="form-control" step="1" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
          <a href="transaksi.php" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Overlay agar background tidak bisa diklik -->
<div class="modal-backdrop fade show"></div>
<?php endif; ?>
</body>
</html>
