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

include "../koneksi.php";

// Jika tombol "Keranjang" ditekan
if (isset($_POST['submit'])) {
    $id_user = $_SESSION['id_user'];
    $kode_barang = $_POST['kode_barang'];
    $jumlah = $_POST['jumlah'];

    // Ambil data produk dari tabel data_produk
    $query_produk = "SELECT * FROM data_produk WHERE id_produk='$kode_barang'";
    $sql_produk = mysqli_query($conn, $query_produk);
    $data_produk = mysqli_fetch_assoc($sql_produk);

    // Check if the quantity purchased is greater than the available stock
    if ($jumlah > $data_produk['stok_produk']) {
        // Mengirimkan respons JSON untuk memberi tahu bahwa stok tidak mencukupi
        echo "<script>alert('Maaf, Jumlah yang dipesan tidak cukup.')</script>";
        echo "<script>history.back();</script>";
        exit();
    }

    // Update the stock in the database
    $new_stock = $data_produk['stok_produk'] - $jumlah;
    $query_update_stock = "UPDATE data_produk SET stok_produk='$new_stock' WHERE id_produk='$kode_barang'";
    $sql_update_stock = mysqli_query($conn, $query_update_stock);

    if (!$sql_update_stock) {
        // Mengirimkan respons JSON untuk memberi tahu bahwa terjadi kesalahan saat mengupdate stok
        echo "<script>alert('Gagal mengupdate stok. Error: " . mysqli_error($conn) . "')</script>";
        echo "<script>history.back();</script>";
        exit();
    }

    // Ambil data user dari tabel data_user
    $query_user = "SELECT * FROM data_user WHERE id_user='$id_user'";
    $sql_user = mysqli_query($conn, $query_user);
    $data_user = mysqli_fetch_assoc($sql_user);

    // Hilangkan karakter "." pada harga dan hitung total harga
    // Mengonversi harga per barang ke dalam format yang ditambah "000" di belakangnya
// Hilangkan karakter "." pada harga dan hitung total harga
$harga_keranjang = $data_produk['harga_produk'] * 1000; // Mengonversi harga per barang ke dalam format yang dikali 1000

$total_harga = $harga_keranjang * $jumlah; // Menghitung total harga dengan benar


    // Insert data ke tabel data_keranjang (tanpa id_admin)
$query_insert = "INSERT INTO data_keranjang (id_user, id_produk, nama_user_keranjang, nama_barang_keranjang, jumlah_keranjang, harga_keranjang, total_harga_keranjang, gambar_keranjang) VALUES ('$id_user', '$kode_barang', '".$data_user['username_user']."', '".$data_produk['nama_produk']."', '$jumlah', '$harga_keranjang', '$total_harga', '".$data_produk['gambar_produk']."')";

    $sql_insert = mysqli_query($conn, $query_insert);

    if ($sql_insert) {
        // Mengirimkan respons JSON untuk memberi tahu bahwa data berhasil dimasukkan
        echo json_encode(["status" => "success"]);
        // Arahkan pengguna ke halaman keranjang.php
        header("Location: keranjang.php");
        exit();
    } else {
        // Mengirimkan respons JSON untuk memberi tahu bahwa terjadi kesalahan
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
        exit();
    }
}
?>


<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beli Baju</title>

    <link rel="stylesheet" href="anjay.css">
</head>

<body>
    <header>
        <div class="about">
            <div class="box">
                <ul>
                    <li>Hp : 0822-1172-4366</li>
                    <li>Wa : 0822-1172-4366</li>
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

                </ul>
            </div>
            <div class="box-SingLog">
                <a href="../index.php"><button>Logout</button></a>
            </div>
        </nav>

        <div class="nm_baju">
            <h2>Nama Baju Yang Dipilih</h2>
        </div>
        <main>
            <div class="box_baju">
                <div class="img_baju">
                    <?php
            include "../koneksi.php";
            if(isset($_GET['kode_barang'])){
                $kode_barang    =$_GET['kode_barang'];
            }
            else {
                die ("Error. No ID Selected!");    
            }
            $query = "SELECT * FROM data_produk WHERE id_produk='$kode_barang'";
            $sql = mysqli_query($conn, $query);
            $data = mysqli_fetch_assoc($sql);
                    echo"<img src='../gambar/".$data['gambar_produk']."' alt='' width='300px'>";
                    ?>
                </div>

                <div class="ket_baju">
                    <p style="font-size: 18px; font-weight: bold;">Keterangan </p>
                    <?php  
            error_reporting(0);
                    echo"<p>Nama : ".$data['nama_produk'] ."</p>";
                    echo"<p>Stock : ".$data['stok_produk']."</p>";
                    echo"<p>Warna : ".$data['warna_produk']."</p>";
                    echo"<p>Ukuran : ".$data['ukuran_produk']."</p>";
                    echo "<p>Harga : Rp. " . number_format($data['harga_produk'], 0, ',', '.') . ",000</p>";

        ?>
                    <form id="keranjangForm" action="" method="post">
                        <span>Jumlah Beli :</span>
                        <input type="number" name="jumlah" class="form-control">
                        <input type="hidden" name="id" value="<?=$_GET['id'];?>">
                        <input type="hidden" name="kode_barang" value="<?=$_GET['kode_barang'];?>">
                        <div class="input">
                            <input type="reset" class="btn btn-danger" value="Batal">
                            <input type="submit" class="btn btn-success" name="submit" value="Keranjang">
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <section>
            <h2>PILIHAN <span>Produk Desain</span></h2>
        </section>
        <?php  
            include "../config.php";
            $query = "SELECT * FROM tb_barang"; 
            $sql = mysqli_query($koneksi, $query);
            while($data = mysqli_fetch_array($sql)){ //Menampilkan Semua Data Dari Table Barang
                echo "<a href='../leng-baju/leng_baju.php?kode_barang=".$data['kode_barang']."'><div class='box_card'>";
                echo "<img src='../images/".$data['gambar']."' width='100%' height='300px' style='object-fit:cover;object-position:center;'>";
                echo "<div class='nama_brg'><p>".$data['nama_barang']."</p></div>";
                echo "<div class='harga_brg'><p>Rp. ".$data['harga']."(".$data['stok'].")</p></div>";
                // echo "<p><a href='inputjumlah.php?kode_barang=".$data['kode_barang']."'><button>Beli</button></a> <a href='proses_hapus.php?kode_barang=".$data['kode_barang']."'><button>Hapus</button></a>";
                echo "</div>";
            }
        ?>
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
                        <li>PERDOS UNHAS</li>
                        <li>ButikStore@gmail.com</li>
                        <li>(0223)2804357</li>
                        <li>Hp : 089618970448</li>
                    </ul>
                </div>
                <div class="box">
                    <h3>Layanan</h3>
                    <p>Tentang Kami</p>
                    <p> Cara Pengiriman Paket Garansi Produk Seputar ButikStore</p>
                    <p>Hp : 082211724366</p>
                    <p>ButikStore@gmail.com</p>
                </div>
            </div>
        </footer>
    </header>
    <div class="title"><img src="../logolg.png" alt="" width="30px" height="30px"><span>&copy;Copyright 2022-2023
            Powered By ButikStore.com, All Rigts Reserved</span></div>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Menangkap respons dari server setelah menekan tombol "Keranjang"
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;

            // Menggunakan Fetch API untuk mengirim data form secara asynchronous
            fetch(form.action, {
                    method: form.method,
                    body: new FormData(form),
                })
                .then(response => response.json())
                .then(data => {
                    // Menampilkan alert berdasarkan respons dari server
                    if (data.status === "success") {
                        alert('Barang berhasil dimasukkan ke keranjang!');
                        // Arahkan pengguna ke halaman keranjang.php
                        window.location.href = 'keranjang.php';
                    } else {
                        alert('Gagal memasukkan barang ke keranjang. Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
    </script>


</body>

</html>