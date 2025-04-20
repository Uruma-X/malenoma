<!-- filepath: [Penyakit.php](http://_vscodecontentref_/2) -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php include('navbar.php'); ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Penyakit</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[url('Jpg1.jpg')] bg-cover bg-center h-screen text-white">
    

    <!-- filepath: c:\xampp\htdocs\Web1\Penyakit.php -->
<div class="pt-20 px-4">
    <div class="bg-black bg-opacity-60 p-8 rounded-lg shadow-xl w-full max-w-4xl text-center mx-auto">
        <h1 class="text-4xl font-semibold mb-6">Informasi tentang Melanoma</h1>
        <p class="text-lg mb-8">Melanoma adalah jenis kanker kulit yang berkembang dari sel pigmen yang disebut melanosit. Biasanya, melanoma muncul sebagai bintik atau tahi lalat yang tampak berbeda dari yang lainnya.</p>

        <div class="space-y-6">
            <div>
                <h2 class="text-2xl font-semibold mb-4">Apa itu Melanoma?</h2>
                <p class="text-lg">Melanoma adalah kanker yang dapat berkembang di kulit, tetapi juga dapat terjadi di mata atau bagian tubuh lain yang tidak terlihat. Ini adalah salah satu jenis kanker kulit yang paling berbahaya karena kemampuannya untuk menyebar ke bagian tubuh lain jika tidak segera ditangani.</p>
            </div>

            <div>
                <h2 class="text-2xl font-semibold mb-4">Gejala Melanoma</h2>
                <p class="text-lg">Ciri-ciri melanoma sering kali muncul sebagai perubahan pada tahi lalat atau kulit yang ada, yang menjadi tidak simetris, memiliki tepi yang tidak teratur, dan variasi warna yang mencolok.</p>
            </div>

            <div>
                <h2 class="text-2xl font-semibold mb-4">Penyebab dan Faktor Risiko</h2>
                <p class="text-lg">Penyebab utama melanoma adalah paparan sinar UV yang berlebihan dari matahari atau solarium. Faktor risiko lainnya termasuk riwayat keluarga, memiliki banyak tahi lalat, atau kulit yang mudah terbakar.</p>
            </div>
        </div>
    </div>
</div>x
</body>
</html>