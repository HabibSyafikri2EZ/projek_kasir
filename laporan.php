<?php
session_start();
include 'db.php';

// Cek login
if (!isset($_SESSION['username']) || !isset($_SESSION['login_status']) || $_SESSION['login_status'] !== 'true') {
    $_SESSION['error_msg'] = "Silakan login terlebih dahulu.";
    header("Location: login.php");
    exit();
}

// Inisialisasi filter
$filter = '';
$tanggal_awal = '';
$tanggal_akhir = '';

if (isset($_POST['submit'])) {
    $tanggal_awal = $_POST['tanggal_awal'];
    $tanggal_akhir = $_POST['tanggal_akhir'];

    if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
        $filter = "WHERE tgl_transaksi IS NOT NULL AND tgl_transaksi BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
    }
}

// Query ambil data transaksi dengan join, sesuaikan nama kolom harga_barang
$sql = "SELECT transaksi.id_transaksi, users.nama_lengkap, barang.nama_barang, transaksi.tgl_transaksi, transaksi.jlh_terjual, barang.harga_barang 
        FROM transaksi 
        JOIN users ON transaksi.id_users = users.id_users 
        JOIN barang ON transaksi.id_barang = barang.id_barang 
        $filter 
        ORDER BY transaksi.tgl_transaksi ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body style="background:#f7f7f7;">
<div class="container mt-5 bg-white p-4 rounded shadow">
    <h1 class="text-center mb-4">Laporan Transaksi</h1>

    <!-- Form Filter -->
    <form method="POST" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-5">
                <label for="tanggal_awal">Dari</label>
                <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" required
                       value="<?= htmlspecialchars($tanggal_awal) ?>">
            </div>
            <div class="form-group col-md-5">
                <label for="tanggal_akhir">Sampai</label>
                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required
                       value="<?= htmlspecialchars($tanggal_akhir) ?>">
            </div>
            <div class="form-group col-md-2 align-self-end">
                <button type="submit" name="submit" class="btn btn-primary btn-block"><i class="fas fa-sync"></i> Filter</button>
            </div>
        </div>
    </form>

    <!-- Tabel Laporan -->
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
        <tr>
            <th>ID Transaksi</th>
            <th>Nama User</th>
            <th>Nama Barang</th>
            <th>Tanggal Transaksi</th>
            <th>Jumlah Terjual</th>
            <th>Harga Barang</th>
            <th>Total Harga</th>
        </tr>
        </thead>
        <tbody>
        <?php 
        $totalKeseluruhan = 0;
        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
                $totalHarga = $row['harga_barang'] * $row['jlh_terjual'];
                $totalKeseluruhan += $totalHarga;
        ?>
            <tr>
                <td><?= $row['id_transaksi'] ?></td>
                <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= $row['tgl_transaksi'] ?></td>
                <td><?= $row['jlh_terjual'] ?></td>
                <td>Rp <?= number_format($row['harga_barang'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($totalHarga, 0, ',', '.') ?></td>
            </tr>
        <?php 
            endwhile;
        else: ?>
            <tr><td colspan="7" class="text-center">Tidak ada data transaksi.</td></tr>
        <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-right">Total Harga Keseluruhan</th>
                <th>Rp <?= number_format($totalKeseluruhan, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="text-center mt-3">
        <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
    </div>
</div>

<!-- FontAwesome & Bootstrap JS (optional) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
