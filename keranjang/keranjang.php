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

// Ambil data keranjang
$query = "SELECT keranjang.*, produk.nama, produk.harga, produk.gambar 
          FROM keranjang 
          JOIN produk ON keranjang.produk_id = produk.id";
$result = $koneksi->query($query);

$total = 0;
$items = [];
while ($row = $result->fetch_assoc()) {
    $total += $row['harga'] * $row['jumlah'];
    $items[] = $row;
}

// Proses simpan transaksi jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_id = !empty($_POST['member_id']) ? $_POST['member_id'] : null;
    $admin_id = $_SESSION['admin']['id']; // ganti dengan session admin jika ada
    $tanggal = date('Y-m-d');
    $status = 'dibayar';
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $uang_masuk = $_POST['uang'];
    $uang_keluar = $uang_masuk - $total;

    // Simpan data transaksi
    $insert_transaksi = "INSERT INTO transaksi (id_member, id_admin, metode_pembayaran, uang_masuk, uang_keluar, total, status, tanggal_transaksi)
                         VALUES ($member_id, $admin_id, '$metode_pembayaran', $uang_masuk, $uang_keluar, $total, '$status', NOW())";
    $koneksi->query($insert_transaksi);

    // Ambil ID transaksi yang baru dibuat
    $transaksi_id = $koneksi->insert_id;

    // Simpan detail transaksi
    foreach ($items as $item) {
        $produk_id = $item['produk_id'];
        $jumlah = $item['jumlah'];
        $harga = $item['harga'];
        $total_harga = $harga * $jumlah;

        $insert_detail = "INSERT INTO detail_transaksi (transaksi_id, nama_produk, harga, jumlah, total)
                          VALUES ($transaksi_id, '{$item['nama']}', $harga, $jumlah, $total_harga)";
        $koneksi->query($insert_detail);
    }

    // Tambahkan poin ke member jika menggunakan member
    if ($member_id) {
        $poin_baru = floor($total / 1000) * 10; // Hitung poin baru
        $update_poin = "UPDATE member SET poin = poin + $poin_baru WHERE id = $member_id";
        $koneksi->query($update_poin);
    }

    // Kosongkan keranjang
    $koneksi->query("DELETE FROM keranjang");

    echo "<script>alert('Transaksi berhasil!'); window.location.href='../transaksi/transaksi.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Halaman Transaksi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
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
            width: 50px;
            height: 45px;
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

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .cart-container {
            display: flex;
            gap: 20px;
        }

        .cart-items {
            flex: 2;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .item {
            background-color: #e0e0e0;
            padding: 20px;
            border-radius: 10px;
        }

        .summary {
            flex: 1;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
        }

        .summary h3 {
            margin-bottom: 10px;
        }

        .summary input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }

        .summary .info {
            margin: 10px 0;
        }

        .summary .pay-section {
            margin-top: 20px;
        }

        .summary button {
            width: 100%;
            padding: 10px;
            background-color: #2eb95c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .summary .payment-method {
            margin-bottom: 10px;
        }

        .summary select {
            width: 100%;
            padding: 8px;
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
            <li onclick="window.location.href='../transaksi/transaksi.php'">💰 Transaksi</li>
            <li class="active">🛒Keranjang</li>
            <li onclick="window.location.href='../logout/logout.php'">🚪 Quit</li>
        </ul>
    </div>

    <div class="content">
        <h2>Keranjang</h2>
        <div class="cart-container">
            <div class="cart-items">
                <?php foreach ($items as $item): ?>
                    <div class="item">
                        <img src="../uploads/<?= htmlspecialchars($item['gambar']); ?>" alt="<?= htmlspecialchars($item['nama']); ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 10px; margin-bottom: 10px;">
                        <strong><?= htmlspecialchars($item['nama']); ?></strong>
                        <p>Rp. <?= number_format($item['harga'], 0, ',', '.'); ?></p>
                        <p>Jumlah: <?= $item['jumlah']; ?></p>
                        <form method="POST" action="hapus_keranjang.php" style="margin-top: 10px;">
                            <input type="hidden" name="produk_id" value="<?= $item['produk_id']; ?>">
                            <button type="submit" style="background: #e74c3c; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer;">Hapus</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="summary">
                <form method="POST">
                    <h3>Pembayaran</h3>
                    <label for="searchMember">No Telepon Member:</label>
                    <input type="hidden" id="member_id" name="member_id"/>
                    <input type="text" id="searchMember" placeholder="Masukkan No Telepon" />

                    <div class="info">
                        <p>Pajak (10%): Rp. <span id="pajak"></span></p>
                        <p>Total Belanja: Rp. <?php echo number_format($total, 0, ',', '.'); ?></p>

                        <label for="uang">Uang Dibayar:</label>
                        <input type="text" id="uang" name="uang" placeholder="Masukkan Nominal Uang" style="margin-top: 10px;" required />

                        <p>Kembalian: Rp. <span id="kembalian">0</span></p>
                    </div>

                    <p>Total Belanja: Rp. <?= number_format($total, 0, ',', '.'); ?></p>
                    <div class="pay-section">
                        <label for="payment">Metode Pembayaran</label>
                        <select id="payment" name="metode_pembayaran" style="margin-top: 10px; margin-bottom: 10px;">
                            <option value="Cash">Cash</option>
                        </select>
                        <!-- Tombol Bayar Sekarang -->
                        <button type="submit" <?= empty($items) ? 'disabled' : ''; ?>>Bayar Sekarang</button>
                    </div>
                </form>
                <a href="list-produk.php"><button style="margin-top: 5px;">Tambah Produk</button></a>
            </div>
        </div>
    </div>
    <script>
        const total = <?php echo $total; ?>;
        const pajak = total * 0.10;
        const totalBayar = total + pajak;

        document.getElementById('pajak').innerText = pajak.toLocaleString('id-ID');

        document.getElementById('uang').addEventListener('input', function() {
            const uangDibayar = parseFloat(this.value.replace(/[^0-9]/g, '')) || 0; // Handle non-numeric input
            const kembalian = uangDibayar - totalBayar;
            document.getElementById('kembalian').innerText = kembalian > 0 ? kembalian.toLocaleString('id-ID') : 0;
        });

        document.getElementById('searchMember').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent form submission
                const searchValue = this.value.trim();

                if (searchValue) {
                    const formData = new FormData();
                    formData.append('phone', searchValue);

                    fetch('../member/searchMember.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.id) {
                                document.getElementById('member_id').value = data.id;
                                document.getElementById('searchMember').setAttribute('readonly', true);
                                alert('Member ditemukan: ' + data.nama);
                            } else {
                                alert('Member tidak ditemukan.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat mencari member.');
                        });
                } else {
                    alert('Masukkan nomor telepon untuk mencari.');
                }
            }
        });
    </script>

</body>

</html>