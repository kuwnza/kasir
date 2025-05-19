<?php
include "../koneksi.php";

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login/login.php");
    exit();
}

$produk = null;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Ambil data produk berdasarkan ID
if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];
    $sql = "SELECT * FROM produk WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produk = $result->fetch_assoc();

    if (!$produk) {
        echo "<script>alert('Produk tidak ditemukan!'); window.location.href='produk.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID tidak diberikan!'); window.location.href='produk.php';</script>";
    exit;
}

// Proses update saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) $_POST["id"];
    $barcode = $_POST["barcode"];
    $nama = $_POST["nama"];
    $kategori = (int) $_POST["kategori_id"];
    $stok = (int) $_POST["stok"];
    $harga = (int) $_POST["harga"];

    // Validasi wajib isi
    if (empty($barcode) || empty($nama) || empty($kategori) || empty($stok) || empty($harga)) {
        echo "<script>alert('Semua field wajib diisi!'); window.location.href='editproduk.php?id=$id';</script>";
        exit;
    }

    // Cek apakah ada file gambar baru
    if (!empty($_FILES["gambar"]["name"])) {
        $gambar = $_FILES["gambar"];
        $namaGambarBaru = time() . "_" . basename($gambar["name"]);
        $folderTujuan = "../uploads/";

        if (!move_uploaded_file($gambar["tmp_name"], $folderTujuan . $namaGambarBaru)) {
            echo "<script>alert('Upload gambar gagal!'); window.location.href='editproduk.php?id=$id';</script>";
            exit;
        }
    } else {
        $namaGambarBaru = $produk['gambar']; // Pakai gambar lama
    }

    // Query update
    $sql = "UPDATE produk SET barcode = ?, nama = ?, kategori_id = ?, stok = ?, harga = ?, gambar = ? WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ssiiisi", $barcode, $nama, $kategori, $stok, $harga, $namaGambarBaru, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Produk berhasil diperbarui!'); window.location.href='produk.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . $stmt->error . "'); window.location.href='editproduk.php?id=$id';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Dashboard</title>
    <style>
        /* Reset dasar */
        * {
            margin: 0;
            padding: 0;
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

        /* CARDS */
        .cards {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            justify-content: flex-start;
        }

        .card {
            background: white;
            padding: 20px;
            flex: 1;
            text-align: center;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            min-width: 150px;
        }

        .card h3 {
            margin-bottom: 5px;
            font-size: 1.2rem;
        }

        .card span {
            font-size: 0.9rem;
            color: #666;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
            max-width: 800px;
            margin: auto;
        }

        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .form-group label {
            flex: 1;
            margin-right: 10px;
        }

        .form-group input {
            flex: 2;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn.back {
            background: gray;
            color: white;
        }

        .btn.submit {
            background: #4CAF50;
            color: white;
        }

        .profile-picture {
            font-size: 80px;
            background: #eee;
            width: 50px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 10px;
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
            <li onclick="window.location.href='../dashbord.html'">🏠 Dashboard</li>
            <li onclick="window.location.href=''">📂 Kategori</li>
            <li onclick="window.location.href=''">👥 Member</li>
            <li class="active" onclick="window.location.href='produk.html'">📦 Produk</li>
            <li onclick="window.location.href=''">💰 Transaksi</li>
            <li onclick="window.location.href='diskon/diskon.html'"> Diskon</li>
            <li>🚪 Quit</li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <h1 style="display: flex; justify-content: center;">Shop Name</h1>

        <form action="editproduk.php?id=<?php echo $produk['id']; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-container">
                <h2 style="margin-bottom: 10px;">Edit Produk</h2>

                <div class="form-group">
                    <label>Barcode</label>
                    <input type="text" name="barcode" value="<?php echo $produk['barcode']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama" value="<?php echo $produk['nama']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Kategori ID</label>
                    <input type="number" name="kategori_id" value="<?php echo $produk['kategori_id']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" value="<?php echo $produk['stok']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" value="<?php echo $produk['harga']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Gambar Produk</label>
                    <input type="file" name="gambar">
                </div>

                <div class="buttons">
                    <input type="hidden" name="id" value="<?php echo $produk['id']; ?>">
                    <button type="button" class="btn back" onclick="window.location.href='produk.php'">Back</button>
                    <button type="submit" class="btn submit">Update</button>
                </div>
            </div>
        </form>


    </div>
</body>

</html>