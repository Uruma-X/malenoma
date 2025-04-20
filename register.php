<?php
ob_start();
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "data_db";

// Koneksi ke database
$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Variabel untuk menyimpan pesan error
$error = "";

// Fungsi validasi password
function validasiPassword($password) {
    if (!preg_match('/[A-Z]/', $password)) {
        return "Password harus memiliki setidaknya 1 huruf kapital!";
    }
    if (!preg_match('/[0-9]/', $password)) {
        return "Password harus memiliki setidaknya 1 angka!";
    }
    if (!preg_match('/[\W]/', $password)) { // \W = simbol
        return "Password harus memiliki setidaknya 1 simbol!";
    }
    return true;
}

// Proses registrasi jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];

    // Validasi password sebelum hashing
    $validasi = validasiPassword($password);
    if ($validasi !== true) {
        $error = $validasi;
    } else {
        // Hash password setelah validasi sukses
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah username atau email sudah terdaftar
        $checkQuery = "SELECT * FROM users WHERE username='$username' OR email='$email'";
        $result = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username atau Email sudah terdaftar!";
        } else {
            // Simpan user baru ke database
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";

            if (mysqli_query($conn, $sql)) {
                $_SESSION["username"] = $username;
                header("Location: dashboard.php"); // Redirect ke dashboard
                exit();
            } else {
                $error = "Terjadi kesalahan, silakan coba lagi.";
            }
        }
    }
}

mysqli_close($conn);
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <form action="register.php" method="POST">
            <h2>Register</h2>

            <div class="input-field">
                <input type="text" name="username" required>
                <label>Enter your username</label>
            </div>

            <div class="input-field">
                <input type="email" name="email" required>
                <label>Enter your email</label>
            </div>

            <div class="input-field">
                <input type="password" name="password" required>
                <label>Create a password</label>
                 <?php if (!empty($error)): ?>
                    <span class="error"><?php echo $error; ?></span>
                <?php endif; ?>
            </div>


            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
