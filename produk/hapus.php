<?php
include "../koneksi.php";

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (isset($_GET["id"])) {
    $id = intval($_GET["id"]); // Hindari SQL Injection

    if (!$koneksi) {
        die("Koneksi database tidak tersedia.");
    }

    // Cek apakah data ada sebelum menghapus
    $cek = $koneksi->prepare("SELECT id FROM produk WHERE id = ?");
    $cek->bind_param("i", $id);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $cek->close();

        $stmt = $koneksi->prepare("DELETE FROM produk WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<script>alert('Produk berhasil dihapus!'); location.href='produk.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus produk: {$stmt->error}'); location.href='produk.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Produk tidak ditemukan!'); location.href='produk.php';</script>";
    }

    $koneksi->close();
} else {
    echo "<script>alert('ID tidak ditemukan!'); location.href='produk.php';</script>";
}
?>
    