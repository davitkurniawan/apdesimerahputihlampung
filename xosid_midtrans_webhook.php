<?php
/**
 * Midtrans Webhook Receiver
 * Endpoint: /api/midtrans/webhook
 */

require_once __DIR__ . '/xosid_midtrans_gateway.php';
require_once __DIR__ . '/db_test.php'; // or whatever connects to DB.
// Wait, actually I will just write a standalone script like index.php does or require the connection.

// For now, let's create a DB connection manually like in xosid_member_api.php
$conn = new mysqli($_ENV['DB_HOST'] ?? 'db', $_ENV['DB_USERNAME'] ?? 'root', $_ENV['DB_PASSWORD'] ?? 'password', $_ENV['DB_DATABASE'] ?? 'xosid', $_ENV['DB_PORT'] ?? 3306);

if ($conn->connect_error) {
    http_response_code(500);
    exit('DB Connection Failed');
}

$payload = file_get_contents('php://input');
$notification = json_decode($payload, true);

if (!$notification || !isset($notification['order_id'])) {
    http_response_code(400);
    exit('Invalid payload');
}

$order_id = $notification['order_id'];
$status_code = $notification['status_code'];
$gross_amount = $notification['gross_amount'];
$transaction_status = $notification['transaction_status'];
$signature_key = $notification['signature_key'];

$transaction_id = $notification['transaction_id'] ?? '';
$payment_type = $notification['payment_type'] ?? '';
$bank = '';
$va_number = '';

if (isset($notification['va_numbers']) && is_array($notification['va_numbers']) && count($notification['va_numbers']) > 0) {
    $bank = $notification['va_numbers'][0]['bank'] ?? '';
    $va_number = $notification['va_numbers'][0]['va_number'] ?? '';
} elseif (isset($notification['bank'])) {
    $bank = $notification['bank'];
} elseif (isset($notification['permata_va_number'])) {
    $bank = 'permata';
    $va_number = $notification['permata_va_number'];
} elseif (isset($notification['bca_va_number'])) {
    $bank = 'bca';
    $va_number = $notification['bca_va_number'];
}

// Verify Signature
$server_key = MIDTRANS_SERVER_KEY;
$expected_signature = hash('sha512', $order_id . $status_code . $gross_amount . $server_key);

if ($signature_key !== $expected_signature) {
    http_response_code(403);
    exit('Invalid Signature');
}

// Check Invoice in DB
$stmt = $conn->prepare("SELECT id, tenant_id, total_tagihan FROM xosid_member_invoices WHERE nomor_invoice = ?");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$invoice = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$invoice) {
    http_response_code(404);
    exit('Invoice not found');
}

// Check Status
if ($transaction_status == 'capture' || $transaction_status == 'settlement') {
    // Paid
    $stmt = $conn->prepare("UPDATE xosid_member_invoices SET status_pembayaran = 'paid', tanggal_dibayar = NOW(), midtrans_transaction_id = ?, midtrans_payment_type = ?, midtrans_bank = ?, midtrans_va_number = ? WHERE nomor_invoice = ?");
    $stmt->bind_param("sssss", $transaction_id, $payment_type, $bank, $va_number, $order_id);
    $stmt->execute();
    $stmt->close();

    // Get Tenant details for notification
    $stmt = $conn->prepare("SELECT email_operator, telepon_operator, nama_operator, nama_kades FROM xosid_registrasi_tenant WHERE id = ?");
    $stmt->bind_param("i", $invoice['tenant_id']);
    $stmt->execute();
    $tenant = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($tenant) {
        $subject = "Pembayaran Berhasil: " . $order_id;
        $msg = "Halo " . ($tenant['nama_operator'] ?: 'Pelanggan') . ", Pembayaran untuk ID Invoice: " . $order_id . " telah berhasil kami terima.\nTanda Terima (Receipt) dapat diunduh pada Dashboard Member XOSID.\n\nTerima kasih atas kepercayaan Anda.";
        xosid_send_notifications($conn, $invoice['tenant_id'], $tenant['email_operator'], $tenant['telepon_operator'], $subject, $msg, 'success');
    }
} elseif ($transaction_status == 'expire' || $transaction_status == 'cancel' || $transaction_status == 'deny') {
    // Expired / Canceled
    $stmt = $conn->prepare("UPDATE xosid_member_invoices SET status_pembayaran = 'expired' WHERE nomor_invoice = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $stmt->close();
}

http_response_code(200);
echo 'OK';
?>
