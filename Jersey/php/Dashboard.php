<?php
require 'koneksi.php';

// Ambil jumlah dari masing-masing entitas
$sql_user = "SELECT COUNT(*) AS total_user FROM data_user";
$result_user = $conn->query($sql_user);
$row_user = $result_user->fetch_assoc();

$sql_produk = "SELECT COUNT(*) AS total_produk FROM data_produk";
$result_produk = $conn->query($sql_produk);
$row_produk = $result_produk->fetch_assoc();

$sql_keranjang = "SELECT COUNT(*) AS total_keranjang FROM data_keranjang";
$result_keranjang = $conn->query($sql_keranjang);
$row_keranjang = $result_keranjang->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <style>
    body {
        background-color: #E6E6FA;
    }
    </style>
    <meta charset="UTF-8" />
    <title>Index</title>
    <link rel="stylesheet" href="dash.css" />
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

        <section class="main">
            <div class="main-skills">
                <canvas id="chart"></canvas>
                <div class="card user-card" onclick="window.location.href='user.php'"">
                    <i class="fas fa-user card-logo"></i>
                    <h3>User</h3>
                    <p><b><?php echo $row_user['total_user']; ?></b></p>
                </div>
                <div class="card produk-card" onclick="window.location.href='produk.php'">
                    <i class="fas fa-cube card-logo"></i>
                    <h3>Produk</h3>
                    <p><b><?php echo $row_produk['total_produk']; ?></b></p>
                </div>
                <div class="card keranjang-card" onclick="window.location.href='keranjang.php'">
                    <i class="fas fa-shopping-cart card-logo"></i>
                    <h3>Keranjang</h3>
                    <p><b><?php echo $row_keranjang['total_keranjang']; ?></b></p>
                </div>
            </div>
        </section>
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