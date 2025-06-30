<?php
session_start();
include '../koneksi.php';

// Cek apakah user admin
if (!isset($_SESSION['stat_login']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Cek ID peminjaman
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID tidak valid!";
    exit;
}

$id_peminjaman = (int)$_GET['id'];

// Ambil data peminjaman
$peminjaman = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM peminjam WHERE id = $id_peminjaman"));

if (!$peminjaman) {
    echo "Data peminjaman tidak ditemukan.";
    exit;
}

if ($peminjaman['status'] === 'dikembalikan') {
    echo "Buku sudah dikembalikan sebelumnya.";
    exit;
}

// Hitung selisih hari untuk denda
$tgl_pinjam = new DateTime($peminjaman['tanggal_pinjam']);
$tgl_kembali = new DateTime(); // hari ini
$selisih_hari = $tgl_pinjam->diff($tgl_kembali)->days;

$denda = 0;
if ($selisih_hari > 3) {
    $denda_per_hari = 1000;
    $denda = ($selisih_hari - 3) * $denda_per_hari;
}

// Simpan ke tabel pengembalian
mysqli_query($conn, "
    INSERT INTO pengembalian (id_peminjaman, tgl_pengembalian, denda)
    VALUES ($id_peminjaman, NOW(), $denda)
");

// Update status peminjam
mysqli_query($conn, "
    UPDATE peminjam SET status = 'dikembalikan' WHERE id = $id_peminjaman
");

// Tambah stok buku kembali
$id_buku = $peminjaman['id_buku'];
mysqli_query($conn, "
    UPDATE buku SET jumlah = jumlah + 1 WHERE id = $id_buku
");

header("Location: peminjaman.php");
exit;
?>
