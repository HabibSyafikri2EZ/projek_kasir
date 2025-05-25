<?php
// Mulai sesi
session_start();

// Hancurkan semua data sesi
session_destroy();

// Arahkan pengguna kembali ke halaman login
header('Location: login.php');
exit;
?>