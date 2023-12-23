<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Produk</title>
    <link rel="stylesheet" href="produk.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body>
    <?php
    session_start();
    include "koneksi.php";

    // Pastikan user sudah login
    if (!isset($_SESSION['id_admin'])) {
        echo "<script>alert('Mohon login terlebih dahulu.'); window.location.href='login.php';</script>";
        exit();
    }

    $id_admin = $_SESSION['id_admin'];

    // Periksa aktivitas user
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_regenerate_id(true);
        $_SESSION['last_activity'] = time();
    }

    $id_admin = $_SESSION['id_admin'];

    $sql = "SELECT username_admin FROM data_admin WHERE id_admin = $id_admin";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
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
                <li>
                    <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
                    <a href="#" class="logout" onclick="konfirmasiLogout()">
                        <span class="nav-item">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="main">
            <button class="btn-tambah" onclick="window.location.href='tambah.php'">
                Tambah</button><br /><br />
            <form class="search-form" method="get" action="produk.php">
                <input type="text" name="search" placeholder="Cari..." />
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            <table class="lapangan-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Stok</th>
                        <th>Warna</th>
                        <th>Ukuran</th>
                        <th>Harga</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    include 'koneksi.php';

                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $search = $_GET['search'];
                        $sql_produk = "SELECT * FROM data_produk WHERE nama_produk LIKE '%$search%'";
                    } else {
                        $sql_produk = "SELECT * FROM data_produk";
                    }
                    $result_produk = $conn->query($sql_produk);

                    if ($result_produk->num_rows > 0) {
                        $no = 1;
                        while ($row = $result_produk->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $no . "</td>";
                            echo "<td>" . $row["nama_produk"] . "</td>";
                            echo "<td>" . $row["stok_produk"] . "</td>";
                            echo "<td>" . $row["warna_produk"] . "</td>";
                            echo "<td>" . $row["ukuran_produk"] . "</td>";
echo "<td>Rp." . number_format($row["harga_produk"], 2, ',', '.') . "</td>";
                            echo "<td><img src='gambar/" . $row["gambar_produk"] . "' height='80'></td>";
                            echo "<td>";
                            echo "<button class='btn-edit' onclick=\"window.location.href='edit.php?id_produk=" . $row["id_produk"] . "'\">Edit</button>";
                            echo "<button class='btn-hapus' onclick=\"konfirmasiHapus(" . $row["id_produk"] . ")\">Hapus</button>";
                            echo "</td>";
                            echo "</tr>";
                            $no++;
                        }
                    } else {
                        echo "0 results";
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