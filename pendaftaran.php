<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <!-- Memasukkan Bootstrap CSS -->
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
    }

    .btn {
        width: 150px;
        margin-top: 5px;
        font-family: sans-serif;
        font-size: 16px;
        border: 2px solid white;
        text-align: center;
        border-radius: 10px;
    }

    .btn:hover {
        color: white;
        border: 2px solid gray;
        border-radius: 10px;
    }
</style>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-3">
                    <div class="card-header text-center">
                        <h3><b>Pendaftaran</b></h3>
                    </div>
                    <div class="card-body">
                        <form action="proses_pendaftaran.php" method="post">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" placeholder="Masukan nama lengkap anda">
                            </div>
                            <div class="form-group">
                                <label for="jabatan">Posisi</label>
                                <select id="jabatan" name="jabatan" class="form-control">
                                    <option value="admin">Admin</option>
                                    <option value="pimpinan">Pimpinan</option>
                                    <option value="karyawan">Karyawan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
                                    <option value="laki">Laki-Laki</option>
                                    <option value="perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan Username">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan Password">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mx-auto">Daftar</button>
                            </div>
                        </form>
                        
                        <!-- Tombol Kembali -->
                        <div class="text-center mt-3">
                            <a href="dashboard.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Memasukkan Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
