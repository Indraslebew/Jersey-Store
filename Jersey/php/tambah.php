<!DOCTYPE html>
<html>

<head>
    <title>Tambah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
        }

        nav {
            background-color: #333;
            color: white;
            padding: 20px;
            width: 200px;
            height: 100vh;
            box-sizing: border-box;
            overflow-y: auto;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        nav li {
            margin-bottom: 15px;
        }

        nav a {
            text-decoration: none;
            color: white;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #555;
        }

        .main {
            flex: 1;
            padding: 20px;
        }

        form {
            max-width: 1200px;
            background-color: #fff;
            padding: 10px;
            margin: 0;
            border: 1px solid #ccc;
        }

        table {
            width: 50%;
        }

        td {
            padding: 8px;
        }

        input[type="text"],
        input[type="file"] {
            padding: 5px;
        }

        input[type="submit"],
        input[type="reset"] {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }

        input[type="submit"] {
            background-color: #2196F3; /* Ubah warna menjadi biru */
            color: white;
            margin-right: 10px;
        }

        input[type="reset"] {
            background-color: #F44336; /* Ubah warna menjadi merah */
            color: white;
        }

    </style>
</head>

<body>

    <body>
<?php
session_start();
include "koneksi.php";

// Check if the user is logged in
if (!isset($_SESSION['id_admin'])) {
    echo "<script>alert('Mohon login terlebih dahulu.'); window.location.href='login.php';</script>";
    exit();
}

$id_admin = $_SESSION['id_admin'];

// Check if the user has been active recently (adjust the time as needed)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // If inactive for more than 30 minutes (1800 seconds), regenerate session ID
    session_regenerate_id(true);
    $_SESSION['last_activity'] = time(); // Update last activity timestamp
}


    $id_admin = $_SESSION['id_admin'];

    $sql = "SELECT username_admin FROM data_admin WHERE id_admin = $id_admin";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama_produk = $_POST['nama'];
        $stok_produk = $_POST['stok'];
        $warna_produk = $_POST['warna'];
        $ukuran_produk = $_POST['ukuran'];
        $harga_produk = $_POST['harga'];
        $gambar_produk = $_FILES['gambar']['name'];
        $id_admin = $_SESSION['id_admin']; // Sesuai dengan session Anda

        // Simpan gambar ke direktori
        $target_dir = "gambar/";
        $target_file = $target_dir . basename($_FILES["gambar"]["name"]);

        // Cek apakah file gambar atau bukan
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (isset($_POST["tambah"])) {
            $check = getimagesize($_FILES["gambar"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File bukan gambar.";
                $uploadOk = 0;
            }
        }

        // Cek jika file sudah ada
        if (file_exists($target_file)) {
            echo "Maaf, file sudah ada.";
            $uploadOk = 0;
        }

        // Cek ukuran file
        if ($_FILES["gambar"]["size"] > 5000000) {
            echo "Maaf, ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        // Izinkan format tertentu
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
            $uploadOk = 0;
        }

        // Cek jika $uploadOk bernilai 0
        if ($uploadOk == 0) {
            echo "Maaf, file tidak terupload.";
        } else {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                $sql = "INSERT INTO data_produk (id_admin, nama_produk, stok_produk, warna_produk, harga_produk, gambar_produk)
                        VALUES ('$id_admin', '$nama_produk', '$stok_produk', '$warna_produk', '$harga_produk', '$gambar_produk')";
                if ($conn->query($sql) === TRUE) {
                    header("Location: produk.php");
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Maaf, terjadi kesalahan saat mengupload file.";
            }
        }

        $conn->close();
    }
    ?>

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
                    </li>
                    <li>
                    <a href="user.php">
                        <span class="nav-item">User</span>
                    </a>
                </li>
                    <li><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                        <a href="#" class="logout" onclick="konfirmasiLogout()">
                            <span class="nav-item">Logout</span>
                        </a>
                    </li>
                </ul>

            </nav>
            <div class="main">
                <form method="post" enctype="multipart/form-data">
                    <table>
                        <tr>
                            <td>Nama</td>
                            <td>: <input type="text" name="nama" size="40" required /></td>
                        </tr>
                        <tr>
                            <td>Warna</td>
                            <td>
                                :
                                <select name="warna" class="select-box" required>
                                    <option value="" disabled selected>Combobox</option>
                                    <option value="Merah">Merah</option>
                                    <option value="Kuning">Kuning</option>
                                    <option value="Putih">Putih</option>
                                    <option value="Biru">Biru</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Stok</td>
                            <td>: <input type="text" name="stok" size="40" required /></td>
                        </tr>
                        <tr>
                            <td>Ukuran</td>
                            <td>
                                :
                                <select name="ukuran" class="select-box" required>
                                    <option value="" disabled selected>Combobox</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Harga</td>
                            <td>: Rp. <input type="text" name="harga" required /></td>
                        </tr>
                        <tr>
                            <td>Gambar</td>
                            <td>: <input type="file" name="gambar" required /></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="submit" name="tambah" value="Tambahkan" />
                                <input type="reset" name="reset" value="Reset" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <script>
        function konfirmasiLogout() {
            var logout = confirm('Anda ingin logout?');

            if (logout) {
                window.location.href = 'login.php';
            }
        }
        </script>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var inputHarga = document.querySelector('input[name="harga"]');
            inputHarga.addEventListener('input', function() {
                var value = this.value.replace(/\D/g, '');
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                this.value = "" + value;
            });
        });
        </script>
    </body>

</html>