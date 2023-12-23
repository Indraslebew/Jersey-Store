<?php
session_start();
include "koneksi.php";

// Check if the user is logged in
if (!isset($_SESSION['id_admin'])) {
    echo "<script>alert('Mohon login terlebih dahulu.'); window.location.href='login.php';</script>";
    exit();
}

$id_admin = $_SESSION['id_admin'];

// Mengambil id_konfirmasi dari URL
if (isset($_GET['id_konfirmasi'])) {
    $id_konfirmasi = $_GET['id_konfirmasi'];

    // Ambil data dari tabel data_konfirmasi berdasarkan id_konfirmasi
    $sql = "SELECT * FROM data_konfirmasi WHERE id_konfirmasi = $id_konfirmasi";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $konfirmasi_data = $result->fetch_assoc();
    } else {
        echo "0 results";
    }
} else {
    // Tindakan jika id_konfirmasi tidak ada dalam URL
    echo "ID Konfirmasi tidak ditemukan";
}

// Update status when "Kirim" button is clicked
if (isset($_POST['kirim'])) {
    $id_konfirmasi_to_update = $_POST['kirim'];
    $update_status_sql = "UPDATE data_konfirmasi SET status = 'Di kirim' WHERE id_konfirmasi = $id_konfirmasi_to_update";
    if ($conn->query($update_status_sql) === TRUE) {
        echo "<script>alert('Berhasil Di kirim');</script>";
        echo "<meta http-equiv='refresh' content='0;url=keranjang.php'>";
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }

}
if (isset($_POST['pending'])) {
    $id_konfirmasi_to_update = $_POST['pending'];
    $update_status_sql = "UPDATE data_konfirmasi SET status = 'Pending' WHERE id_konfirmasi = $id_konfirmasi_to_update";
    if ($conn->query($update_status_sql) === TRUE) {
        echo "<script>alert('Berhasil di Pending');</script>";
        echo "<meta http-equiv='refresh' content='0;url=keranjang.php'>";
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Konfirmasi Pembayaran</title>
    <link rel="stylesheet" href="produk.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <style>
    /* Tombol untuk Kirim */
    button[name='kirim'] {
        background-color: green;
        /* Ubah warna latar belakang */
        color: white;
        /* Ubah warna teks */
    }

    /* Tombol untuk Pending */
    button[name='pending'] {
        background-color: yellow;
        /* Ubah warna latar belakang */
        margin-left: 10px;
        color: black;
        /* Ubah warna teks */
    }
    </style>
</head>

<body>
    <div class="container">
        <nav>
            <ul>
                <li>
                    <a href="Dashboard.php" class="active">
                        <span class="nav-item">Home</span>
                    </a>
                </li>
                <li>
                    <a href="produk.php">
                        <span class="nav-item">Produk</span>
                    </a>
                </li>
                <li>
                    <a href="keranjang.php">
                        <span class="nav-item">Keranjang</span>
                    </a>
                <li>
                    <a href="user.php">
                        <span class="nav-item">User</span>
                    </a>
                </li>
                </li><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                <li>
                    <a href="#" class="logout" onclick="konfirmasiLogout()">
                        <span class="nav-item">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="main">
            <form method="post" action="" onsubmit="return kirimAction()">
                <table class="lapangan-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Nama Barang</th>
                            <th>Alamat</th>
                            <th>No HP</th>
                            <th>Harga</th>
                            <th>Gambar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
        if (!empty($konfirmasi_data)) {
            echo "<tr>";
            echo "<td>1</td>";
            echo "<td>" . $konfirmasi_data['id_konfirmasi'] . "</td>";
            echo "<td>" . $konfirmasi_data['username_konfirmasi'] . "</td>";
            echo "<td>" . $konfirmasi_data['barang'] . "</td>";
            echo "<td>" . $konfirmasi_data['alamat_konfirmasi'] . "</td>";
            echo "<td>" . $konfirmasi_data['no_hp_konformasi'] . "</td>";
            echo "<td>" . $konfirmasi_data['harga_konfirmasi'] . "</td>";
            echo "<td><img src='user/bukti/" . $konfirmasi_data['gambar_konfirmasi'] . "' height='100'></td>";
            echo "<td>" . $konfirmasi_data['status'] . "</td>";
            echo "<td>";
if ($konfirmasi_data['status'] !== 'Di Terima') {
    echo "<button type='submit' name='kirim' value='" . $konfirmasi_data['id_konfirmasi'] . "'>Kirim</button>";
    echo "<button type='submit' name='pending' value='" . $konfirmasi_data['id_konfirmasi'] . "'>Pending</button>";
}
echo "</td>";
            echo "</tr>";
        } else {
            echo "<tr><td colspan='8'>0 results</td></tr>";
        }
        ?>
                    </tbody>
                </table>
            </form>
        </div>
        <script>
        function konfirmasiLogout() {
            var logout = confirm('Anda ingin logout?');
            if (logout) {
                window.location.href = 'login.php';
            }
        }
        </script>
</body>

</html>