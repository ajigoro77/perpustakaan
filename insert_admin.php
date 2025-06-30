<?php
include 'koneksi.php';

$nama = 'Admin Aji';
$username = 'admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);

// Cek apakah username sudah ada
$cek = mysqli_query($conn, "SELECT * FROM tb_admin WHERE username = '$username'");
if (mysqli_num_rows($cek) == 0) {
    $insert = mysqli_query($conn, "INSERT INTO tb_admin (nama_admin, username, password) VALUES ('$nama', '$username', '$password')");
    
    if ($insert) {
        echo "✅ Admin berhasil ditambahkan!";
    } else {
        echo "❌ Gagal menambahkan admin.";
    }
} else {
    echo "⚠️ Username sudah terdaftar!";
}
?>
