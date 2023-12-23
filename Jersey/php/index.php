<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JERSY STORE</title>
    <link rel="stylesheet" href="../styless.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="slides.js"></script>
    <style>
    .mySlides {
        display: none;
    }
    </style>
</head>

<body>

    <header>
        <div class="about">
            <div class="box">
                <ul>
                    <li>Hp : 082271355344</li>
                    <li>Wa : 082271355344</li>
                </ul>
            </div>
        </div>
        <nav>

            <div class="box-nav">
                <ul>
                    <li>
                    <li>
                    <li>
                    <li>
                    <li>
                    <li>
                    <li>
                    <li>
                    <li>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>


        </nav>
        <div class="slider">
            <img class="mySlides" src="user/main.jpg" style="width:100%;height: 500px;object-fit: cover;">
            <img class="mySlides" src="user/xr7.jpg" style="width:100%;height: 500px;object-fit: cover;">
            <img class="mySlides" src="user/we2.png" style="width:100%;height: 500px;object-fit: cover;">
        </div>
        <main>
            <section>
                <h3><span>SELAMAT BERBELANJA DI MAMASA JERSY STORE</span></h3>
                <p>KAMI BESAR DENGAN KEPERCAYAAN PELANGGAN DAN KAMI MEMILIKI VISI UNTUK MENGEMBANGKAN PRODUK KAMI</p>
                <hr>
                <h2>PILIHAN <span>Produk Kami</span></h2>
            </section>

            <?php  
            include "koneksi.php";  // Assuming this file includes your database connection logic

            $query = "SELECT * FROM data_produk";  // Updated query to fetch data from data_produk table
            $sql = mysqli_query($conn, $query);

            while ($data = mysqli_fetch_array($sql)) {
    echo "<a href='leng-baju/leng_baju.php?kode_barang=" . $data['id_produk'] . "'><div class='box_card'>";
    echo "<img src='gambar/" . $data['gambar_produk'] . "' width='100%' height='300px' style='object-fit:cover;object-position:center;'>";
    echo "<div class='nama_brg'><p>" . $data['nama_produk'] . "</p></div>";
    // Tambahkan penambahan ",000" pada harga produk di bawah ini
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
                        <li>Mamasa</li>
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