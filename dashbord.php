<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login/login.php");
    exit();
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

        .card.active {
            background: #4CAF50;
            color: white;
        }

        /* CONTENT SECTION */
        .content {
            display: flex;
            gap: 20px;
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .transaction,
        .chart {
            background: white;
            padding: 20px;
            border-radius: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 300px;
        }

        .transaction h2,
        .chart h2 {
            margin-bottom: 15px;
        }

        .transaction label {
            display: block;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .transaction input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        .transaction button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 10px;
            margin-top: 5px;
            width: 100%;
            font-size: 1rem;
        }

        .transaction button:hover {
            opacity: 0.9;
        }

        .transaction .total-amount {
            margin: 15px 0;
            font-size: 1.5rem;
            color: red;
        }

        /* TABEL ORDER */
        .transaction table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .transaction table thead {
            background: #f4f4f4;
        }

        .transaction table th,
        .transaction table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 0.9rem;
        }

        .transaction table th {
            font-weight: 600;
        }

        /* CHART */
        .chart {
            display: flex;
            flex-direction: column;
        }

        .chart h2 {
            margin-bottom: 10px;
        }

        .bar-chart {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            height: 150px;
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            background: #f9f9f9;
        }

        .bar {
            width: 20px;
            background: #4CAF50;
            transition: 0.3s ease;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            color: white;
            font-size: 0.7rem;
            border-radius: 3px 3px 0 0;
        }

        .bar-label {
            writing-mode: vertical-rl;
            transform: rotate(180deg);
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="profil.php" style="text-decoration: none; color: inherit;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <img class="profile-picture" src="uploads/<?= htmlspecialchars($_SESSION['admin']['gambar']); ?>" alt="Profile Image">
                <h2><?= htmlspecialchars($_SESSION['admin']['nama']); ?></h2>
            </div>
        </a>
        <ul>
            <li class="active">🏠 Dashboard</li>
            <li onclick="window.location.href='kategori/kategori.php'">📂 Kategori</li>
            <li onclick="window.location.href='member/member.php'">👥 Member</li>
            <li onclick="window.location.href='admin/admin.php'">👥 Admin</li>
            <li onclick="window.location.href='produk/produk.php'">📦 Produk</li>
            <li onclick="window.location.href='transaksi/transaksi.php'">💰 Transaksi</li>
            <li onclick="window.location.href='keranjang/keranjang.php'">🛒Keranjang</li>
            <li onclick="window.location.href='logout.php'">🚪 Quit</li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <h1 style="display: flex; justify-content: center;">Shop Name</h1>

        <!-- CARDS -->
        <div class="cards">
            <div class="card">
                <h3>IDR. 10.000</h3>
                <span>In Come</span>
            </div>
            <div class="card">
                <h3>IDR. 20.000</h3>
                <span>Out Come</span>
            </div>
            <div class="card active">
                <h3>Cashier</h3>
                <span>Transaction</span>
            </div>
            <div class="card">
                <h3>400 Stock</h3>
                <span>Stock Item</span>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">
            <!-- TRANSACTION -->
            <div class="transaction">
                <h2>Transaction</h2>
                <label>Data Item</label>
                <input type="text" placeholder="Masukkan nama item...">

                <label>Amount</label>
                <input type="number" placeholder="Masukkan jumlah...">

                <button>Add Item</button>

                <h3 class="total-amount">IDR 150.000</h3>

                <button>Pay & Print</button>

                <h3 style="margin-top: 20px;">List Order</h3>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Name</th>
                            <th>Ex</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>ABC123</td>
                            <td>Desc</td>
                            <td>2</td>
                            <td>IDR 50.000</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>XYZ789</td>
                            <td>Desc</td>
                            <td>3</td>
                            <td>IDR 75.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- CHART -->
            <div class="chart">
                <h2>Chart</h2>
                <p>Perbandingan penjualan per bulan</p>
                <div class="bar-chart">
                    <!-- Contoh bar dengan tinggi bervariasi -->
                    <div class="bar" style="height: 40px;">
                        <span class="bar-label">Jan</span>
                    </div>
                    <div class="bar" style="height: 80px;">
                        <span class="bar-label">Feb</span>
                    </div>
                    <div class="bar" style="height: 60px;">
                        <span class="bar-label">Mar</span>
                    </div>
                    <div class="bar" style="height: 100px;">
                        <span class="bar-label">Apr</span>
                    </div>
                    <div class="bar" style="height: 50px;">
                        <span class="bar-label">Mei</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>