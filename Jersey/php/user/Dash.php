<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include '../koneksi.php';

// Redirect to login page if 'id_user' is not set in the session
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member / User</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="about">
            <div class="box">
                </ul>
            </div>
        </div>
        <nav>
            <div class="box-logo">
                <img src="../logods.png" alt="" width="200px">
            </div>
            <div class="box-nav">
                <ul>

                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li><a href="Dash.php">HOME</a></li>
                    <li><a href="keranjang.php">KERANJANG</a></li>
                    <li><a href="detail.php">PESANAN</a></li>
                </ul>
            </div>
            <div class="box-SingLog">
                <a href="../index.php"><button>Logout</button></a>
            </div>

        </nav>
        <div class="slider">
            <img class="mySlides" src="main.jpg" style="width:100%;height: 500px;object-fit: cover;">
            <img class="mySlides" src="xr7.jpg" style="width:100%;height: 500px;object-fit: cover;">
            <img class="mySlides" src="we2.png" style="width:100%;height: 500px;object-fit: cover;">
        </div>
        <main>
            <section>
                <h3><span>SELAMAT BERBELANJA DI ENREKANG JERSY STORE</span></h3>
                <p>KAMI BESAR DENGAN KEPERCAYAAN PELANGGAN DAN KAMI MEMILIKI VISI UNTUK MENGEMBANGKAN PRODUK KAMI</p>
                <hr>
                <h2>PILIHAN <span>Produk Kami</span></h2>
            </section>

            <?php  
            include "../koneksi.php";  // Assuming this file includes your database connection logic

            $query = "SELECT * FROM data_produk";  // Updated query to fetch data from data_produk table
            $sql = mysqli_query($conn, $query);

            while ($data = mysqli_fetch_array($sql)) {
                echo "<a href='pilih.php?kode_barang=" . $data['id_produk'] . "'><div class='box_card'>";
                echo "<img src='../gambar/" . $data['gambar_produk'] . "' width='100%' height='300px' style='object-fit:cover;object-position:center;'>";
                echo "<div class='nama_brg'><p>" . $data['nama_produk'] . "</p></div>";
                echo "<div class='harga_brg'><p>Rp. " . number_format($data['harga_produk'], 0, ',', '.') . ",000 (" . $data['stok_produk'] . ")</p></div>";
                // Add other product details as needed
                echo "</div></a>";
            }

            
        ?>
        </main>

        <div class="clear" style="clear:both"></div>
        <footer>
            <div class="box-footer">
                <div class="box">
                    <h3>Follow US</h3>
                    <ul>
                        <li>Facebook</li>
                        <li>Instagram</li>
                        <li>Twitter</li>
                    </ul>
                </div>
                <div class="box">
                    <h3>Address</h3>
                    <ul>
                        <li>Enrekang</li>
                        <li>jersy_store@gmail.com</li>
                        <li>Hp : 082271355344</li>
                    </ul>
                </div>
                <div class="box">
                    <h3>Layanan</h3>
                    <p>Tentang Kami</p>
                    <p> Cara Pengiriman Paket Garansi Produk Seputar Jersy Store</p>
                    <p>Hp : 082271355344</p>
                    <p>jersy_store@gmail.com</p>
                </div>
            </div>
        </footer>

        <script src="slides.js"></script>
        <script>
        var myIndex = 0;
        carousel();

        function carousel() {
            var i;
            var x = document.getElementsByClassName("mySlides");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            myIndex++;
            if (myIndex > x.length) {
                myIndex = 1
            }
            x[myIndex - 1].style.display = "block";
            setTimeout(carousel, 2000); // Change  every 2 seconds
        }
        </script>
</body>

</html>