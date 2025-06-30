<?php
// Konfigurasi koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_perpustakaan";

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Optional: aktifkan UTF-8 jika kamu pakai karakter Indonesia
mysqli_set_charset($conn, "utf8");
?>
