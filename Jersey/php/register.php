<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registration Form</title>
    <link rel="stylesheet" type="text/css" href="regis.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@iconify/icons-bx/1.0.0/dist/icons.css">
</head>

<body>
    <h2>User Registration Form</h2>

<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    include 'koneksi.php';

    // Get user input from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Check if the username already exists
    $check_username_query = "SELECT * FROM `data_user` WHERE `username_user` = '$username'";
    $check_username_result = $conn->query($check_username_query);

    if ($check_username_result->num_rows > 0) {
        echo "<p>Username '$username' already exists. Please choose a different username.</p>";
    } else {
        // Insert user data into the database without hashing the password
        $insert_query = "INSERT INTO `data_user` (`username_user`, `password_user`, `no_telp_user`, `alamat_user`) 
                        VALUES ('$username', '$password', '$phone', '$address')";

        if ($conn->query($insert_query) === TRUE) {
            echo "<p>Registration successful! Redirecting to login page...</p>";
            header("refresh:2;url=login.php"); // Redirect to login page after 2 seconds
            exit();
        } else {
            echo "Error: " . $insert_query . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>


    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="password">Password:</label>
        <div class="password-container">
            <input type="password" name="password" id="password" required>
            <div class="show-password" onclick="togglePassword()">
                    <i class='bx bx-hide'></i>
            </div>
        </div>

        <label for="phone">Nomor HandPhone:</label>
        <input type="text" name="phone" required><br>

        <label for="address">Alamat:</label>
        <input type="text" name="address" required><br>

        <!-- Move the submit button below the "Show Password" feature -->
        <input type="submit" value="Register">
    </form>

      <script>
        function togglePassword() {
            var passwordInput = document.getElementById('password');
            var icon = document.querySelector('.show-password i');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                passwordInput.type = "password";
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }
    </script>
</body>

</html>
