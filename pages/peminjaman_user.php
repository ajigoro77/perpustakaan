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

// Proses pengembalian
if (isset($_GET['kembalikan'])) {
    $id_pinjam = $_GET['kembalikan'];

    // Ambil id_buku untuk menambah jumlah
    $get = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM peminjam WHERE id = $id_pinjam AND id_anggota = $id_anggota AND status = 'dipinjam'"));
    if ($get) {
        $id_buku = $get['id_buku'];

        // Update status dan tanggal kembali
        $today = date('Y-m-d');
        mysqli_query($conn, "UPDATE peminjam SET status = 'dikembalikan', tanggal_kembali = '$today' WHERE id = $id_pinjam");

        // Tambahkan jumlah buku kembali
        mysqli_query($conn, "UPDATE buku SET jumlah = jumlah + 1 WHERE id = $id_buku");
    }

    header("Location: peminjaman_user.php");
    exit;
}

// Ambil data peminjaman milik anggota ini
$data = mysqli_query($conn, "
    SELECT peminjam.*, buku.judul AS judul_buku 
    FROM peminjam 
    JOIN buku ON peminjam.id_buku = buku.id 
    WHERE peminjam.id_anggota = $id_anggota 
    ORDER BY peminjam.tanggal_pinjam DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peminjaman Saya</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 30px;
            background: #f2f2f2;
        }

        h2 {
            margin-bottom: 20px;
        }

        .menu a {
            margin-right: 10px;
            text-decoration: none;
            padding: 10px 18px;
            background-color: #36a2eb;
            color: white;
            border-radius: 5px;
        }

        .menu a:hover {
            background-color: #2c8acb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            margin-top: 20px;
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
            background-color: green;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-kembali:hover {
            background-color: darkgreen;
        }

        .done {
            font-style: italic;
            color: gray;
        }
    </style>
</head>
<body>

<h2>📄 Peminjaman Saya</h2>

<div class="menu">
    <a href="dashboard_anggota.php">🏠 Dashboard</a>
    <a href="../logout.php" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
</div>

<table>
    <thead>
        <tr>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($data) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($data)) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['judul_buku']) ?></td>
                    <td><?= $row['tanggal_pinjam'] ?></td>
                    <td><?= $row['tanggal_kembali'] ?: '-' ?></td>
                    <td><?= ucfirst($row['status']) ?></td>
                    <td>
                        <?php if ($row['status'] === 'dipinjam') : ?>
                            <a href="?kembalikan=<?= $row['id'] ?>" class="btn-kembali" onclick="return confirm('Kembalikan buku ini?')">Kembalikan</a>
                        <?php else: ?>
                            <span class="done">✔ Selesai</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="kosong">📭 Belum ada buku yang dipinjam</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
