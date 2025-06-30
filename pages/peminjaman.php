<?php
session_start();
include '../koneksi.php';

// Cek hak akses admin
if (!isset($_SESSION['stat_login']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Hapus data peminjaman
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM peminjam WHERE id = $id");
    header("Location: peminjaman.php");
    exit;
}

// Ambil semua data peminjaman
$data = mysqli_query($conn, "
    SELECT peminjam.*, anggota.nama AS nama_anggota, buku.judul AS judul_buku 
    FROM peminjam 
    JOIN anggota ON peminjam.id_anggota = anggota.id 
    JOIN buku ON peminjam.id_buku = buku.id 
    ORDER BY peminjam.tanggal_pinjam DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Peminjaman</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 30px;
            background-color: #f0f4f8;
        }

        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .menu a {
            margin-right: 12px;
            padding: 10px 18px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .menu a:hover {
            background-color: #2d83c2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            background-color: white;
            box-shadow: 0 3px 6px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f6f6f6;
            color: #333;
        }

        .status-dipinjam {
            background-color: #f39c12;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .status-kembali {
            background-color: #2ecc71;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .btn-aksi {
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .btn-hapus {
            background-color: crimson;
            color: white;
            margin-right: 5px;
        }

        .btn-hapus:hover {
            background-color: darkred;
        }

        .btn-kembalikan {
            background-color: green;
            color: white;
        }

        .btn-kembalikan:hover {
            background-color: darkgreen;
        }

        .btn-kembali {
            background-color: gray;
            color: white;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 5px;
            margin-top: 30px;
            display: inline-block;
        }

        .btn-kembali:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<h2>📄 Data Peminjaman Buku</h2>

<div class="menu">
    <a href="dashboard_admin.php">🏠 Dashboard</a>
    <a href="buku.php">📚 Kelola Buku</a>
    <a href="pengembalian.php">🔁 Data Pengembalian</a>
    <a href="../logout.php" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
</div>

<table>
    <thead>
        <tr>
            <th>Nama Anggota</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($data)) : ?>
        <tr>
            <td><?= htmlspecialchars($row['nama_anggota']) ?></td>
            <td><?= htmlspecialchars($row['judul_buku']) ?></td>
            <td><?= $row['tanggal_pinjam'] ?></td>
            <td><?= $row['tanggal_kembali'] ?></td>
            <td>
                <?php if ($row['status'] === 'dipinjam'): ?>
                    <span class="status-dipinjam">Dipinjam</span>
                <?php else: ?>
                    <span class="status-kembali">Dikembalikan</span>
                <?php endif; ?>
            </td>
            <td>
                <a href="?hapus=<?= $row['id'] ?>" class="btn-aksi btn-hapus" onclick="return confirm('Hapus peminjaman ini?')">Hapus</a>
                <?php if ($row['status'] === 'dipinjam'): ?>
                    <a href="proses_pengembalian.php?id=<?= $row['id'] ?>" class="btn-aksi btn-kembalikan" onclick="return confirm('Konfirmasi pengembalian buku?')">Kembalikan</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="dashboard_admin.php" class="btn-kembali">🔙 Kembali ke Dashboard</a>

</body>
</html>
