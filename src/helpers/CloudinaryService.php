<?php
class CloudinaryService
{
    public static function uploadVideo(string $filePath, string $fileName): string
    {
        $cloudName = CLOUDINARY_CLOUD_NAME;
        $apiKey    = CLOUDINARY_API_KEY;
        $apiSecret = CLOUDINARY_API_SECRET;

    // Debug - tulis ke log
    error_log("Cloudinary Debug - Cloud: $cloudName, Key: $apiKey, Secret: " . (empty($apiSecret) ? 'KOSONG' : 'ADA'));
    error_log("File path: $filePath, exists: " . (file_exists($filePath) ? 'YES' : 'NO'));

    $timestamp = time();
    $publicId  = 'skillup/videos/' . pathinfo($fileName, PATHINFO_FILENAME) . '_' . $timestamp;
    
    $signatureString = 'public_id=' . $publicId . '&timestamp=' . $timestamp . $apiSecret;
    $signature = sha1($signatureString);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => "https://api.cloudinary.com/v1_1/{$cloudName}/video/upload",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => [
            'file'      => new CURLFile($filePath),
            'api_key'   => $apiKey,
            'timestamp' => $timestamp,
            'public_id' => $publicId,
            'signature' => $signature,
        ],
    ]);

    $response = curl_exec($ch);
    $error    = curl_error($ch);
    curl_close($ch);

    error_log("Cloudinary Response: " . $response);
    error_log("CURL Error: " . $error);

    if ($error) {
        throw new Exception('CURL error: ' . $error);
    }

    $data = json_decode($response, true);
    
    if (isset($data['secure_url'])) {
        return $data['secure_url'];
    }
    
    throw new Exception('Cloudinary upload failed: ' . ($data['error']['message'] ?? json_encode($data)));
}
}