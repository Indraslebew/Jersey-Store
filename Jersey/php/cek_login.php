<?php
session_start();
include "koneksi.php"; 

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $kategori = $_POST['kategori'];

    if ($kategori == "Admin") {
        $stmt = $conn->prepare("SELECT * FROM data_admin WHERE username_admin = ? AND password_admin = ?");
        $dashboard = "Dashboard.php";
    } elseif ($kategori == "User") {
        $stmt = $conn->prepare("SELECT * FROM data_user WHERE username_user = ? AND password_user = ?");
        $dashboard = "user/Dash.php";
    }

    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Set session variables based on user type
        if ($kategori == "Admin") {
            $_SESSION['id_admin'] = $row['id_admin'];
        } elseif ($kategori == "User") {
            $_SESSION['id_user'] = $row['id_user'];
        }

        header("Location: $dashboard");
        exit();
    } else {
        echo "<script>alert('Login gagal, pastikan data Anda benar!'); history.back();</script>";
    }
}
?>
