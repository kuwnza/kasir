<?php
include "../koneksi.php"; // Hubungkan ke database
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login/login.php");
    exit();
}

$user = null; // Inisialisasi untuk menghindari warning

// GET: Ambil data admin berdasarkan ID
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $sql = "SELECT * FROM admin WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "<script>alert('Data admin tidak ditemukan!'); window.location.href='admin.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID tidak diberikan!'); window.location.href='admin.php';</script>";
    exit;
}

// POST: Update data admin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nama = $_POST["nama"];
    $no_tlpn = $_POST["no_tlpn"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $gambar = $user['gambar']; // Default gambar adalah gambar lama

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi file gambar
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            // Pindahkan file ke direktori tujuan
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                $gambar = htmlspecialchars(basename($_FILES["gambar"]["name"])); // Simpan nama file baru
            } else {
                echo "<script>alert('Gagal mengunggah gambar!'); window.location.href='editadmin.php?id=$id';</script>";
                exit;
            }
        } else {
            echo "<script>alert('Format gambar tidak valid! Hanya JPG, JPEG, PNG, dan GIF yang diperbolehkan.'); window.location.href='editadmin.php?id=$id';</script>";
            exit;
        }
    } else {
        // Jika tidak ada file baru yang diunggah, gunakan gambar lama
        $gambar = $user['gambar'];
    }

    // Validasi data lainnya
    if (empty($nama) || empty($no_tlpn) || empty($email)) {
        echo "<script>alert('Nama, No Telpon, dan Email wajib diisi!'); window.location.href='editadmin.php?id=$id';</script>";
        exit;
    }

    // Jika password diubah
    if (!empty($password)) {
        $sql = "UPDATE admin SET nama = ?, no_tlpn = ?, email = ?, password = ?, gambar = ? WHERE id = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sssssi", $nama, $no_tlpn, $email, $password, $gambar, $id);
    } else {
        // Jika password tidak diubah
        $sql = "UPDATE admin SET nama = ?, no_tlpn = ?, email = ?, gambar = ? WHERE id = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ssssi", $nama, $no_tlpn, $email, $gambar, $id);
    }

    // Eksekusi query
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diupdate!'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data! Error: " . $stmt->error . "'); window.location.href='editadmin.php?id=$id';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Dashboard</title>
    <style>
        /* Reset dasar */
        * {
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            color: #333;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background: #4CAF50;
            padding: 20px;
            color: white;
            min-height: 100vh;
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

        /* CARDS */
        .cards {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            justify-content: flex-start;
        }

        .card {
            background: white;
            padding: 20px;
            flex: 1;
            text-align: center;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            min-width: 150px;
        }

        .card h3 {
            margin-bottom: 5px;
            font-size: 1.2rem;
        }

        .card span {
            font-size: 0.9rem;
            color: #666;
        }

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: left;
            max-width: 800px;
            margin: auto;
        }

        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .form-group label {
            flex: 1;
            margin-right: 10px;
        }

        .form-group input {
            flex: 2;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn.back {
            background: gray;
            color: white;
        }

        .btn.submit {
            background: #4CAF50;
            color: white;
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
            <li class="active">👥 Admin</li>
            <li onclick="window.location.href='../produk/produk.php'">📦 Produk</li>
            <li onclick="window.location.href='../transaksi/transaksi.php'">💰 Transaksi</li>
            <li onclick="window.location.href='../diskon/diskon.php'"> Diskon</li>
            <li onclick="window.location.href='../logout.php'">🚪 Quit</li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <h1 style="display: flex; justify-content: center;">Shop Name</h1>

        <form action="editadmin.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-container">
                <h2 style="margin-bottom: 10px;">Edit Admin</h2>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" value="<?php echo $user['nama']; ?>" required>
                </div>
                <div class="form-group">
                    <label>No Tlpn</label>
                    <input type="text" name="no_tlpn" value="<?php echo $user['no_tlpn']; ?>">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password baru... (kosongkan jika tidak diubah)">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $user['email']; ?>">
                </div>
                <div class="form-group">
                    <label>Gambar</label>
                    <input type="file" name="gambar" accept="image/*">
                    <p><img src="../uploads/<?php echo $user['gambar']; ?>" alt="Gambar Admin" style="width: 100px; height: 100px; object-fit: cover; margin-top: 10px; border-radius: 10px;"></p>
                </div>
                <div class="buttons">
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                    <button type="button" class="btn back" onclick="window.location.href='admin.php'">Back</button>
                    <button type="submit" class="btn submit">Update</button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
