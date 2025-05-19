<?php
include "../koneksi.php";

session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login/login.php");
    exit();
}

// Ambil data produk dari database
$sql = "SELECT produk.*, kategori.kategori AS kategori_nama FROM produk 
    LEFT JOIN kategori ON produk.kategori_id = kategori.id";
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
            width: 65px;
            height: 60px;
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
            <li onclick="window.location.href=''">📂 Kategori</li>
            <li onclick="window.location.href='../member/member.php'">👥 Member</li>
            <li onclick="window.location.href='../admin/admin.php'">👥 Admin</li>
            <li class="active">📦 Produk</li>
            <li onclick="window.location.href=''">💰 Transaksi</li>
            <li onclick="window.location.href='../keranjang/keranjang.php'">🛒Keranjang</li>
            <li>🚪 Quit</li>
        </ul>
    </div>
<!-- width="50" style="padding-right: 10px;" -->
    <div class="main-content">
        <h1 style="display: flex; justify-content: center;">Shop Name</h1>
        <div class="table-container">
            <h2 style="padding-bottom: 10px;">Produk</h2>
            <button class="add-button" onclick="window.location.href='buatproduk.php'">➕ Add Employee</button>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gambar</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Stock</th>
                        <th>Harga</th>
                        <th>Barcode</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td><img src='../uploads/" . $row['gambar'] . "' width='50' alt='Gambar Produk'></td>";
                            echo "<td>" . $row['nama'] . "</td>";
                            echo "<td>" . $row['kategori_nama'] . "</td>";
                            echo "<td>" . $row['stok'] . "</td>";
                            echo "<td>" . $row['harga'] . "</td>";
                            echo "<td> <button class='btn-barcode' onclick=\"showBarcodeModal('" . $row['barcode'] . "')\">Lihat Barcode</button> </td>";
                            echo "<td>
                                    <button class='btn-edit' onclick=\"window.location.href='editproduk.php?id=" . $row['id'] . "'\">Edit</button>
                                    <button class='btn-delete' onclick=\"if(confirm('Are you sure?')) window.location.href='hapus.php?id=" . $row['id'] . "'\">Delete</button>
                                    
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No data available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

<div id="barcodeModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center;">
    <div class="modal-dialog" style="background: white; border-radius: 10px; width: 90%; max-width: 500px; padding: 20px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
            <h5 class="modal-title" style="margin: 0; font-size: 1.25rem;">Barcode Produk</h5>
            <button type="button" class="close" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" style="padding: 20px; text-align: center;">
            <div id="barcodeContainer">
                <canvas id="barcode"></canvas>
            </div>
        </div>
        <div class="modal-footer" style="display: flex; justify-content: flex-end; border-top: 1px solid #ddd; padding-top: 10px;">
            <button type="button" class="btn-close" style="background: #ccc; color: #333; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;" onclick="closeModal()">Tutup</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
    function showBarcodeModal(barcode) {
        // Generate the barcode using JsBarcode
        JsBarcode("#barcode", barcode, {
            format: "CODE128",
            lineColor: "#000",
            width: 2,
            height: 100,
            displayValue: true
        });

        // Show the modal
        document.getElementById('barcodeModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('barcodeModal').style.display = 'none';
    }
</script>
</body>

</html>