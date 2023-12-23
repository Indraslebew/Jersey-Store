<!DOCTYPE html>
<html>

<head>
    <title>Checkout</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 16px;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header h1 {
        font-size: 36px;
        margin-bottom: 10px;
        color: #333;
    }

    .header p {
        font-size: 18px;
        margin: 0;
        color: #666;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 16px;
    }

    table th,
    table td {
        border: 1px solid #ccc;
        padding: 12px;
        text-align: left;
    }

    table th {
        background-color: #f2f2f2;
    }

    table td:last-child {
        text-align: right;
    }

    .footer {
        text-align: center;
        margin-top: 20px;
    }

    .footer button {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .footer button:hover {
        background-color: #45a049;
    }
    </style>
</head>

<body>
    <center>
        <div class="container">
            <div class="header">
                <h1>Enrekang Jersey</h1>
                <p>Alamat Toko: jln.poros enrekang toraja<br>Telp: 085398890213</p><br>
                <h2>CHECKOUT</h2>
            </div>
            <?php
            session_start();

            include "../koneksi.php";

            // Periksa apakah pengguna telah login, jika belum, alihkan ke halaman login
            if (!isset($_SESSION['id_user'])) {
            header("Location: login.php"); // Ganti login.php dengan halaman login Anda
            exit;
            }

            $id_user = $_SESSION['id_user'];

            // Fetch data dari database table berdasarkan id_user dari session
            $sql = "SELECT * FROM data_keranjang WHERE id_user = $id_user";
            $result = $conn->query($sql);


            if ($result) {
            if ($result->num_rows > 0) {
            $total = 0;
            ?>
            <table>
                <tr align='center'>
                    <th>Nama Pembeli</th>
                    <th>kode Keranjang</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah Keranjang</th>
                    <th>Total Harga</th>
                </tr>
                <?php
                        while ($row = $result->fetch_assoc()) {
                            ?>
                <tr>
                    <td><?php echo $row["nama_user_keranjang"]; ?></td>
                    <td><?php echo $row["id_keranjang"]; ?></td>
                    <td><?php echo $row["id_produk"]; ?></td>
                    <td><?php echo $row["nama_barang_keranjang"]; ?></td>
                    <td>Rp.<?php echo number_format($row["harga_keranjang"]); ?></td>
                    <td><?php echo $row["jumlah_keranjang"]; ?></td>
                    <td style='text-align:right'>Rp.<?php echo number_format($row["total_harga_keranjang"]); ?></td>
                </tr>

                <?php
                            $total += (float)$row["total_harga_keranjang"];
                        }
                        ?>
                <tr>
                    <td colspan='6' style='text-align:right'><b>Total Yang Harus Di Bayar Adalah :</b></td>
                    <td style='text-align:right'>Rp.<?php echo number_format($total); ?></td>
                </tr>
                <tr>
                    <td colspan='6' style='text-align:right'><b>Rekening tujuan : 503601053996537 A.N. Muh Ikhsan</b>

                </tr>
            </table>
            <?php
                } else {
                    echo "No data found";
                }
            } else {
                echo "Error executing the query: " . $conn->error;
            }
            ?>
            <div class="footer">
                <button onclick="window.location.href='beli.php'">Bayar sekarang</button>
            </div>

            <?php
            $conn->close();
            ?>
        </div>
    </center>
</body>

</html>