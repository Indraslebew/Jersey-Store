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


// Mengecek apakah parameter 'id_produk' telah diterima
if (isset($_GET['id_produk'])) {
    $id_produk = $_GET['id_produk'];

    // Memeriksa apakah produk dengan id tertentu ada dalam database
    $sql = "SELECT * FROM data_produk WHERE id_produk = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the product with the given ID exists
    if ($result->num_rows == 1) {
        // Fetch product data
        $row_produk = $result->fetch_assoc();

        // Inisialisasi nilai untuk input form
        $nama_produk = $row_produk['nama_produk'];
        $warna_produk = $row_produk['warna_produk'];
        $stok_produk = $row_produk['stok_produk'];
        $ukuran_produk = $row_produk['ukuran_produk'];
        $harga_produk = $row_produk['harga_produk'];
        $gambar_produk = $row_produk['gambar_produk'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle form submission
            $nama_produk = $_POST['nama'];
            $stok_produk = $_POST['stok'];
            $warna_produk = $_POST['warna'];
            $ukuran_produk = $_POST['ukuran'];
            $harga_produk = $_POST['harga'];

            // Apakah gambar diubah
            $gambar_lama = $row_produk['gambar_produk'];
            $gambar_baru = $_FILES['gambar']['name'];

            // Check if a new image is uploaded
            if ($gambar_baru != '') {
                // Simpan gambar ke direktori
                $target_dir = "gambar/";
                $target_file = $target_dir . basename($_FILES["gambar"]["name"]);

                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Cek apakah file gambar atau bukan
                $check = getimagesize($_FILES["gambar"]["tmp_name"]);
                if ($check === false) {
                    echo "File bukan gambar.";
                    exit();
                }

                // Cek ukuran file
                if ($_FILES["gambar"]["size"] > 500000) {
                    echo "Maaf, ukuran file terlalu besar.";
                    exit();
                }

                // Izinkan format tertentu
                if (
                    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif"
                ) {
                    echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
                    exit();
                }

                // Hapus gambar lama
                unlink($target_dir . $gambar_lama);

                // Upload gambar baru
                if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                    // Update product data in the database with the new image
                    $update_sql = "UPDATE data_produk 
                                   SET nama_produk=?, 
                                       stok_produk=?, 
                                       warna_produk=?, 
                                       ukuran_produk=?, 
                                       harga_produk=?, 
                                       gambar_produk=? 
                                   WHERE id_produk=?";
                    $stmt_update = $conn->prepare($update_sql);
                    $stmt_update->bind_param(
                        "ssssiss",
                        $nama_produk,
                        $stok_produk,
                        $warna_produk,
                        $ukuran_produk,
                        $harga_produk,
                        $gambar_baru,
                        $id_produk
                    );
                } else {
                    echo "Maaf, terjadi kesalahan saat mengupload file.";
                    exit();
                }
            } else {
                // Update product data in the database without changing the image
                $update_sql = "UPDATE data_produk 
                               SET nama_produk=?, 
                                   stok_produk=?, 
                                   warna_produk=?, 
                                   ukuran_produk=?, 
                                   harga_produk=? 
                               WHERE id_produk=?";
                $stmt_update = $conn->prepare($update_sql);
                $stmt_update->bind_param(
                    "ssssis",
                    $nama_produk,
                    $stok_produk,
                    $warna_produk,
                    $ukuran_produk,
                    $harga_produk,
                    $id_produk
                );
            }

 if ($stmt_update->execute()) {
                header("Location: produk.php");
            } else {
                echo "Error updating record: " . $stmt_update->error;
            }

            $stmt_update->close();
        }
    } else {
        echo "Produk tidak ditemukan.";
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html>

<head>
    <title>Edit Produk</title>
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
            background-color: orange;
            color: white;
            margin-right: 10px;
        }

        input[type="reset"] {
            background-color: #F44336;
            color: white;
        }
    </style>
</head>

<body>

    <div class="container">
        <nav>

            <ul>
                <li>
                    <a href="Dashboard.php">
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
            <form action="" method="post" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td>Nama</td>
                        <td>: <input type="text" name="nama" size="40" value="<?php echo $nama_produk; ?>" required />
                        </td>
                    </tr>
                    <tr>
                        <td>Warna</td>
                        <td>:
                            <select name="warna" class="select-box" required>
                                <option value="" disabled selected>Combobox</option>
                                <option value="Merah" <?php if ($warna_produk === 'Merah') echo 'selected'; ?>>Merah
                                </option>
                                <option value="Kuning" <?php if ($warna_produk === 'Kuning') echo 'selected'; ?>>Kuning
                                </option>
                                <option value="Putih" <?php if ($warna_produk === 'Putih') echo 'selected'; ?>>Putih
                                </option>
                                <option value="Biru" <?php if ($warna_produk === 'Biru') echo 'selected'; ?>>Biru
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Stok</td>
                        <td>: <input type="text" name="stok" size="40" value="<?php echo $stok_produk; ?>" required />
                        </td>
                    </tr>
                    <tr>
                        <td>Ukuran</td>
                        <td>:
                            <select name="ukuran" class="select-box" required>
                                <option value="" disabled selected>Combobox</option>
                                <option value="M" <?php if ($ukuran_produk === 'M') echo 'selected'; ?>>M</option>
                                <option value="L" <?php if ($ukuran_produk === 'L') echo 'selected'; ?>>L</option>
                                <option value="XL" <?php if ($ukuran_produk === 'XL') echo 'selected'; ?>>XL</option>
                                <option value="XXL" <?php if ($ukuran_produk === 'XXL') echo 'selected'; ?>>XXL</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Harga</td>
                        <td>: Rp.<input type="text" name="harga" value="<?php echo $harga_produk; ?>" required /></td>
                    </tr>
                    <tr>


<tr>
    <td>Gambar</td>
    <td>: <input type="file" name="gambar" /><br><?php echo $gambar_produk; ?></td>
</tr>


                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="edit" value="Simpan Perubahan" />
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

</body>

</html>
