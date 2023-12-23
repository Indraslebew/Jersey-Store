<?php
include "../koneksi.php";

if (isset($_GET['id'])) {
    $id_konfirmasi = $_GET['id'];

    $update_query = $conn->query("UPDATE data_konfirmasi SET status = 'Di Terima' WHERE id_konfirmasi = '$id_konfirmasi'");
    if ($update_query) {
        header("Location: " . $_SERVER['HTTP_REFERER']); // Redirect to the previous page after the update
        exit();
    } else {
        echo '<div class="alert alert-danger">Gagal memperbarui status.</div>';
    }
}
?>