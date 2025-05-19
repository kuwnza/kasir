<?php
// filepath: /var/www/local/kasir/profil.php
session_start();
include 'koneksi.php';

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login/login.php");
    exit();
}

// Ambil data admin dari session
$admin_id = $_SESSION['admin']['id'];

// Ambil data admin dari database
$sql_admin = "SELECT * FROM admin WHERE id = $admin_id";
$result_admin = $koneksi->query($sql_admin);
$admin = $result_admin->fetch_assoc();

// Hitung jumlah produk
$sql_produk = "SELECT COUNT(*) AS total_produk FROM produk";
$result_produk = $koneksi->query($sql_produk);
$total_produk = $result_produk->fetch_assoc()['total_produk'];

// Hitung jumlah transaksi
$sql_transaksi = "SELECT COUNT(*) AS total_transaksi FROM transaksi WHERE id_admin = $admin_id";
$result_transaksi = $koneksi->query($sql_transaksi);
$total_transaksi = $result_transaksi->fetch_assoc()['total_transaksi'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin</title>
    <style>
        /* Reset dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            color: #333;
        }

       /* SIDEBAR */
        .sidebar {
            width: 250px;
            background: #4CAF50;
            padding: 20px;
            color: white;
            min-height: 100vh;
        }

        .profile-picture {
            font-size: 80px;
            background: #eee;
            width: 65px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 10px;
        }

        .sidebar h2 {
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            padding: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 50px;
            transition: background 0.3s;
        }

        .sidebar li:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar .active {
            background: white;
            color: #4CAF50;
        }

        /* MAIN CONTENT */
        .main-content {
            flex-grow: 1;
            padding: 40px;
            text-align: center;
        }

        .profile-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .profile-profil {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 15px;
            object-fit: cover;
        }

        .profile-card h2 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .profile-info {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .info-box {
            text-align: center;
            flex: 1;
        }

        .info-box p {
            font-size: 14px;
            color: #555;
        }

        .info-box h3 {
            font-size: 20px;
            color: #333;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <div class="sidebar">
        <div style="display: flex; align-items: center; gap: 10px;">
            <img class="profile-picture" src="uploads/<?= htmlspecialchars($admin['gambar']); ?>" alt="Profile Image">
            <h2><?= htmlspecialchars($admin['nama']); ?></h2>
        </div>
        <ul>
            <li onclick="window.location.href='dashbord.php'">🏠 Dashboard</li>
            <li onclick="window.location.href='kategori/kategori.php'">📂 Kategori</li>
            <li onclick="window.location.href='member/member.php'">👥 Member</li>
            <li onclick="window.location.href='admin/admin.php'">👥 Admin</li>
            <li onclick="window.location.href='produk/produk.php'">📦 Produk</li>
            <li onclick="window.location.href='transaksi/transaksi.php'">💰 Transaksi</li>
            <li onclick="window.location.href='keranjang/keranjang.php'">🛒 Keranjang</li>
            <li onclick="window.location.href='logout.php'">🚪 Quit</li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <h1 style="margin-bottom: 10px;">Profil Admin</h1>
        <div class="profile-card">
            <img class="profile-profil" src="uploads/<?= htmlspecialchars($admin['gambar']); ?>" alt="Profile Picture">
            <h2><?= htmlspecialchars($admin['nama']); ?></h2>
            <div class="profile-info">
                <div class="info-box">
                    <p>Produk</p>
                    <h3><?= $total_produk; ?></h3>
                </div>
                <div class="info-box">
                    <p>Transaksi</p>
                    <h3><?= $total_transaksi; ?></h3>
                </div>
                <div class="info-box">
                    <p>Terdaftar</p>
                    <h3><?= date('d M Y', strtotime($admin['created_at'])); ?></h3>
                </div>
            </div>
        </div>
    </div>
</body>

</html>