<?php
include "../koneksi.php"; // Hubungkan ke database
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login/login.php");
    exit();
}
// Ambil data member berdasarkan ID
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $sql = "SELECT * FROM member WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $member = $result->fetch_assoc();

    if (!$member) {
        echo "<script>alert('Data member tidak ditemukan!'); window.location.href='member.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID tidak diberikan!'); window.location.href='member.php';</script>";
    exit;
}

// POST: Update data member
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nama = $_POST["nama"];
    $no_tlpn = $_POST["no_tlpn"];
    $email = $_POST["email"];
    $point = $_POST["point"];
    $status = $_POST["status"];

    // Debug: cek apakah data yang diterima dari form valid
    if (empty($nama) || empty($no_tlpn) || empty($email)) {
        echo "<script>alert('Nama, No Telpon, dan Email wajib diisi!'); window.location.href='editmember.php?id=$id';</script>";
        exit;
    }

    // Update data member
    $sql = "UPDATE member SET nama = ?, no_tlpn = ?, email = ?, point = ?, status = ? WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("sssssi", $nama, $no_tlpn, $email, $point, $status, $id);

    // Debug: Cek eksekusi query
    if ($stmt->execute()) {
        echo "<script>alert('Data member berhasil diupdate!'); window.location.href='member.php';</script>";
    } else {
        // Jika query gagal, tampilkan error
        echo "<script>alert('Gagal mengupdate data member! Error: " . $stmt->error . "'); window.location.href='editmember.php?id=$id';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member</title>
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

        .profile-picture {
            font-size: 80px;
            background: #eee;
            width: 65px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 10px;
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            padding: 20px;
        }

        .main-content h1 {
            margin-bottom: 20px;
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
            <li class="active">👥 Member</li>
            <li onclick="window.location.href='../admin/admin.php'">👥 Admin</li>
            <li onclick="window.location.href='../produk/produk.php'">📦 Produk</li>
            <li onclick="window.location.href=''">💰 Transaksi</li>
            <li onclick="window.location.href='../diskon/diskon.php'"> Diskon</li>
            <li onclick="window.location.href='../logout.php'">🚪 Quit</li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
    <h1 style="display: flex; justify-content: center;">Shop Name</h1>

        <div class="form-container">
            <form action="editmember.php?id=<?php echo $id; ?>" method="POST">
            <h2 style="padding-bottom: 10px;">Tambah Member</h2>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" value="<?php echo $member['nama']; ?>" required>
                </div>
                <div class="form-group">
                    <label>No Tlpn</label>
                    <input type="text" name="no_tlpn" value="<?php echo $member['no_tlpn']; ?>">
                </div>
                <div class="form-group">
                    <label>Point</label>
                    <input type="number" name="point" value="<?php echo $member['point']; ?>">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" value="<?php echo $member['email']; ?>">
                </div>
                <div class="form-group">
                <label>Status</label>
                <select name="status" required style="flex: 2; padding: 8px; border: 1px solid #ccc; border-radius: 5px;">
                    <option value="aktif" <?php echo ($member['status'] == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="tidak_aktif" <?php echo ($member['status'] == 'tidak_aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                </select>
                </div>
                <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                <div class="buttons">
                    <button type="button" class="btn back" onclick="window.location.href='member.php'">Back</button>
                    <button type="submit" class="btn submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
