<?php
session_start();
include '../koneksi.php';

// Cek login
if (!isset($_SESSION['stat_login']) || $_SESSION['stat_login'] !== true) {
    header("Location: ../login.php");
    exit;
}

// Proses tambah data
if (isset($_POST['simpan'])) {
    $nama     = htmlspecialchars($_POST['nama']);
    $jurusan  = htmlspecialchars($_POST['jurusan']);
    $jk       = $_POST['jk'];
    $alamat   = htmlspecialchars($_POST['alamat']);

    $simpan = mysqli_query($conn, "INSERT INTO anggota (nama, jurusan_anggota, jk_anggota, alamat)
                                   VALUES ('$nama', '$jurusan', '$jk', '$alamat')");

    if ($simpan) {
        $pesan = "Data berhasil ditambahkan!";
    } else {
        $pesan = "Gagal menambahkan data.";
    }
}

// Proses hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM anggota WHERE id = $id");
    header("Location: anggota.php");
    exit;
}

// Ambil data anggota
$data_anggota = mysqli_query($conn, "SELECT * FROM anggota ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Anggota</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        h2 { margin-bottom: 10px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        form { margin-top: 20px; }
        input, textarea, select { padding: 5px; width: 300px; margin-bottom: 10px; }
        .btn { padding: 5px 15px; }
        .pesan { color: green; }
    </style>
</head>
<body>

    <h2>Data Anggota</h2>
    <?php if (isset($pesan)) echo "<p class='pesan'>$pesan</p>"; ?>

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Jurusan</th>
            <th>Jenis Kelamin</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while($a = mysqli_fetch_array($data_anggota)) { ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $a['nama'] ?></td>
            <td><?= $a['jurusan_anggota'] ?></td>
            <td><?= $a['jk_anggota'] ?></td>
            <td><?= $a['alamat'] ?></td>
            <td><a href="anggota.php?hapus=<?= $a['id'] ?>" onclick="return confirm('Yakin?')">Hapus</a></td>
        </tr>
        <?php } ?>
    </table>

    <h3>Tambah Anggota</h3>
    <form method="POST">
        <input type="text" name="nama" placeholder="Nama" required><br>
        <select name="jurusan" required>
            <option value="">-- Pilih Jurusan --</option>
            <option value="Sistem Informasi">Sistem Informasi</option>
            <option value="Teknik Informatika">Teknik Informatika</option>
            <option value="Manajemen Informatika">Manajemen Informatika</option>
        </select><br>
        <select name="jk" required>
            <option value="">-- Jenis Kelamin --</option>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select><br>
        <textarea name="alamat" placeholder="Alamat lengkap" rows="3" required></textarea><br>
        <button class="btn" name="simpan">Simpan</button>
    </form>

</body>
</html>
