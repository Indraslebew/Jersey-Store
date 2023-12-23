<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jersi Store Enrekang</title>
    <link rel="stylesheet" type="text/css" href="login.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>

<body>
    <div class="p">
        <form action="cek_login.php" method="POST">
            <h2>JERSI STORE ENREKANG</h2><br>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required />
            </div>
            <div class="input-box">
                <input type="password" name="password" id="password" placeholder="Password" required />
                <div class="show-password" onclick="togglePassword()">
                    <i class='bx bx-hide'></i>
                </div>
            </div>
            <div class="input-box">
                <select name="kategori" class="select-box" required>
                    <option value="" disabled selected>Kategori</option>
                    <option value="Admin">Admin</option>
                    <option value="User">User</option>
                </select>
            </div>
            <button type="submit" name="login" class="btn">Login</button>
            <button type="button" class="btnn" onclick="window.location.href='register.php'">Register</button>
        </form>
    </div>

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
