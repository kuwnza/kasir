<?php
include "../koneksi.php";

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login/login.php");
    exit();
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $barcode     = $_POST["barcode"];
    $nama        = $_POST["nama"];
    $harga       = $_POST["harga"];
    $stok        = $_POST["stok"];
    $kategori_id = $_POST["kategori_id"];
    $gambar      = "";

    // Generate barcode if not provided
    if (empty($barcode)) {
        $barcode = uniqid();
    }

    // Cek dan upload gambar
    if (isset($_FILES["gambar"]) && $_FILES["gambar"]["error"] == 0) {
        $namaFile = $_FILES["gambar"]["name"];
        $tmpName = $_FILES["gambar"]["tmp_name"];
        $ext = pathinfo($namaFile, PATHINFO_EXTENSION);
        $namaBaru = uniqid() . '.' . $ext;
        $tujuan = "../uploads/" . $namaBaru;

        if (move_uploaded_file($tmpName, $tujuan)) {
            $gambar = $namaBaru;
        } else {
            echo "<script>alert('Gagal upload gambar');</script>";
            exit;
        }
    }

    // Simpan ke database
    $sql = "INSERT INTO produk (barcode, nama, kategori_id, stok, harga, gambar)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ssiids", $barcode, $nama, $kategori_id, $stok, $harga, $gambar);

    if ($stmt->execute()) {
        echo "<script>alert('Produk berhasil ditambahkan!'); window.location.href='produk.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan produk: " . $stmt->error . "'); history.back();</script>";
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
            margin-bottom: 20px;
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
            <li onclick="window.location.href='../diskon/diskon.html'"> Diskon</li>
            <li>🚪 Quit</li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <h1 style="display: flex; justify-content: center;">Shop Name</h1>

        <div class="form-container">
            <form action="" method="post" enctype="multipart/form-data">
                <h2 style="padding-bottom: 10px;">Buat Produk</h2>

                <div class="form-group">
                    <label>Barcode Barang</label>
                    <input type="text" name="barcode" placeholder="Masukkan Barcode..." id="qr-reader-results">
                </div>

                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" name="nama" placeholder="Masukkan Nama Barang...">
                </div>

                <div class="form-group">
                    <label>Harga</label>
                    <input type="number" name="harga" step="0.01" placeholder="IDR">
                </div>

                <div class="form-group">
                    <label>Gambar Produk</label>
                    <input type="file" name="gambar" accept="image/*">
                </div>

                <div class="form-group">
                    <label>Stok</label>
                    <input type="number" name="stok" placeholder="Masukkan jumlah...">
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <input type="number" name="kategori_id" placeholder="Masukkan ID Kategori...">
                </div>

                <div class="buttons">
                    <button class="btn back" type="button" onclick="window.location.href='produk.php'">Back</button>
                    <button class="btn submit" type="submit">Simpan Produk</button>
                </div>
            </form>

        </div>
    </div>
    </div>

    <script src="html5-qrcode.min.js"></script>
    <script>
        function docReady(fn) {
            // see if DOM is already available
            if (document.readyState === "complete" ||
                document.readyState === "interactive") {
                // call on next available tick
                setTimeout(fn, 1);
            } else {
                document.addEventListener("DOMContentLoaded", fn);
            }
        }

        docReady(function() {
            var resultContainer = document.getElementById('qr-reader-results');
            var lastResult, countResults = 0;

            function onScanSuccess(decodedText, decodedResult) {
                if (decodedText !== lastResult) {
                    ++countResults;
                    lastResult = decodedText;
                    // Handle on success condition with the decoded message.
                    // console.log(`Scan result ${decodedText}`, decodedResult);
                    resultContainer.value = decodedText;
                }
            }

            var html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", {
                    fps: 10,
                    qrbox: 250
                });
            html5QrcodeScanner.render(onScanSuccess);
        });
    </script>
</body>

</html>