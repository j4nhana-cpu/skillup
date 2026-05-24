<?php
// Test langsung tanpa routing
$cloudName = 'dylv8d0ae';
$apiKey    = '398552872585616';
$apiSecret = 'jTHf3GWN6ZVI0Kh_5K_kTI-WpoQ';

$timestamp = time();
$publicId  = 'test_video_' . $timestamp;

$signatureString = 'public_id=' . $publicId . '&timestamp=' . $timestamp . $apiSecret;
$signature = sha1($signatureString);

// Upload file test kecil (video 1 detik dari URL)
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => "https://api.cloudinary.com/v1_1/{$cloudName}/video/upload",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => [
        'file'      => 'https://res.cloudinary.com/demo/video/upload/dog.mp4',
        'api_key'   => $apiKey,
        'timestamp' => $timestamp,
        'public_id' => $publicId,
        'signature' => $signature,
    ],
]);

$response = curl_exec($ch);
curl_close($ch);

echo "<pre>";
print_r(json_decode($response, true));
echo "</pre>";