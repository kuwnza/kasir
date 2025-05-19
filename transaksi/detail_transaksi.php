<?php
// filepath: /var/www/local/kasir/transaksi/detail_transaksi.php
include '../koneksi.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login/login.php");
    exit();
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Ambil ID transaksi dari URL
$id_transaksi = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data transaksi
$sql_transaksi = "SELECT transaksi.*, 
                         member.nama AS nama_member, 
                         member.email AS email_member 
                  FROM transaksi 
                  LEFT JOIN member ON transaksi.id_member = member.id 
                  WHERE transaksi.id = $id_transaksi";
$result_transaksi = $koneksi->query($sql_transaksi);
$transaksi = $result_transaksi->fetch_assoc();

// Ambil detail produk dari transaksi
$sql_detail = "SELECT * FROM detail_transaksi WHERE transaksi_id = $id_transaksi";
$result_detail = $koneksi->query($sql_detail);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background-color: #f4f4f4;
        }

        /* SIDEBAR */
        .sidebar {
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
        .content {
            flex: 1;
            padding: 20px;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .info-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        info-container {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
        color: #333;
    }

    .info-container h2 {
        font-size: 1.5rem;
        color: #4CAF50;
        margin-bottom: 15px;
        border-bottom: 2px solid #4CAF50;
        padding-bottom: 5px;
    }

    .info-container p {
        margin: 5px 0;
        font-size: 1rem;
        line-height: 1.6;
    }

    .info-container p span {
        font-weight: bold;
        color: #555;
    }

    .info-container .highlight {
        font-size: 1.2rem;
        font-weight: bold;
        color: #4CAF50;
    }

    .info-container .total {
        font-size: 1.3rem;
        font-weight: bold;
        text-align: right;
        margin-top: 15px;
        color: #333;
    }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #f4f4f4;
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <div class="sidebar">
         <a href="profil.php" style="text-decoration: none; color: inherit;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <img class="profile-picture" src="../uploads/<?= htmlspecialchars($_SESSION['admin']['gambar']); ?>" alt="Profile Image">
                <h2><?= htmlspecialchars($_SESSION['admin']['nama']); ?></h2>
            </div>
        </a>
        <ul>
            <li onclick="window.location.href='../dashbord.php'">🏠 Dashboard</li>
            <li onclick="window.location.href='../kategori/kategori.php'">📂 Kategori</li>
            <li onclick="window.location.href='../member/member.php'">👥 Member</li>
            <li onclick="window.location.href='../admin/admin.php'">👥 Admin</li>
            <li onclick="window.location.href='../produk/produk.php'">📦 Produk</li>
            <li class="active">💰 Transaksi</li>
            <li onclick="window.location.href='../keranjang/keranjang.php'">🛒 Keranjang</li>
            <li onclick="window.location.href='../logout/logout.php'">🚪 Quit</li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content">
        <h1 style="margin-bottom: 20px;">Detail Transaksi</h1>
        <div class="info-container" style="margin-bottom: 20px;">
            <h2>Informasi Transaksi</h2>
            <p><strong>ID Transaksi:</strong> <?php echo $transaksi['id']; ?></p>
            <p><strong>Nama Member:</strong> <?php echo $transaksi['nama_member'] ? $transaksi['nama_member'] : 'Guest'; ?></p>
            <p><strong>Email Member:</strong> <?php echo $transaksi['email_member'] ? $transaksi['email_member'] : '-'; ?></p>
            <p><strong>Metode Pembayaran:</strong> <?php echo $transaksi['metode_pembayaran']; ?></p>
            <p><strong>Uang Masuk:</strong> Rp. <?php echo number_format($transaksi['uang_masuk'], 0, ',', '.'); ?></p>
            <p><strong>Uang Keluar:</strong> Rp. <?php echo number_format($transaksi['uang_keluar'], 0, ',', '.'); ?></p>
            <p><strong>Total:</strong> Rp. <?php echo number_format($transaksi['total'], 0, ',', '.'); ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($transaksi['status']); ?></p>
            <p><strong>Tanggal Transaksi:</strong> <?php echo $transaksi['tanggal_transaksi']; ?></p>
        </div>

        <div class="table-container">
            <h2>Detail Produk</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_detail->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['nama_produk']; ?></td>
                            <td>Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td><?php echo $row['jumlah']; ?></td>
                            <td>Rp. <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>