<?php
header('Content-Type: application/json');
echo json_encode([
    'status' => true,
    'message' => 'Token Valid',
    'data' => [
        'nama_desa' => 'Desa Bumi Daya',
        'kode_desa' => '99.99.99.9999',
        'expired_at' => '2099-12-31 23:59:59',
        'is_premium' => true,
        'token' => 'XOSID-SUPRADESA-PREMIUM-UNLOCKED-TOKEN'
    ]
]);
