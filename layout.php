<!-- filepath: c:\xampp\htdocs\Web1\layout.php -->
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[url('Jpg1.jpg')] bg-cover bg-center h-screen text-white">
    <!-- Navigasi -->
    <nav class="fixed top-0 z-50 w-full bg-white bg-opacity-80 backdrop-blur border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center justify-between px-4 py-3">
            <a href="dashboard.php" class="text-lg font-bold text-gray-800 dark:text-white">Dashboard</a>
            <div class="flex space-x-4">
                <a href="dashboard.php" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:underline">Home</a>
                <a href="Penyakit.php" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:underline">Penyakit</a>
            </div>
        </div>
    </nav>

    <!-- Container Utama -->
    <div class="pt-20 px-4">
        <?php include($pageContent); ?>
    </div>
</body>
</html>