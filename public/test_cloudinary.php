<?php
require_once '../config/config.php';
require_once '../src/helpers/CloudinaryService.php';

// Test dengan video kecil dari URL
$cloudName = CLOUDINARY_CLOUD_NAME;
$apiKey    = CLOUDINARY_API_KEY;
$apiSecret = CLOUDINARY_API_SECRET;

echo "Cloud Name: " . $cloudName . "<br>";
echo "API Key: " . $apiKey . "<br>";
echo "API Secret: " . (empty($apiSecret) ? 'KOSONG!' : 'ADA') . "<br>";