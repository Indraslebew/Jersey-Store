<?php
include "../koneksi.php";

// Start the session
session_start();

// Assuming $_SESSION['id_user'] is the session variable storing the logged-in user's id
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}

$query = "SELECT data_keranjang.id_keranjang, data_keranjang.nama_user_keranjang, data_keranjang.id_user, data_produk.nama_produk, data_keranjang.jumlah_keranjang, data_produk.harga_produk, data_keranjang.total_harga_keranjang
          FROM data_keranjang
          INNER JOIN data_produk ON data_keranjang.id_produk = data_produk.id_produk
          WHERE data_keranjang.id_user = '$id_user'";


$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang</title>
    <link rel="stylesheet" href="style.css">
    <style>
    /* Tambahkan gaya untuk tombol Checkout */
    .checkout-button {
        background-color: orange;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
    }

    /* Gaya tambahan jika tombol dihover */
    .checkout-button:hover {
        filter: brightness(80%);
    }

    .delete-button {
        background-color: #f44336;
        border: none;
        color: white;
        padding: 8px 16px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
    }

    /* Gaya tambahan jika tombol dihover */
    .delete-button:hover {
        background-color: #d32f2f;
    }
    </style>

</head>
<style>
#customers {
    width: 100%;
    margin-bottom: 30px;
}

table {
    width: 100%;
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
}

#customers td,
#customers th {
    border: 1px solid #ddd;
    padding: 8px;
}

#customers tr:nth-child(even) {
    background-color: #f2f2f2;
}

#customers tr:hover {
    background-color: #ddd;
}

#customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #04AA6D;
    color: white;
}
</style>

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
            <div class="box-logo">

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
        <main>
            <section>
                <h3>LIST PRODUK DI KERANGJANG</h3>
                <hr>
                <h2>DAFTAR PRODUK DI BELI</h2>
            </section>
            <div id="customers">
                <?php if(mysqli_num_rows($result) > 0) { ?>
                <table border="1">
                    <tr>
                        <th>Nama User</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                    <?php  
                while ($data = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $data['nama_user_keranjang'] . "</td>";
                    echo "<td>" . $data['nama_produk'] . "</td>";
                    echo "<td>" . $data['jumlah_keranjang'] . "</td>";
                    echo "<td>Rp." . number_format($data['harga_produk'], 3, ',', '.') . "</td>";
echo "<td>Rp." . number_format($data['total_harga_keranjang'], 0, ',', '.') . "</td>";


                    echo '<td>
                            <a href="delete.php?id_keranjang='.$data['id_keranjang'].'" onclick="return confirm(\'Yakin ingin menghapus data ini\')" style= background color: red>Hapus</a>
                            
                          </td>';
                    echo "</tr>";
                }
                ?>
                </table>
                <br><br>
                <button class="checkout-button" onclick="window.location.href='faktur.php'">Checkout</button>

                <?php } else { ?>
                <p>Tidak ada produk di keranjang.</p>
                <?php } ?>
            </div>
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
                    <p> Cara Pengiriman Paket Garansi Produk Seputar jersy store</p>
                    <p>Hp : 082271355344</p>
                </div>
            </div>
        </footer>
    </header>

    <script src="../slides.js"></script>
</body>

</html>