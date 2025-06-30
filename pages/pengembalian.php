<?php
session_start();
include '../koneksi.php';

// Cek hak akses admin
if (!isset($_SESSION['stat_login']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Ambil data pengembalian lengkap
$data = mysqli_query($conn, "
    SELECT pengembalian.*, 
           peminjam.tanggal_pinjam, 
           anggota.nama AS nama_anggota, 
           buku.judul AS judul_buku 
    FROM pengembalian
    JOIN peminjam ON pengembalian.id_peminjaman = peminjam.id
    JOIN anggota ON peminjam.id_anggota = anggota.id
    JOIN buku ON peminjam.id_buku = buku.id
    ORDER BY pengembalian.tgl_pengembalian DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pengembalian</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 30px;
            background: #f4f4f4;
        }

        h2 {
            margin-bottom: 20px;
        }

        .menu a {
            margin-right: 10px;
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .menu a:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            margin-top: 25px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #eee;
        }

        .kosong {
            text-align: center;
            color: red;
            font-style: italic;
        }

        .btn-kembali {
            margin-top: 25px;
            display: inline-block;
            padding: 10px 18px;
            background-color: gray;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-kembali:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<h2>📥 Data Pengembalian Buku</h2>

<div class="menu">
    <a href="dashboard_admin.php">🏠 Dashboard</a>
    <a href="peminjaman.php">📄 Data Peminjaman</a>
    <a href="buku.php">📚 Kelola Buku</a>
    <a href="../logout.php" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
</div>

<table>
    <thead>
        <tr>
            <th>Nama Anggota</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Denda</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($data) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($data)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_anggota']) ?></td>
                    <td><?= htmlspecialchars($row['judul_buku']) ?></td>
                    <td><?= $row['tanggal_pinjam'] ?></td>
                    <td><?= $row['tgl_pengembalian'] ?></td>
                    <td><?= $row['denda'] > 0 ? 'Rp ' . number_format($row['denda'], 0, ',', '.') : 'Tidak ada' ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="kosong">📭 Belum ada pengembalian</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<a href="dashboard_admin.php" class="btn-kembali">🔙 Kembali ke Dashboard</a>

</body>
</html>
