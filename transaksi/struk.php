<?php
include '../koneksi.php';

$id_transaksi = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data transaksi
$sql_transaksi = "SELECT * FROM transaksi WHERE id = $id_transaksi";
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
    <title>Struk Transaksi</title>
    <style>
        .struk {
            font-family: 'Courier New', Courier, monospace;
            font-size: 1.2rem; /* Ukuran font diperbesar */
            line-height: 1.8; /* Jarak antar baris diperbesar */
            margin: 20px auto;
            padding: 30px; /* Padding diperbesar */
            background: #fff;
            border: 2px dashed #333; /* Border diperbesar */
            border-radius: 10px; /* Border radius diperbesar */
            width: 500px; /* Lebar struk diperbesar */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* Shadow diperbesar */
        }

        .struk h3 {
            text-align: center;
            margin-bottom: 20px; /* Jarak bawah diperbesar */
            font-size: 1.5rem; /* Ukuran font judul diperbesar */
            color: #333;
        }

        .struk p {
            margin: 10px 0; /* Jarak antar paragraf diperbesar */
        }

        .struk .total {
            font-weight: bold;
            text-align: right;
            margin-top: 20px; /* Jarak atas diperbesar */
            font-size: 1.3rem; /* Ukuran font total diperbesar */
        }

        .btn-print {
            padding: 15px 30px; /* Padding tombol diperbesar */
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 10px; /* Border radius tombol diperbesar */
            cursor: pointer;
            font-size: 1.2rem; /* Ukuran font tombol diperbesar */
        }

        .btn-print:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="struk">
        <h3>Struk Transaksi</h3>
        <p>ID Transaksi: <?php echo $transaksi['id']; ?></p>
        <p>Tanggal: <?php echo $transaksi['tanggal_transaksi']; ?></p>
        <p>Metode Pembayaran: <?php echo $transaksi['metode_pembayaran']; ?></p>
        <p>Total: Rp. <?php echo number_format($transaksi['total'], 0, ',', '.'); ?></p>
        <p>Uang Masuk: Rp. <?php echo number_format($transaksi['uang_masuk'], 0, ',', '.'); ?></p>
        <p>Kembalian: Rp. <?php echo number_format($transaksi['uang_masuk'] - $transaksi['total'], 0, ',', '.'); ?></p>
        <hr>
        <h4>Detail Produk</h4>
        <?php while ($row = $result_detail->fetch_assoc()): ?>
            <p><?php echo $row['nama_produk']; ?> (x<?php echo $row['jumlah']; ?>) - Rp. <?php echo number_format($row['total'], 0, ',', '.'); ?></p>
        <?php endwhile; ?>
        <p class="total">Terima Kasih!</p>
    </div>

    <!-- Tombol Print -->
    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem;">
            Print Struk
        </button>
    </div>
</body>
</html>