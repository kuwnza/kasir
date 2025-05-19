<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../koneksi.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

date_default_timezone_set('Asia/Jakarta');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    require '../vendor/PHPMailer-6.10.0/src/Exception.php';
    require '../vendor/PHPMailer-6.10.0/src/PHPMailer.php';
    require '../vendor/PHPMailer-6.10.0/src/SMTP.php';

    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50));
    $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

    $stmt = $koneksi->prepare("UPDATE admin SET reset_token=?, reset_expiry=? WHERE email=?");
    $stmt->bind_param("sss", $token, $expiry, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $reset_link = "http://localhost/kasir/login/reset_password.php?token=$token";

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '5fb9402a9c0d1d';
            $mail->Password = '7bedbee509833b';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 2525;

            // Recipients
            $mail->setFrom('no-reply@yourdomain.com', 'Kasir App');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password Anda';
            $mail->Body = "Klik link berikut untuk mengatur ulang password Anda:<br><br><a href='$reset_link'>$reset_link</a><br><br>Link ini berlaku 1 jam.";

            $mail->send();
            // header("Location: login.php?message=reset_link_sent");
            echo '<script>alert("Link reset password telah dikirim ke email Anda."); location.href="login.php";</script>';
            exit();
        } catch (Exception $e) {
            echo "Gagal mengirim email. Silakan coba lagi. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email tidak ditemukan.";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h2>Lupa Password</h2>
            <form action="" method="POST">
                <label>Email</label>
                <input type="email" name="email" placeholder="Email..." required>
                <button type="submit" class="login-btn" >kirim link reset password</button>
            </form>
        </div>
    </div>
</body>
</html>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }
    body {
        background-color: #5BC048;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .container {
        background: white;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        width: 400px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    h2 {
        margin-bottom: 20px;
        font-weight: bold;
    }
    label {
        display: block;
        text-align: left;
        font-weight: bold;
        margin-top: 10px;
    }
    input {
        width: 100%;
        padding: 10px;
        border-radius: 25px;
        border: none;
        background: #5BC048;
        color: white;
        margin-top: 5px;
        text-align: center;
    }
    .login-btn {
        width: 100%;
        padding: 10px;
        margin-top: 20px;
        background: gray;
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 25px;
        cursor: pointer;
    }
</style>
