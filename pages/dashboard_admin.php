<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['stat_login']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Query jumlah data
$jumlah_buku = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM buku"));
$jumlah_anggota = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM anggota"));
$jumlah_peminjaman = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM peminjam"));
$jumlah_pengembalian = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pengembalian"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4f8;
        }

        .navbar {
            background-color: #2d3e50;
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
        }

        .menu a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
        }

        .menu a:hover {
            text-decoration: underline;
        }

        .container {
            padding: 40px;
        }

        .welcome {
            font-size: 22px;
            margin-bottom: 30px;
            color: #333;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        .card h3 {
            margin: 0;
            color: #2d3e50;
        }

        .card p {
            font-size: 32px;
            margin-top: 10px;
            color: #3498db;
        }

        .card-icon {
            font-size: 40px;
            color: #555;
            margin-bottom: 10px;
        }

        .logout {
            background-color: crimson;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
        }

        .logout:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h2>📚 Sistem Perpustakaan - Admin</h2>
    <div class="menu">
        <a href="buku.php">Kelola Buku</a>
        <a href="peminjaman.php">Peminjaman</a>
        <a href="pengembalian.php">Pengembalian</a>
        <a href="../logout.php" class="logout" onclick="return confirm('Yakin ingin logout?')">Logout</a>
    </div>
</div>

<div class="container">
    <div class="welcome">
        👋 Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!
    </div>

    <div class="cards">
        <div class="card">
            <div class="card-icon">📘</div>
            <h3>Jumlah Buku</h3>
            <p><?= $jumlah_buku ?></p>
        </div>

        <div class="card">
            <div class="card-icon">👥</div>
            <h3>Jumlah Anggota</h3>
            <p><?= $jumlah_anggota ?></p>
        </div>

        <div class="card">
            <div class="card-icon">📄</div>
            <h3>Total Peminjaman</h3>
            <p><?= $jumlah_peminjaman ?></p>
        </div>

        <div class="card">
            <div class="card-icon">🔁</div>
            <h3>Total Pengembalian</h3>
            <p><?= $jumlah_pengembalian ?></p>
        </div>
    </div>
</div>

</body>
</html>
