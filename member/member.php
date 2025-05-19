<?php
include "../koneksi.php";
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login/login.php");
    exit();
}

$sql = "SELECT * FROM member";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Dashboard</title>
    <style>
        * { margin: 0; padding: 0; }
        body {
            display: flex;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            color: #333;
        }

        .sidebar {
            width: 250px;
            background: #4CAF50;
            padding: 20px;
            color: white;
            min-height: 100vh;
        }

        .sidebar h2 { margin-bottom: 20px; }
        .sidebar ul { list-style: none; }
        .sidebar li {
            padding: 30px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 50px;
            transition: background 0.3s;
        }
        .sidebar li:hover { background: rgba(255, 255, 255, 0.2); }
        .sidebar .active { background: white; color: #4CAF50; }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .main-content h1 { margin-bottom: 20px; }

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

        .card h3 { margin-bottom: 5px; font-size: 1.2rem; }
        .card span { font-size: 0.9rem; color: #666; }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .table-container h2 { text-align: left; color: #4CAF50; }

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

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th { background: #f4f4f4; }

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
            width: 65px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 10px;
        }

        a {
            text-decoration: none;
            color: inherit;
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
            <li class="active">👥 Member</li>
            <li onclick="window.location.href='../admin/admin.php'">👥 Admin</li>
            <li onclick="window.location.href='../produk/produk.php'">📦 Produk</li>
            <li onclick="window.location.href=''">💰 Transaksi</li>
            <li onclick="window.location.href='../diskon/diskon.php'"> Diskon</li>
            <li onclick="window.location.href='../logout.php'">🚪 Quit</li>
        </ul>
    </div>

    <div class="main-content">
        <h1 style="display: flex; justify-content: center;">Shop Name</h1>

        <div class="table-container">
            <h2 style="padding-bottom: 10px;">Member</h2>
            <button class="add-button" onclick="window.location.href='tambahmember.php'">➕ Add Employee</button>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>No_Tlpn</th>
                        <th>Poin</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row["id"] ?></td>
                                <td><?= $row["nama"] ?></td>
                                <td><?= $row["no_tlpn"] ?></td>
                                <td><?= $row["point"] ?></td>
                                <td><?= $row["email"] ?></td>
                                <td>
                                    <?php if ($row['status'] == 'aktif') : ?>
                                        <span style="color: green;">Aktif</span>
                                    <?php else : ?>
                                        <span style="color: red;">Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="editmember.php?id=<?= $row["id"] ?>" class="btn-edit">Edit</a>
                                    <a href="hapus.php?id=<?= $row["id"] ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr><td colspan="7">Tidak ada data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
