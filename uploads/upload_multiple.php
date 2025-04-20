<?php
header('Content-Type: application/json');

// Konfigurasi
$uploadDir = __DIR__ . '/'; // simpan di folder 'uploads'
$uploadUrlBase = 'uploads/'; // relative URL ke file
$apiEndpoint = 'http://127.0.0.1:5000/predict'; // Flask API

$response = [
  'success' => false,
  'files' => []
];

// Cek apakah file dikirim
if (!isset($_FILES['images'])) {
  $response['message'] = 'No files uploaded.';
  echo json_encode($response);
  exit;
}

$files = $_FILES['images'];
$total = count($files['name']);

for ($i = 0; $i < $total; $i++) {
  $name = basename($files['name'][$i]);
  $tmpName = $files['tmp_name'][$i];
  $size = $files['size'][$i];
  $type = $files['type'][$i];
  $error = $files['error'][$i];

  $result = [
    'name' => $name,
    'success' => false,
    'message' => '',
    'prediction' => '',
    'url' => ''
  ];

  if ($error !== UPLOAD_ERR_OK) {
    $result['message'] = 'Upload error.';
    $response['files'][] = $result;
    continue;
  }

  if (!in_array($type, ['image/jpeg', 'image/png'])) {
    $result['message'] = 'Invalid file type.';
    $response['files'][] = $result;
    continue;
  }

  if ($size > 5 * 1024 * 1024) {
    $result['message'] = 'File too large.';
    $response['files'][] = $result;
    continue;
  }

  $uniqueName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9_.]/', '_', $name);
  $savePath = $uploadDir . $uniqueName;
  $urlPath = $uploadUrlBase . $uniqueName;

  if (!move_uploaded_file($tmpName, $savePath)) {
    $result['message'] = 'Failed to save file.';
    $response['files'][] = $result;
    continue;
  }

  // Kirim ke Flask API untuk prediksi
  $curl = curl_init();
  $cfile = new CURLFile($savePath, $type, $uniqueName);
  curl_setopt_array($curl, [
    CURLOPT_URL => $apiEndpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => ['image' => $cfile],
  ]);

  $apiResponse = curl_exec($curl);
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);

  if ($httpCode !== 200 || !$apiResponse) {
    $result['message'] = 'Prediction failed.';
    $response['files'][] = $result;
    continue;
  }

  $decoded = json_decode($apiResponse, true);
  if (!isset($decoded['prediction'])) {
    $result['message'] = 'Invalid prediction response.';
    $response['files'][] = $result;
    continue;
  }

  $result['success'] = true;
  $result['prediction'] = $decoded['prediction'];
  $result['url'] = $urlPath;
  $response['files'][] = $result;
}

// Jika ada file berhasil
$response['success'] = count(array_filter($response['files'], fn($f) => $f['success'])) > 0;
echo json_encode($response);
