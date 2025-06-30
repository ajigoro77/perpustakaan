<?php
session_start();

// Redirect ke dashboard jika sudah login
if (isset($_SESSION['stat_login']) && $_SESSION['stat_login'] === true) {
    if ($_SESSION['level'] === 'admin') {
        header("Location: dashboard_admin.php");
        exit;
    } elseif ($_SESSION['level'] === 'anggota') {
        header("Location: dashboard_anggota.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beranda Sistem Perpustakaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        p {
            color: #555;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 10px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .info {
            margin-top: 40px;
            color: #777;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <h1>Selamat Datang di Sistem Perpustakaan</h1>
    <p>Silakan login atau daftar sebagai anggota untuk mengakses sistem.</p>

    <div>
        <a href="login.php" class="btn">🔐 Login</a>
        <a href="register.php" class="btn">📝 Daftar Anggota</a>
    </div>

    <div class="info">
        <p><strong>Catatan:</strong> Admin tidak perlu mendaftar. Silakan login dengan akun yang sudah tersedia di database.</p>
    </div>

</body>
</html>
