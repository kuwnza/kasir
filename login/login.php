<?php
session_start();
include '../koneksi.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo '<script>alert("email dan password tidak boleh kosong!");</script>';
    } else {
        $stmt = $koneksi->prepare("SELECT * FROM admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // var_dump($result);die;

        // var_dump($result); // Debugging line

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            if ($password === $data['password']) {
                $_SESSION['admin'] = $data;
                echo '<script>alert("Selamat datang, ' . htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8') . '");
                    location.href="../dashbord.php";</script>';
            } else {
                echo '<script>alert("email atau password salah!");</script>';
            }
        } else {
            // echo '<script>alert("Nama atau password salah!");</script>';
            echo '<script>alert("Email atau password tidak ditemukan!");</script>';
        }

        $stmt->close();
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
            <h2>CASHIER APPLICATION</h2>
            <form action="" method="POST">
                <label>Email</label>
                <input type="text" name="email" placeholder="Email..." required>
                <label>PASSWORD</label>
                <input type="password" name="password" placeholder="Password..." required>
                <a href="lupa_password.php">Lupa Password?</a>
                <button type="submit" class="login-btn" >LOGIN</button>
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
