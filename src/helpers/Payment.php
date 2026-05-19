<?php
// ============================================================
//  src/helpers/Payment.php
//  Integrasi Midtrans Payment Gateway (Snap API - Sandbox)
//  Dokumentasi: https://docs.midtrans.com/reference/snap-1
// ============================================================

class Payment
{
    /**
     * Buat transaksi Snap Midtrans dan dapatkan snap_token
     * yang akan dipakai oleh Midtrans.snap.pay() di frontend.
     *
     * @param array $order   Data order: id, amount, course_title
     * @param array $customer Data customer: name, email
     * @return array ['snap_token' => '...', 'redirect_url' => '...'] atau ['error' => '...']
     */
    public static function createSnapToken(array $order, array $customer): array
    {
        $serverKey  = MIDTRANS_SERVER_KEY;
        $isProduction = MIDTRANS_IS_PRODUCTION;
        $snapUrl    = $isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $payload = [
            'transaction_details' => [
                'order_id'     => $order['code'],
                'gross_amount' => (int) $order['amount'],
            ],
            'item_details' => [[
                'id'       => $order['course_id'],
                'price'    => (int) $order['amount'],
                'quantity' => 1,
                'name'     => substr($order['course_title'], 0, 50), // max 50 char
            ]],
            'customer_details' => [
                'first_name' => $customer['name'],
                'email'      => $customer['email'],
            ],
            'callbacks' => [
                'finish' => APP_URL . '/student/payment/finish',
            ],
        ];
$ch = curl_init($snapUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Basic ' . base64_encode($serverKey . ':'),
    ],
]);

$response = curl_exec($ch);
$curlError = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($curlError) {
    error_log('Midtrans CURL Error: ' . $curlError);
    return ['error' => 'Koneksi ke Midtrans gagal: ' . $curlError];
}

error_log('Midtrans HTTP: ' . $httpCode . ' | Response: ' . $response);
        if ($httpCode !== 201) {
            $err = json_decode($response, true);
            return ['error' => $err['error_messages'][0] ?? 'Gagal membuat transaksi'];
        }

        $data = json_decode($response, true);
        return [
            'snap_token'   => $data['token'] ?? null,
            'redirect_url' => $data['redirect_url'] ?? null,
            'raw_response' => $data,
        ];
    }

    /**
     * Ambil status transaksi dari Midtrans
     *
     * @param string $orderId
     * @return array|false
     */
    public static function getTransactionStatus(string $orderId)
    {
        $serverKey    = MIDTRANS_SERVER_KEY;
        $isProduction = MIDTRANS_IS_PRODUCTION;
        $statusUrl    = $isProduction
            ? 'https://api.midtrans.com/v2/' . urlencode($orderId) . '/status'
            : 'https://api.sandbox.midtrans.com/v2/' . urlencode($orderId) . '/status';

        $ch = curl_init($statusUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($serverKey . ':'),
            ],
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlError) {
            error_log('Midtrans status CURL Error: ' . $curlError);
            return false;
        }

        if ($httpCode !== 200) {
            error_log('Midtrans status HTTP: ' . $httpCode . ' | Response: ' . $response);
            return false;
        }

        return json_decode($response, true);
    }

    /**
     * Verifikasi notifikasi dari Midtrans (webhook)
     * Midtrans mengirim POST ke /api/payment/notification
     *
     * @param array $notification Body JSON dari Midtrans
     * @return bool true jika signature valid
     */
    public static function verifySignature(array $notification): bool
    {
        $orderId     = $notification['order_id'] ?? '';
        $statusCode  = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $serverKey   = MIDTRANS_SERVER_KEY;

        $expectedSig = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expectedSig, $notification['signature_key'] ?? '');
    }

    /**
     * Cek apakah status transaksi = berhasil dibayar
     */
    public static function isPaid(string $transactionStatus, string $fraudStatus = ''): bool
    {
        // settlement = transfer berhasil, capture = kartu kredit berhasil
        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            if ($transactionStatus === 'capture') {
                return $fraudStatus === 'accept';
            }
            return true;
        }
        return false;
    }
}
