<?php
$targetDir = "uploads";
$maxFileSize = 5 * 1024 * 1024; // 5MB

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["image"])) {
    $file = $_FILES["image"];
    $fileName = basename($file["name"]);
    $targetFile = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $allowedTypes = ["jpg", "jpeg", "png"];
    if (!in_array($fileType, $allowedTypes)) {
        echo "File type not allowed.";
        exit;
    }

    if ($file["size"] > $maxFileSize) {
        echo "File is too large.";
        exit;
    }

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        echo "Upload successful: $targetFile";
    } else {
        echo "Failed to upload file.";
    }
} else {
    echo "No file uploaded.";
}
?>
