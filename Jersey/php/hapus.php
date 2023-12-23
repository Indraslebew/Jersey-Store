<?php
include 'koneksi.php';

if (isset($_GET['id_produk'])) {
    $id_produk = $_GET['id_produk'];

    // Hapus data produk berdasarkan ID produk
    $sql = "DELETE FROM data_produk WHERE id_produk = $id_produk";

    if ($conn->query($sql) === TRUE) {
        echo "Data produk berhasil dihapus.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "ID produk tidak ditemukan.";
}

header("Location: produk.php");
?>