<?php
include 'koneksi.php';
session_start();
$pesan = "";

if (isset($_POST['submit'])) {
    // Ambil dan sanitasi input
    $nama = htmlspecialchars($_POST['nama']);
    $jurusan = htmlspecialchars($_POST['jurusan']);
    $jk = $_POST['jk'];
    $alamat = htmlspecialchars($_POST['alamat']);
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    // Validasi input kosong
    if ($nama && $jurusan && $jk && $alamat && $username && $password && $konfirmasi) {
        if ($password !== $konfirmasi) {
            $pesan = "❌ Password dan konfirmasi tidak cocok!";
        } else {
            // Cek apakah username sudah digunakan
            $cek = mysqli_query($conn, "SELECT * FROM anggota WHERE username = '$username'");
            if (mysqli_num_rows($cek) > 0) {
                $pesan = "❌ Username sudah digunakan!";
            } else {
                // Hash password
                $hash = password_hash($password, PASSWORD_DEFAULT);

                // Insert ke database
                $insert = mysqli_query($conn, "INSERT INTO anggota 
                    (nama, jurusan_anggota, jk_anggota, alamat, username, password) 
                    VALUES ('$nama', '$jurusan', '$jk', '$alamat', '$username', '$hash')");

                if ($insert) {
                    $pesan = "<span style='color:green;'>✅ Registrasi berhasil! Silakan <a href='login.php'>login</a>.</span>";
                } else {
                    $pesan = "❌ Gagal registrasi: " . mysqli_error($conn);
                }
            }
        }
    } else {
        $pesan = "❌ Semua form wajib diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Anggota</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef1f5;
            padding: 50px;
        }

        .container {
            background: white;
            padding: 30px;
            width: 400px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
        }

        h2 {
            text-align: center;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #3498db;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #2980b9;
        }

        .message {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
            color: red;
        }

        a {
            color: #3498db;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>📚 Registrasi Anggota</h2>

    <form method="POST">
        <input type="text" name="nama" placeholder="Nama Lengkap" required>
        <input type="text" name="jurusan" placeholder="Jurusan" required>

        <select name="jk" required>
            <option value="">--Pilih Jenis Kelamin--</option>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>

        <textarea name="alamat" placeholder="Alamat" required></textarea>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required>

        <button type="submit" name="submit">Daftar</button>
    </form>

    <?php if (!empty($pesan)) echo "<div class='message'>$pesan</div>"; ?>
</div>
</body>
</html>
