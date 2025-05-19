<?php
include '../koneksi.php';

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login/login.php");
    exit();
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Ambil data produk dari database
$query = "SELECT * FROM produk WHERE stok > 0";
$result = $koneksi->query($query);

// Proses tambah ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produk_id'])) {
    $produk_id = $_POST['produk_id'];
    $jumlah = 1; // Jumlah default yang ditambahkan ke keranjang

    // Periksa apakah produk sudah ada di keranjang
    $cekKeranjang = $koneksi->query("SELECT * FROM keranjang WHERE produk_id = $produk_id");
    if ($cekKeranjang->num_rows > 0) {
        // Jika produk sudah ada, tambahkan jumlahnya
        $koneksi->query("UPDATE keranjang SET jumlah = jumlah + $jumlah WHERE produk_id = $produk_id");
    } else {
        // Jika produk belum ada, tambahkan sebagai entri baru
        $koneksi->query("INSERT INTO keranjang (produk_id, jumlah) VALUES ($produk_id, $jumlah)");
    }

    // Kurangi stok produk
    $koneksi->query("UPDATE produk SET stok = stok - $jumlah WHERE id = $produk_id");

    echo '<script>alert("Produk berhasil ditambahkan ke keranjang!"); window.location.href="list-produk.php";</script>';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk</title>
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
            flex: 1;
            padding: 20px;
        }

        .main-content h1 {
            margin-bottom: 20px;
        }

        /* MAIN CONTENT */
        .content {
            flex: 1;
            padding: 20px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            padding: 20px;
            text-align: center;
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .card h3 {
            margin: 10px 0;
            font-size: 1.5rem;
            color: #333;
        }

        .card p {
            margin: 5px 0;
            color: #666;
        }

        .card button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }

        .card button:hover {
            background: #45a049;
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
            <li class="active">📦 Produk</li>
            <li onclick="window.location.href='../transaksi/transaksi.php'">💰 Transaksi</li>
            <li onclick="window.location.href='../keranjang/keranjang.php'">🛒 Keranjang</li>
            <li onclick="window.location.href='../logout/logout.php'">🚪 Quit</li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content">
        <h1 style="text-align: center; margin-bottom: 20px;">Daftar Produk</h1>
        <div class="container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <img src="../uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="Gambar Produk">
                    <h3><?php echo htmlspecialchars($row['nama']); ?></h3>
                    <p>Harga: Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                    <p>Stok: <?php echo htmlspecialchars($row['stok']); ?></p>
                    <form method="POST">
                        <input type="hidden" name="produk_id" value="<?php echo $row['id']; ?>">
                        <button type="submit">Tambah Produk</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <button onclick="window.location.href='../keranjang/keranjang.php'" style="background: #4CAF50; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold;">
                Kembali ke Keranjang
            </button>
        </div>
    </div>
</body>
</html>