<?php
include '../koneksi.php';

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

date_default_timezone_set('Asia/Jakarta');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['token'])) {
    $token = $_POST['token'];
    // $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $password = $_POST['password'];

    $stmt = $koneksi->prepare("UPDATE admin SET password=?, reset_token=NULL, reset_expiry=NULL WHERE reset_token=?");
    $stmt->bind_param("ss", $password, $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo '<script>alert("Password berhasil diganti."); location.href="login.php";</script>';
    } else {
        echo '<script>alert("Gagal mengganti password.");</script>';
    }
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $stmt = $koneksi->prepare("SELECT * FROM admin WHERE reset_token=? AND reset_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token valid
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Password</title>
            <link rel="stylesheet" href="styles.css">
        </head>
        <body>
            <div class="container">
                <div class="login-box">
                    <h2>RESET PASSWORD</h2>
                    <form method="post">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        <label>Password Baru</label>
                        <input type="password" name="password" placeholder="Password baru" required>
                        <button type="submit" class="login-btn">GANTI PASSWORD</button>
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
        <?php
    } else {
        echo '<script>alert("Token tidak valid atau kadaluarsa."); location.href="login.php";</script>';
    }
}
?>
