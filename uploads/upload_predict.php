<?php
// Konfigurasi API Flask
$apiUrl = 'http://127.0.0.1:5000/predict'; // Ganti jika Flask jalan di IP lain

// Periksa apakah file diupload
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];

    // Inisialisasi cURL
    $ch = curl_init();

    // Siapkan file untuk dikirim
    $cFile = new CURLFile($fileTmpPath, $_FILES['image']['type'], $fileName);

    // Data POST ke Flask
    $postData = ['image' => $cFile];

    // Konfigurasi cURL
    curl_setopt_array($ch, [
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData
    ]);

    // Eksekusi dan ambil respon
    $response = curl_exec($ch);
    $error = curl_error($ch);

    curl_close($ch);

    // Tampilkan hasil
    if ($error) {
        echo "Error: $error";
    } else {
        $result = json_decode($response, true);
        if (isset($result['prediction'])) {
            echo "<h2>Hasil Prediksi:</h2>";
            echo "<p><strong>Kategori:</strong> " . htmlspecialchars($result['prediction']) . "</p>";
            echo "<p><strong>Kepercayaan:</strong> " . round($result['confidence'] * 100, 2) . "%</p>";
        } elseif (isset($result['error'])) {
            echo "<p><strong>Error:</strong> " . htmlspecialchars($result['error']) . "</p>";
        } else {
            echo "<p>Respon tidak dikenali.</p>";
        }
    }
} else {
    echo "<p>Tidak ada file yang diupload atau terjadi kesalahan.</p>";
}
?>
<form method="post" action="upload_predict.php" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/*" required>
    <button type="submit">Upload & Prediksi</button>
</form>
