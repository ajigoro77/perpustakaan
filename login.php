<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'koneksi.php';

$error = "";

if (isset($_POST['submit'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $level = $_POST['level']; // admin atau anggota

    if ($level == 'admin') {
        $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");
    } else {
        $query = mysqli_query($conn, "SELECT * FROM anggota WHERE username='$username'");
    }

    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $password_valid = false;

        if ($level == 'admin') {
            $password_valid = ($password === $data['password']); // admin = plain text
        } else {
            $password_valid = password_verify($password, $data['password']); // anggota = hash
        }

        if ($password_valid) {
            $_SESSION['stat_login'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['level'] = $level;

            if ($level == 'admin') {
                header("Location: pages/dashboard_admin.php");
            } else {
                header("Location: pages/dashboard_anggota.php");
            }
            exit;
        } else {
            $error = "❌ Password salah!";
        }
    } else {
        $error = "❌ Akun tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Perpustakaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef1f5;
            text-align: center;
            padding: 80px;
        }

        form {
            display: inline-block;
            padding: 30px 50px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
        }

        input, select {
            padding: 10px;
            width: 250px;
            margin: 10px 0;
            font-size: 15px;
        }

        input[type=submit] {
            background-color: #3498db;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type=submit]:hover {
            background-color: #2980b9;
        }

        .error {
            color: red;
            margin-top: 15px;
        }

        .register {
            margin-top: 20px;
        }

        .register a {
            text-decoration: none;
            color: #3498db;
        }

        .register a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h2>🔐 Login Sistem Perpustakaan</h2>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>

        <select name="level" required>
            <option value="">-- Pilih Level --</option>
            <option value="admin">Admin</option>
            <option value="anggota">Anggota</option>
        </select><br>

        <input type="submit" name="submit" value="Login">
    </form>

    <?php if (!empty($error)) : ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <div class="register">
        Belum punya akun anggota? <a href="register_anggota.php">Daftar di sini</a>
    </div>

</body>
</html>
