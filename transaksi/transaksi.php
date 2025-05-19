<?php
include "../koneksi.php";

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login/login.php");
    exit();
}
// Ambil data produk dari database
$sql = "SELECT * FROM produk";
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

        .btn-struk {
            background: blue;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
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
        .info-container {
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
        margin: 10px 0;
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
            <li onclick="window.location.href='../diskon/diskon.php'"> Diskon</li>
            <li onclick="window.location.href='../logout.php'">🚪 Quit</li>
        </ul>
    </div>
    <!-- width="50" style="padding-right: 10px;" -->
    <div class="main-content">
        <h1 style="display: flex; justify-content: center;">Shop Name</h1>
        <div class="table-container">
            <h2 style="padding-bottom: 10px;">Transaksi</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>ID Member</th>
                        <th>ID Admin</th>
                        <th>Metode Pembayaran</th>
                        <th>Uang Masuk</th>
                        <th>Uang Keluar</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal Transaksi</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil data transaksi dari database
                    $sql_transaksi = "SELECT * FROM transaksi";
                    $result_transaksi = $koneksi->query($sql_transaksi);

                    if ($result_transaksi->num_rows > 0) {
                        while ($row = $result_transaksi->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . ($row['id_member'] ? $row['id_member'] : 'Guest') . "</td>";
                            echo "<td>" . $row['id_admin'] . "</td>";
                            echo "<td>" . $row['metode_pembayaran'] . "</td>";
                            echo "<td>Rp. " . number_format($row['uang_masuk'], 0, ',', '.') . "</td>";
                            echo "<td>Rp. " . number_format($row['uang_keluar'], 0, ',', '.') . "</td>";
                            echo "<td>Rp. " . number_format($row['total'], 0, ',', '.') . "</td>";
                            echo "<td>" . ucfirst($row['status']) . "</td>";
                            echo "<td>" . $row['tanggal_transaksi'] . "</td>";
                            echo "<td>
                                    <button class='btn-edit' onclick=\"window.location.href='detail_transaksi.php?id=" . $row['id'] . "'\">Detail</button>
                                    <button class='btn-struk' onclick=\"window.location.href='struk.php?id=" . $row['id'] . "'\">Lihat Struk</button>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10'>Tidak ada data transaksi</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>