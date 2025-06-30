<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['stat_login']) || $_SESSION['stat_login'] !== true || $_SESSION['level'] !== 'anggota') {
    header("Location: ../login.php");
    exit;
}

$username = $_SESSION['username'];
$anggota = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM anggota WHERE username = '$username'"));
$id_anggota = $anggota['id'];

// Pencarian
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
$query = "SELECT * FROM buku";
if (!empty($cari)) {
    $query .= " WHERE judul LIKE '%$cari%' OR pengarang LIKE '%$cari%' OR penerbit LIKE '%$cari%'";
}
$buku = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Anggota</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f4f4f4;
        }

        h2 {
            margin-bottom: 20px;
        }

        .menu {
            margin-bottom: 25px;
        }

        .menu a {
            text-decoration: none;
            padding: 10px 18px;
            background-color: #36a2eb;
            color: white;
            border-radius: 5px;
            margin-right: 10px;
        }

        .menu a:hover {
            background-color: #2c8acb;
        }

        form {
            margin-bottom: 30px;
        }

        input[type="text"] {
            padding: 8px;
            width: 300px;
        }

        input[type="submit"] {
            padding: 8px 16px;
            background-color: #36a2eb;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #2c8acb;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: 0.2s;
            display: flex;
            flex-direction: column;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .card img {
            width: 100%;
            height: 260px;
            object-fit: cover;
        }

        .card-content {
            padding: 15px;
            flex: 1;
        }

        .card-content h4 {
            margin: 0 0 10px;
            font-size: 18px;
        }

        .card-content p {
            margin: 4px 0;
            font-size: 14px;
            color: #444;
        }

        .btn-pinjam {
            background-color: green;
            color: white;
            padding: 8px 10px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .btn-pinjam:hover {
            background-color: darkgreen;
        }

        .no-stock {
            color: red;
            font-style: italic;
            margin-top: 10px;
        }

        .not-found {
            color: red;
            font-style: italic;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<h2>👋 Halo, <?= htmlspecialchars($anggota['nama']) ?>!</h2>

<div class="menu">
    <a href="dashboard_anggota.php">🏠 Dashboard</a>
    <a href="peminjaman_user.php">📄 Peminjaman Saya</a>
    <a href="../logout.php" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
</div>

<form method="GET">
    <input type="text" name="cari" placeholder="Cari buku berdasarkan judul / pengarang / penerbit..." value="<?= htmlspecialchars($cari) ?>">
    <input type="submit" value="Cari">
</form>

<?php if (mysqli_num_rows($buku) > 0): ?>
<div class="grid-container">
    <?php while ($row = mysqli_fetch_assoc($buku)) : ?>
        <div class="card">
            <img src="../img/<?= $row['gambar'] ?: 'default.jpg' ?>" alt="Cover Buku">
            <div class="card-content">
                <h4><?= htmlspecialchars($row['judul']) ?></h4>
                <p><strong>Pengarang:</strong> <?= htmlspecialchars($row['pengarang']) ?></p>
                <p><strong>Penerbit:</strong> <?= htmlspecialchars($row['penerbit']) ?></p>
                <p><strong>Tahun:</strong> <?= $row['tahun_terbit'] ?></p>
                <p><strong>Stok:</strong> <?= $row['jumlah'] ?></p>
                <?php if ($row['jumlah'] > 0): ?>
                    <a href="pinjam.php?id_buku=<?= $row['id'] ?>" class="btn-pinjam" onclick="return confirm('Yakin ingin meminjam buku ini?')">📚 Pinjam</a>
                <?php else: ?>
                    <p class="no-stock">Stok habis</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<?php else: ?>
    <p class="not-found">📭 Buku tidak ditemukan</p>
<?php endif; ?>

</body>
</html>
