<?php
// ======================================================================
// XOSID PROGRESSIVE CALCULATOR VERIFICATION TEST
// Runs step-by-step simulation to verify cache, session, and html2pdf
// ======================================================================

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/index.php/status_desa/recalculate_progress';

// Mock session
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
$_SESSION['user_id'] = 1;

require_once __DIR__ . '/../xosid_emergency_autoload.php';

echo "=== MEMULAI TEST SIMULASI AJAK RE-CALCULATE ===\n\n";

// --- STEP 1 ---
echo "[Step 1] Menjalankan Tahap 1 (Membaca data kependudukan & file Indeks Desa)... \n";
$_GET['step'] = 1;
ob_start();
require __DIR__ . '/../xosid_recalculate_endpoint.php';
$output1 = ob_get_clean();

echo "Respon Step 1:\n" . $output1 . "\n\n";
$res1 = json_decode($output1, true);

if (!$res1 || !$res1['status']) {
    echo "❌ GAGAL di Step 1: " . ($res1['message'] ?? 'JSON parsing failed') . "\n";
    exit(1);
}
echo "✅ Step 1 BERHASIL! Progress: " . $res1['progress'] . "%\n\n";

// --- STEP 2 ---
echo "[Step 2] Menjalankan Tahap 2 (Penyusunan 9 titik analisis & DTSEN)... \n";
$_GET['step'] = 2;
ob_start();
require __DIR__ . '/../xosid_recalculate_endpoint.php';
$output2 = ob_get_clean();

echo "Respon Step 2:\n" . $output2 . "\n\n";
$res2 = json_decode($output2, true);

if (!$res2 || !$res2['status']) {
    echo "❌ GAGAL di Step 2: " . ($res2['message'] ?? 'JSON parsing failed') . "\n";
    exit(1);
}
echo "✅ Step 2 BERHASIL! Progress: " . $res2['progress'] . "%\n\n";

// --- STEP 3 ---
echo "[Step 3] Menjalankan Tahap 3 (Pembuatan & Pengarsipan Laporan PDF)... \n";
$_GET['step'] = 3;
ob_start();
require __DIR__ . '/../xosid_recalculate_endpoint.php';
$output3 = ob_get_clean();

echo "Respon Step 3:\n" . $output3 . "\n\n";
$res3 = json_decode($output3, true);

if (!$res3 || !$res3['status']) {
    echo "❌ GAGAL di Step 3: " . ($res3['message'] ?? 'JSON parsing failed') . "\n";
    exit(1);
}
echo "✅ Step 3 BERHASIL! Progress: " . $res3['progress'] . "%\n";
echo "🔗 Download URL: " . $res3['pdf_url'] . "\n\n";

// Check if PDF file was created
$pdfFile = $res3['pdf_file'];
$pdfPath = __DIR__ . '/../desa/upload/analisis_cerdas/history/' . $pdfFile;

if (file_exists($pdfPath)) {
    echo "✅ FILE PDF DITEMUKAN: $pdfPath (" . number_format(filesize($pdfPath)/1024, 1) . " KB)\n";
    echo "🎉 SIMULASI RE-CALCULATE BERTINGKAT BERHASIL 100% SECARA SEMPURNA!\n";
} else {
    echo "❌ FILE PDF TIDAK DITEMUKAN DI LOKASI: $pdfPath\n";
    exit(1);
}
