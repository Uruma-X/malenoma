<?php 
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $login = mysqli_real_escape_string($conn, $_POST['username']); // sesuaikan nama field
  $password = $_POST['password']; 
  
  $sql = "SELECT * FROM users WHERE username='$login' OR email='$login'"; 
  $result = mysqli_query($conn, $sql);
  
  if (!$result) {
      die("Query error: " . mysqli_error($conn));
  }
  
  $user = mysqli_fetch_assoc($result);
  if ($user) {
      // User ditemukan, cek password
      if (password_verify($password, $user['password'])) {
          // Password benar
          $_SESSION['username'] = $user['username']; 
          $_SESSION['email'] = $user['email'];
          header("Location: dashboard.php");
          exit();
      } else {
          // Password salah
          echo "<script>alert('Password yang dimasukkan salah!'); window.location='login.php';</script>";
      }
  } else {
      // User tidak ditemukan
      echo "<script>alert('Username/email tidak ditemukan!'); window.location='login.php';</script>";
  }
}

mysqli_close($conn);
?>



<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Glassmorphism Login Form</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="wrapper">
    <form action="login.php" method="POST">
      <h2>Login</h2>
      <div class="input-field">
        <input type="text" name="username" required>
        <label>Masukkan username atau email</label>
      </div>
      <div class="input-field">
        <input type="password" name="password" required>
        <label>Masukkan password</label>
      </div>
      <div class="forget">
        <label for="remember">
          <input type="checkbox" id="remember">
          <p>Ingat saya</p>
        </label>
        <a href="#">Lupa password?</a>
      </div>
      <button type="submit">Log In</button>
      <div class="register">
        <p>Belum punya akun? <a href="register.php">Daftar</a></p>
      </div>
    </form>
  </div>
</body>
</html>
