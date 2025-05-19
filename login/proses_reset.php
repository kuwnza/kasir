<?php
include "koneksi.php";

if (isset($_POST['token']) && isset($_POST['password'])) {
    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $koneksi->prepare("UPDATE admin SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
    $stmt->bind_param("ss", $password, $token);
    $stmt->execute();

    if ($stmt->affected_rows > 40) {
        echo "<script>alert('Password berhasil diganti'); location.href='login.php';</script>";
    } else {
        echo "<script>alert('Token tidak valid'); location.href='lupa_password.php';</script>";
    }
}
?>
