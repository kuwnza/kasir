<?php
// filepath: /var/www/local/kasir/keranjang/hapus_keranjang.php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produk_id'])) {
    $produk_id = intval($_POST['produk_id']);

    // Ambil jumlah produk dari keranjang sebelum dihapus
    $query_get_jumlah = "SELECT jumlah FROM keranjang WHERE produk_id = $produk_id";
    $result = $koneksi->query($query_get_jumlah);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $jumlah = $row['jumlah'];

        // Kembalikan stok produk
        $query_update_stok = "UPDATE produk SET stok = stok + $jumlah WHERE id = $produk_id";
        $koneksi->query($query_update_stok);

        // Hapus produk dari keranjang
        $query_delete = "DELETE FROM keranjang WHERE produk_id = $produk_id";
        if ($koneksi->query($query_delete)) {
            echo "<script>alert('Produk berhasil dihapus dari keranjang dan stok dikembalikan!'); window.location.href='keranjang.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus produk dari keranjang.'); window.location.href='keranjang.php';</script>";
        }
    } else {
        echo "<script>alert('Produk tidak ditemukan di keranjang.'); window.location.href='keranjang.php';</script>";
    }
} else {
    header("Location: keranjang.php");
    exit();
}
?>