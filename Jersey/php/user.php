<?php
session_start();
include "koneksi.php";

// Check if the user is logged in
if (!isset($_SESSION['id_admin'])) {
    echo "<script>alert('Mohon login terlebih dahulu.'); window.location.href='login.php';</script>";
    exit();
}

$id_admin = $_SESSION['id_admin'];

// Check if the user has been active recently (adjust the time as needed)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // If inactive for more than 30 minutes (1800 seconds), regenerate session ID
    session_regenerate_id(true);
    $_SESSION['last_activity'] = time(); // Update last activity timestamp
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>User</title>
    <link rel="stylesheet" href="produk.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body>
    <div class="container">
        <nav>

            <ul>
                <li>
                    <a href="Dashboard.php" class="active">
                        <span class="nav-item">Home</span>
                    </a>
                </li>
                <li>
                    <a href="produk.php">
                        <span class="nav-item">Produk</span>
                    </a>
                </li>
                <li>
                    <a href="keranjang.php">
                        <span class="nav-item">Keranjang</span>
                    </a>
                    <li>
                    <a href="user.php">
                        <span class="nav-item">User</span>
                    </a>
                </li>
                </li><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                <li>
                    <a href="#" class="logout" onclick="konfirmasiLogout()">
                        <span class="nav-item">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="main">
            <br />
            <form class="search-form" method="get" action="keranjang.php">
                <input type="text" name="search" placeholder="Cari..." />
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
            <table class="lapangan-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID User</th>
                        <th>Nama</th>
                        <th>Password</th>
                        <th>Nomor Telepon</th>
                        <th>Alamat</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if (isset($_GET['search']) && !empty($_GET['search'])) {
                        $search = $_GET['search'];
                        $sql_user = "SELECT * FROM data_user WHERE username_user LIKE '%$search%'";
                    } else {
                        $sql_user = "SELECT * FROM data_user";
                    }

                    $result_user = $conn->query($sql_user);

                    if ($result_user->num_rows > 0) {
                        $nomor = 1;
                        while ($row = $result_user->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $nomor . "</td>";
                            echo "<td>" . $row["id_user"] . "</td>";
                            echo "<td>" . $row["username_user"] . "</td>";
                            echo "<td>" . $row["password_user"] . "</td>";
                            echo "<td>" . $row["no_telp_user"] . "</td>";
                            echo "<td>" . $row["alamat_user"] . "</td>";
                            echo "</tr>";
                            $nomor++;
                        }
                    } else {
                        echo "0 results";
                    }

                    $conn->close();
                    ?>

                </tbody>
            </table>
        </div>
    </div>
    <script>
    function konfirmasiHapus(id) {
        var hapus = confirm('Anda yakin mau menghapus data ini?');

        if (hapus) {
            window.location.href = 'hapus.php?id_produk=' + id;
        }
    }
    </script>

    <script>
    function konfirmasiLogout() {
        var logout = confirm('Anda ingin logout?');

        if (logout) {
            window.location.href = 'login.php';
        }
    }
    </script>
</body>

</html>