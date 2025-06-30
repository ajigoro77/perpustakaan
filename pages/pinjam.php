<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['stat_login']) || $_SESSION['level'] !== 'anggota') {
    header("Location: ../login.php");
    exit;
}

$username = $_SESSION['username'];
$anggota = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM anggota WHERE username = '$username'"));
$id_anggota = $anggota['id'];

if (isset($_GET['id_buku'])) {
    $id_buku = $_GET['id_buku'];

    $cek_buku = mysqli_query($conn, "SELECT * FROM buku WHERE id = $id_buku");
    $buku = mysqli_fetch_assoc($cek_buku);

    if ($buku && $buku['jumlah'] > 0) {
        $tanggal_pinjam = date('Y-m-d');
        $tanggal_kembali = date('Y-m-d', strtotime('+7 days'));
        $status = 'Dipinjam';

        mysqli_query($conn, "INSERT INTO peminjam (id_anggota, id_buku, tanggal_pinjam, tanggal_kembali, status)
                             VALUES ('$id_anggota', '$id_buku', '$tanggal_pinjam', '$tanggal_kembali', '$status')");

        mysqli_query($conn, "UPDATE buku SET jumlah = jumlah - 1 WHERE id = $id_buku");

        echo "<script>alert('Buku berhasil dipinjam!'); window.location='dashboard_anggota.php';</script>";
    } else {
        echo "<script>alert('Stok buku habis!'); window.location='dashboard_anggota.php';</script>";
    }
} else {
    header("Location: dashboard_anggota.php");
}
?>
