<?php
include "../koneksi.php";
session_start();
$user = $_SESSION['id_user'];

$person = $conn->query("SELECT * FROM data_user WHERE id_user = '$user' ");

if (mysqli_num_rows($person) == 0) {
    echo '<div class="alert alert-warning">User not found in the database.</div>';
    exit();
} else {
    $set = mysqli_fetch_assoc($person);
}

$cs = $set['id_user'];

if (isset($_POST['terima'])) {
    $id_konfirmasi = $_POST['terima'];

    $status_query = $conn->query("SELECT status FROM data_konfirmasi WHERE id_konfirmasi = '$id_konfirmasi'");
    $status_data = mysqli_fetch_assoc($status_query);
    $status = $status_data['status'];

    if ($status == 'Di kirim') {
        // Tambahkan konfirmasi alert sebelum pembaruan status
        echo '<script>
            if (confirm("Apakah Anda yakin paket sudah Di Terima?")) {
                window.location.href = "update_status.php?id=' . $id_konfirmasi . '";
            }
        </script>';
    } else {
        echo '<div class="alert alert-warning">Status harus "Di kirim" untuk menandai sebagai "Di Terima".</div>';
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>TRANSAKSI</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        padding-top: 20px;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
    }

    .btn-back {
        margin-bottom: 20px;
        background-color: #6c757d;
        color: #fff;
        border-color: #6c757d;
        padding: 8px 20px;
        text-transform: uppercase;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background-color: #495057;
        border-color: #495057;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .table thead th {
        background-color: #007bff;
        color: #fff;
        font-weight: bold;
        text-transform: uppercase;
        border: none !important;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table th,
    .table td {
        padding: 12px;
        text-align: center;
        border: none !important;
    }

    .table img {
        max-width: 100px;
        height: auto;
    }

    .btn-terima {
        width: 80px;
        font-size: 0.85rem;
    }

    .item {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 30px;
    }
    </style>
</head>

<body>
    <form method="post">
        <div class="item">
            <h2>DATA PEMBAYARAN</h2><br>
            <a href="keranjang.php" class="btn btn-outline-secondary"><b>Kembali</b></a><br><br>
            <table class="table table-bordered border-primary">
                <thead>
                    <th>No</th>
                    <th>ID Konfirmasi</th>
                    <th>Nama</th>
                    <th>Barang</th>
                    <th>Alamat</th>
                    <th>No. HP</th>
                    <th>Harga</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </thead>

                <?php
                $no = 1;
                $query = $conn->query("SELECT * FROM data_konfirmasi WHERE id_user = '$cs'");
                while ($data = mysqli_fetch_array($query)) {
                ?>
                <tbody>
                    <td><?= $no++ ?></td>
                    <td><?= $data['id_konfirmasi'] ?></td>
                    <td><?= $data['username_konfirmasi'] ?></td>
                    <td><?= $data['barang'] ?></td>
                    <td><?= $data['alamat_konfirmasi'] ?></td>
                    <td><?= $data['no_hp_konformasi'] ?></td>
                    <td><?= $data['harga_konfirmasi'] ?></td>
                    <td><img src='bukti/<?= $data['gambar_konfirmasi'] ?>' alt='Image' width='100px' height='100px'>
                    </td>
                    <td><?= $data['status'] ?></td>
                    <td>
                        <?php if ($data['status'] == 'Di kirim') { ?>
                        <button type='submit' name='terima' value='<?= $data['id_konfirmasi'] ?>'
                            class='btn btn-success'>Terima</button>
                        <?php } ?>
                    </td>
                </tbody>
                <?php } ?>
            </table>
        </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>