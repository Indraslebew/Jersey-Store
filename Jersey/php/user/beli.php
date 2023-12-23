<?php
include "../koneksi.php";
session_start();

// Inisialisasi variabel untuk mencegah kesalahan variabel yang tidak terdefinisi
$nama = $alamat = $no_hp = $barang = $jumlah = $total = '';

// Cek apakah formulir telah dikirimkan
if (isset($_POST['submit'])) {
    // Mendapatkan dan memvalidasi data dari formulir (pastikan selalu untuk melakukan validasi dan sanitasi input pengguna untuk mencegah serangan SQL injection)
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $barang = $_POST['barang'];
    $jumlah = $_POST['jumlah'];
    $total = $_POST['total'];
    $status = "Pending"; // Set status to 'Pending'
    
    // Menyimpan 'id_user' dalam sesi setelah pengguna login
    if(isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];

        // Handle unggah file
        $targetDirectory = "bukti/"; // Direktori tempat file akan diunggah

        $fileExtension = pathinfo($_FILES['bukti_pembayaran']['name'], PATHINFO_EXTENSION);
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        // Memeriksa apakah ekstensi file diizinkan
        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadedFilePath = $targetDirectory . uniqid() . '.' . $fileExtension; // Menyimpan nama file beserta direktori

            // Simpan gambar di folder yang sama dengan script PHP
            move_uploaded_file($_FILES["bukti_pembayaran"]["tmp_name"], __DIR__ . "/" . $uploadedFilePath);

            $nama_file = basename($uploadedFilePath); // Mendapatkan hanya nama file

            // Menyiapkan pernyataan SQL dengan prepared statement
            $stmt = $conn->prepare("INSERT INTO data_konfirmasi (id_user, username_konfirmasi, alamat_konfirmasi, no_hp_konformasi, barang, jumlah, harga_konfirmasi, gambar_konfirmasi, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssssss", $id_user, $nama, $alamat, $no_hp, $barang, $jumlah, $total, $nama_file, $status);
            
            // Mengeksekusi prepared statement
            if ($stmt->execute()) {
                // Jika penyisipan data berhasil, lakukan tindakan tambahan atau pengalihan

                // Hapus data dari tabel data_keranjang sesuai id_user yang login
                $deleteStmt = $conn->prepare("DELETE FROM data_keranjang WHERE id_user = ?");
                $deleteStmt->bind_param("i", $id_user);
                $deleteStmt->execute();
                $deleteStmt->close();

                echo '<script>alert("Berhasil upload bukti pembayaran , silahkan cek pesanan"); window.location = "Dash.php";</script>';
                exit();
            } else {
                // Menghandle kegagalan penyisipan data
                echo "Gagal menyimpan data. Silakan coba lagi.";
            }

            // Menutup pernyataan
            $stmt->close();
        } else {
            echo "Tipe file tidak diizinkan. Harap unggah file JPG, JPEG, atau PNG saja.";
        }
    } else {
        // Menghandle jika id_user tidak ada dalam sesi
        echo "ID Pengguna tidak ditemukan. Silakan login terlebih dahulu.";
    }
} else {
    // Mendapatkan data untuk mengisi formulir sebelumnya (prefill)
    if(isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
        
        // Mengambil data pengguna dari tabel data_user
        $queryUserData = "SELECT username_user, alamat_user, no_telp_user FROM data_user WHERE id_user = ?";
        $stmt = $conn->prepare($queryUserData);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $resultUserData = $stmt->get_result();

        if ($resultUserData->num_rows > 0) {
            $userData = $resultUserData->fetch_assoc();
            $nama = $userData['username_user'];
            $alamat = $userData['alamat_user'];
            $no_hp = $userData['no_telp_user'];
        } else {
            // Menghandle jika data pengguna tidak ditemukan
        }
        $stmt->close();

        // Mengambil data keranjang dari tabel data_keranjang
        $queryKeranjangData = "SELECT GROUP_CONCAT(nama_barang_keranjang) AS nama_barang, GROUP_CONCAT(jumlah_keranjang) AS jumlah, SUM(total_harga_keranjang) AS total_harga FROM data_keranjang WHERE id_user = ?";
        $stmt = $conn->prepare($queryKeranjangData);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $resultKeranjangData = $stmt->get_result();

        if ($resultKeranjangData->num_rows > 0) {
            $keranjangData = $resultKeranjangData->fetch_assoc();
            $barang = $keranjangData['nama_barang'];
            $jumlah = $keranjangData['jumlah'];
            $total = $keranjangData['total_harga'];
        } else {
            // Menghandle jika data keranjang tidak ditemukan
        }
        $stmt->close();
    }
}
?>



<!DOCTYPE html>
<html>

<head>
    <title>Form Bukti Pembayaran</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
    }

    .container {
        width: 50%;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="number"] {
        width: 100%;
        padding: 8px;
        font-size: 16px;
    }

    input[type="file"] {
        margin-top: 5px;
    }

    input[type="submit"] {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Form Bukti Pembayaran</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <!-- Nilai dari data pengguna dan keranjang diisi ke dalam form -->
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" value="<?php echo $nama; ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" name="alamat" id="alamat" value="<?php echo $alamat; ?>" required>
            </div>
            <div class="form-group">
                <label for="no_hp">No HP</label>
                <input type="text" name="no_hp" id="no_hp" value="<?php echo $no_hp; ?>" required>
            </div>
            <div class="form-group">
                <label for="barang">Barang</label>
                <input type="text" name="barang" id="barang" value="<?php echo $barang; ?>" required>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="text" name="jumlah" id="jumlah" value="<?php echo $jumlah; ?>" required>
            </div>
            <div class="form-group">
                <label for="total">Total</label>
                <!-- Menggunakan number_format() untuk menampilkan nilai total dalam format ribuan -->
                <input type="text" name="total" id="total" value="Rp.<?php echo number_format($total, 0, ',', '.'); ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="nomor_rekening">Nomor Rekening : 503601053996537 A.N. Muh Ikhsan </label>
            </div>
            <div class="form-group">
                <label for="bukti_pembayaran">Upload Bukti Pembayaran (Foto)</label>
                <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" required>
            </div>
            <!-- Submit button -->
            <div class="form-group">
                <input type="submit" name="submit" value="Upload Bukti Pembayaran">
            </div>
        </form>
    </div>
</body>

</html>