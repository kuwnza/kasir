<?php

include '../koneksi.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// search member by no_hp

$no_hp = $_POST['phone'] ?? '';
// $no_hp = htmlspecialchars($no_hp, ENT_QUOTES, 'UTF-8');

// Ambil data member dari database
$sql = "SELECT * FROM member WHERE no_tlpn = '$no_hp'";
$result = $koneksi->query($sql);

if ($result->num_rows >= 1) {
    $member = $result->fetch_assoc();
    echo json_encode($member);
} else {
    $member = null;
    // show error 404
    http_response_code(404);
    echo json_encode(array("error" => "Member not found"));
}
?>