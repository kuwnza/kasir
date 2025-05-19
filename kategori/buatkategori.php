<?php
session_start();
include "../koneksi.php"; 
if (!isset($_SESSION['admin'])) {
    header("Location: ../login/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kategori = $_POST["kategori"];

    // Simpan ke database dengan prepared statement
    $sql = "INSERT INTO kategori (kategori) VALUES (?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $kategori);

    if ($stmt->execute()) {
        echo "<script>alert('Kategori berhasil ditambahkan!'); window.location.href='kategori.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan kategori: " . $stmt->error . "');</script>";
    }

    $stmt->close();
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
            padding: 20px;
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
            <li class="active">📂 Kategori</li>
            <li onclick="window.location.href='../member/member.html'">👥 Member</li>
            <li onclick="window.location.href=''">📦 Produk</li>
            <li onclick="window.location.href=''">💰 Transaksi</li>
            <li onclick="window.location.href='../diskon/diskon.html'">Diskon</li>
            <li>🚪 Quit</li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <h1 style="display: flex; justify-content: center;">Shop Name</h1>
        <div class="form-container">
            <h2 style="margin-bottom: 10px;">Tambah Kategori</h2>
            <form method="POST" action="">
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" placeholder="Tambahkan Kategori..." required>
            </div>
            <div class="buttons">
                <button class="btn back" onclick="window.location.href='kategori.php'">Back</button>
                <button type="submit" class="btn submit">Tambah</button>
            </div>
            </form>
        </div>
    </div>
</body>

</html>