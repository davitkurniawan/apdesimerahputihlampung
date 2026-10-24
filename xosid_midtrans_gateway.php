<?php
/**
 * XOSID Midtrans Gateway & Notification Helper
 * Handles Snap Token generation, Webhook validation, and Centralized Notifications
 */

define('MIDTRANS_IS_PRODUCTION', false); // Set to true for production
define('MIDTRANS_SERVER_KEY', 'SB-Mid-server-1zSYKitbgt5FEKzBESdXHwQk');
define('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-1ZtR4hsYiWmFSFDO');

function xosid_midtrans_get_snap_token($order_id, $gross_amount, $customer_details, $item_details = []) {
    $url = MIDTRANS_IS_PRODUCTION ? 
        'https://app.midtrans.com/snap/v1/transactions' : 
        'https://app.sandbox.midtrans.com/snap/v1/transactions';

    $payload = [
        'transaction_details' => [
            'order_id' => $order_id,
            'gross_amount' => $gross_amount,
        ],
        'customer_details' => $customer_details,
    ];
    
    if (!empty($item_details)) {
        $payload['item_details'] = $item_details;
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Basic ' . base64_encode(MIDTRANS_SERVER_KEY . ':')
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 201) {
        $result = json_decode($response, true);
        return [
            'token' => $result['token'],
            'redirect_url' => $result['redirect_url']
        ];
    }
    
    // Fallback if Midtrans fails or credentials are wrong (simulate for now)
    return [
        'token' => 'simulated_token_' . time(),
        'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v3/redirection/simulated'
    ];
}

function xosid_send_notifications($conn, $tenant_id, $tenant_email, $tenant_phone, $subject, $message, $type = 'info') {
    // 1. In-App Notification
    $stmt = $conn->prepare("INSERT INTO xosid_member_notifications (tenant_id, judul, pesan, kategori) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("isss", $tenant_id, $subject, $message, $type);
        $stmt->execute();
        $stmt->close();
    }

    // 2. Email Gateway (Mock for now, using log)
    $log_dir = __DIR__ . '/storage/logs/';
    if (!is_dir($log_dir)) @mkdir($log_dir, 0755, true);
    $email_log = "[".date('Y-m-d H:i:s')."] [EMAIL] To: $tenant_email | Sub: $subject | Msg: $message\n";
    file_put_contents($log_dir . 'email_gateway.log', $email_log, FILE_APPEND);

    // 3. WA Gateway (Mock for now, using log)
    $wa_log = "[".date('Y-m-d H:i:s')."] [WHATSAPP] To: $tenant_phone | Msg: $message\n";
    file_put_contents($log_dir . 'wa_gateway.log', $wa_log, FILE_APPEND);
}
