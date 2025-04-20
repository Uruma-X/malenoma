<?php
// Mulai session untuk autentikasi user
session_start();

// Contoh: Ambil user ID dari session
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
  die("Unauthorized");
}

// Simulasi koneksi ke database (ganti sesuai konfigurasi kamu)
$conn = new mysqli("localhost", "root", "", "your_database_name");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari form
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$profilePic = $_FILES['profilePic'] ?? null;

// Validasi sederhana
$errors = [];

if ($password && $password !== $confirmPassword) {
  $errors[] = "Passwords do not match!";
}

if (!empty($errors)) {
  foreach ($errors as $e) {
    echo "<p style='color:red;'>$e</p>";
  }
  exit;
}

// Simpan foto profil jika diupload
if ($profilePic && $profilePic['error'] === UPLOAD_ERR_OK) {
  $targetDir = "uploads/";
  if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
  }

  $fileName = basename($profilePic["name"]);
  $targetFile = $targetDir . time() . "_" . $fileName;
  move_uploaded_file($profilePic["tmp_name"], $targetFile);

  // Update profil di database
  $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
  $stmt->bind_param("si", $targetFile, $userId);
  $stmt->execute();
  $stmt->close();
}

// Update username/email/password jika ada perubahan
if ($username) {
  $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
  $stmt->bind_param("si", $username, $userId);
  $stmt->execute();
  $stmt->close();
}

if ($email) {
  $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
  $stmt->bind_param("si", $email, $userId);
  $stmt->execute();
  $stmt->close();
}

if ($password) {
  $hashed = password_hash($password, PASSWORD_DEFAULT);
  $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->bind_param("si", $hashed, $userId);
  $stmt->execute();
  $stmt->close();
}

$conn->close();
echo "<script>alert('Settings updated successfully!'); window.location.href='settings.php';</script>";
?>
