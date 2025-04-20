
<?php
session_start();
session_destroy();
header("Location: login.php"); // Kembali ke halaman login setelah logout
exit();
?>
