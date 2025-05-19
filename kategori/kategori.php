<?php
include "../koneksi.php"; 
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login/login.php");
    exit();
}

// Ambil data kategori dari database
$sql = "SELECT * FROM kategori";
$result = $koneksi->query($sql);
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

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .table-container h2 {
            text-align: left;
            color: #4CAF50;
        }

        .add-button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #f4f4f4;
        }

        .btn-delete {
            background: red;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-edit {
            background: gold;
            color: black;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
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
            <li onclick="window.location.href='../dashbord.php'">🏠 Dashboard</li>
            <li class="active">📂 Kategori</li>
            <li onclick="window.location.href='../member/member.php'">👥 Member</li>
            <li onclick="window.location.href='../admin/admin.php'">👥 Admin</li>
            <li onclick="window.location.href='../produk/produk.php'">📦 Produk</li>
            <li onclick="window.location.href='../transaksi/transaksi.php'">💰 Transaksi</li>
            <li onclick="window.location.href='../keranjang/keranjang.php'">🛒Keranjang</li>
            <li>🚪 Quit</li>
        </ul>
    </div>

    <div class="main-content">
        <h1 style="display: flex; justify-content: center;">Shop Name</h1>
        <div class="table-container">
            <h2 style="padding-bottom: 10px;">Kategori</h2>
            <button class="add-button" onclick="window.location.href='buatkategori.php'">➕ Add Employee</button>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                <td>" . htmlspecialchars($row["id"]) . "</td>
                <td>" . htmlspecialchars($row["kategori"]) . "</td>
                <td>
                    <a href='editkategori.php?id=" . $row["id"] . "' class='btn-edit'>Edit</a>
                    <a href='hapus.php?id=" . $row["id"] . "' class='btn-delete' onclick='return confirm(\"Yakin ingin menghapus kategori ini?\")'>Hapus</a>
                </td>
              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Tidak ada kategori tersedia.</td></tr>";
                }
                ?>


            </table>
        </div>
    </div>
</body>

</html>