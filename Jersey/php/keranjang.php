<?php
session_start();
include "koneksi.php";

// Check if the user is logged in
if (!isset($_SESSION['id_admin'])) {
    echo "<script>alert('Mohon login terlebih dahulu.'); window.location.href='login.php';</script>";
    exit();
}

// Using JOIN to combine data from the data_keranjang and data_produk tables
$sql = "SELECT dk.*, dp.harga_produk, dp.gambar_produk
        FROM data_keranjang AS dk
        JOIN data_produk AS dp ON dk.id_produk = dp.id_produk";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $pemesanan_data = array(); // Storing product data

    while ($row = $result->fetch_assoc()) {
        $jumlah_keranjang = (int) $row['jumlah_keranjang'];

        // Set the harga_keranjang and gambar_keranjang to the values from data_produk
        $harga_keranjang = (int) str_replace('.', '', $row['harga_produk']);
        $gambar_keranjang = $row['gambar_produk'];

        // Update total_harga_keranjang, harga_keranjang, and gambar_keranjang in the database
        $update_query = "UPDATE data_keranjang
                        SET total_harga_keranjang = $jumlah_keranjang * $harga_keranjang,
                        harga_keranjang = $harga_keranjang,
                        gambar_keranjang = '$gambar_keranjang'
                        WHERE id_keranjang = " . $row['id_keranjang'];
        $conn->query($update_query);

        // Update data in the array
        $row['harga_keranjang'] = $harga_keranjang;
        $row['total_harga_keranjang'] = $jumlah_keranjang * $harga_keranjang;
        $row['gambar_keranjang'] = $gambar_keranjang;

        $pemesanan_data[] = $row;
    }
} else {
    
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Keranjang</title>
    <link rel="stylesheet" href="produk.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
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
            <br />
            <form class="search-form" method="get" action="keranjang.php">
                <input type="text" name="search" placeholder="Cari..." />
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            <table class="lapangan-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pembeli</th>
                        <th>Nama Barang</th>
                        <th>Alamat</th>
                        <th>Nomor Hp</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                        <th>Gambar</th>
                        <th>Status Pengiriman</th>
                        <th>Bukti Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include 'koneksi.php';

                        $sql_konfirmasi = "SELECT * FROM data_konfirmasi";
                        $result_konfirmasi = $conn->query($sql_konfirmasi);

                        if ($result_konfirmasi->num_rows > 0) {
                            $no = 1;
                            while ($row = $result_konfirmasi->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $no . "</td>";
                                echo "<td>" . $row["username_konfirmasi"] . "</td>";
                                echo "<td>" . $row["barang"] . "</td>";
                                echo "<td>" . $row["alamat_konfirmasi"] . "</td>";
                                echo "<td>" . $row["no_hp_konformasi"] . "</td>";
                                echo "<td>" . $row["jumlah"] . "</td>";
                                echo "<td>Rp." . $row["harga_konfirmasi"] . "</td>";
                                echo "<td><img src='user/bukti/" . $row["gambar_konfirmasi"] . "' height='80'></td>";
                                echo "<td>" . $row["status"] . "</td>";
                                echo "<td>";
                                echo "<a class='btn-edit' href='konfirmasi.php?id_konfirmasi=" . $row["id_konfirmasi"] . "'>Cek</a>";
                                echo "</td>";
                                echo "</tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='9'>0 results</td></tr>";
                        }

                        $conn->close();
                    ?>
                </tbody>
            </table>

        </div>
    </div>
    <script>
    function konfirmasiHapus(id) {
        var hapus = confirm('Anda yakin mau menghapus data ini?');

        if (hapus) {
            window.location.href = 'hapus.php?id_produk=' + id;
        }
    }
    </script>

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