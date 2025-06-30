<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['stat_login']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Tambah buku
if (isset($_POST['tambah'])) {
    $judul     = htmlspecialchars($_POST['judul']);
    $pengarang = htmlspecialchars($_POST['pengarang']);
    $penerbit  = htmlspecialchars($_POST['penerbit']);
    $tahun     = $_POST['tahun'];
    $jumlah    = $_POST['jumlah'];

    $gambar = '';
    if ($_FILES['gambar']['name'] != '') {
        $nama_file = time() . '_' . basename($_FILES['gambar']['name']);
        $tmp = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "../img/" . $nama_file);
        $gambar = $nama_file;
    }

    mysqli_query($conn, "INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, jumlah, gambar)
                         VALUES ('$judul', '$pengarang', '$penerbit', '$tahun', '$jumlah', '$gambar')");
    header("Location: buku.php");
    exit;
}

// Hapus buku
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $cek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM buku WHERE id = $id"));
    if ($cek['gambar'] != '') {
        @unlink("../img/" . $cek['gambar']);
    }
    mysqli_query($conn, "DELETE FROM buku WHERE id = $id");
    header("Location: buku.php");
    exit;
}

$data_buku = mysqli_query($conn, "SELECT * FROM buku ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kelola Buku - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f5f5f5;
        }

        h2, h3 {
            margin-bottom: 20px;
        }

        .menu a {
            display: inline-block;
            margin-right: 10px;
            padding: 10px 16px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .menu a:hover {
            background-color: #2980b9;
        }

        form {
            margin-top: 20px;
            margin-bottom: 40px;
        }

        form input, form button {
            padding: 8px;
            margin: 5px 10px 5px 0;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: 0.2s;
        }

        .card:hover {
            transform: scale(1.02);
        }

        .card img {
            width: 100%;
            height: 280px;
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

        .card-actions {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            border-top: 1px solid #eee;
            background-color: #fafafa;
        }

        .card-actions a {
            padding: 6px 10px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 13px;
        }

        .edit {
            background-color: #f1c40f;
        }

        .hapus {
            background-color: #e74c3c;
        }

        .edit:hover {
            background-color: #d4ac0d;
        }

        .hapus:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<h2>📚 Kelola Data Buku</h2>

<div class="menu">
    <a href="dashboard_admin.php">🏠 Dashboard</a>
    <a href="../logout.php" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
</div>

<h3>➕ Tambah Buku</h3>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="judul" placeholder="Judul" required>
    <input type="text" name="pengarang" placeholder="Pengarang" required>
    <input type="text" name="penerbit" placeholder="Penerbit" required>
    <input type="text" name="tahun" placeholder="Tahun" required>
    <input type="number" name="jumlah" placeholder="Jumlah" required>
    <input type="file" name="gambar" accept="image/*">
    <button type="submit" name="tambah">Simpan</button>
</form>

<h3>📖 Daftar Buku</h3>
<div class="grid-container">
    <?php while ($buku = mysqli_fetch_assoc($data_buku)) : ?>
        <div class="card">
            <img src="../img/<?= $buku['gambar'] ?: 'default.jpg' ?>" alt="Cover Buku">
            <div class="card-content">
                <h4><?= $buku['judul'] ?></h4>
                <p><strong>Pengarang:</strong> <?= $buku['pengarang'] ?></p>
                <p><strong>Penerbit:</strong> <?= $buku['penerbit'] ?></p>
                <p><strong>Tahun:</strong> <?= $buku['tahun_terbit'] ?></p>
                <p><strong>Jumlah:</strong> <?= $buku['jumlah'] ?></p>
            </div>
            <div class="card-actions">
                <a href="edit_buku.php?id=<?= $buku['id'] ?>" class="edit">✏️ Edit</a>
                <a href="?hapus=<?= $buku['id'] ?>" class="hapus" onclick="return confirm('Hapus buku ini?')">🗑️ Hapus</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
