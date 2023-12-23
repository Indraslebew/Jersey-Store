<?php

include '../koneksi.php';

if(isset($_GET['id_keranjang'])) {
    $id = $_GET['id_keranjang'];

    // Ambil data jumlah_keranjang dan id_produk sebelum menghapus dari keranjang
    $query_select = "SELECT jumlah_keranjang, id_produk FROM data_keranjang WHERE id_keranjang = '$id'";
    $result_select = mysqli_query($conn, $query_select);

    if ($result_select) {
        $row = mysqli_fetch_assoc($result_select);
        $jumlah_keranjang = $row['jumlah_keranjang'];
        $id_produk = $row['id_produk'];

        // Hapus item dari keranjang
        $query_delete = "DELETE FROM data_keranjang WHERE id_keranjang = '$id'";
        $result_delete = mysqli_query($conn, $query_delete);

        if ($result_delete) {
            // Ambil nilai stok_produk saat ini
            $query_produk = "SELECT stok_produk FROM data_produk WHERE id_produk = '$id_produk'";
            $result_produk = mysqli_query($conn, $query_produk);

            if ($result_produk) {
                $row_produk = mysqli_fetch_assoc($result_produk);
                $stok_produk_sekarang = $row_produk['stok_produk'];

                // Perbaharui stok_produk dengan menambahkan jumlah yang dihapus dari keranjang
                $stok_baru = $stok_produk_sekarang + $jumlah_keranjang;
                $query_update_stok = "UPDATE data_produk SET stok_produk = '$stok_baru' WHERE id_produk = '$id_produk'";
                $result_update_stok = mysqli_query($conn, $query_update_stok);

                if ($result_update_stok) {
                    echo "<script>alert('Berhasil Menghapus dari Keranjang dan Memperbarui Stok'); window.location.href='keranjang.php';</script>";
                } else {
                    echo "Gagal memperbarui stok produk.";
                }
            } else {
                echo "Gagal mengambil data produk.";
            }
        } else {
            echo "Gagal menghapus dari keranjang.";
        }
    } else {
        echo "Gagal mengambil data keranjang.";
    }
}


?>