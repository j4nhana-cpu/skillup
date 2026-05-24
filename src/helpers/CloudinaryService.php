<?php
class CloudinaryService
{
    public static function uploadVideo(string $filePath, string $fileName): string
    {
        $cloudName = CLOUDINARY_CLOUD_NAME;
        $apiKey    = CLOUDINARY_API_KEY;
        $apiSecret = CLOUDINARY_API_SECRET;

        $timestamp = time();
        $publicId  = 'skillup/videos/' . pathinfo($fileName, PATHINFO_FILENAME) . '_' . $timestamp;
        
        $signatureString = 'public_id=' . $publicId . '&timestamp=' . $timestamp . $apiSecret;
        $signature = sha1($signatureString);

        // Copy file ke temp location yang pasti bisa dibaca
        $tempFile = sys_get_temp_dir() . '/' . uniqid('upload_') . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        copy($filePath, $tempFile);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => "https://api.cloudinary.com/v1_1/{$cloudName}/video/upload",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_TIMEOUT        => 300,
            CURLOPT_POSTFIELDS     => [
                'file'      => new CURLFile($tempFile, 'video/mp4', $fileName),
                'api_key'   => $apiKey,
                'timestamp' => $timestamp,
                'public_id' => $publicId,
                'signature' => $signature,
            ],
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        // Hapus temp file
        if (file_exists($tempFile)) unlink($tempFile);

        if ($curlError) {
            throw new Exception('CURL error: ' . $curlError);
        }

        if (empty($response)) {
            throw new Exception('Empty response from Cloudinary');
        }

        $data = json_decode($response, true);
        
        if (isset($data['secure_url'])) {
            return $data['secure_url'];
        }
        
        throw new Exception('Cloudinary upload failed: ' . json_encode($data));
    }
}