<?php
include "../koneksi.php"; // Pastikan file koneksi dimasukkan

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Pastikan koneksi ke database tersedia
    if (!$koneksi) {
        die("Koneksi database tidak tersedia.");
    }

    // Query hapus data berdasarkan ID
    $sql = "DELETE FROM member WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); location.href='member.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); location.href='member.php';</script>";
    }

    $stmt->close(); // Tutup statement
    $koneksi->close(); // Tutup koneksi database
} else {
    echo "<script>alert('ID tidak ditemukan!'); location.href='member.php';</script>";
}
?>
