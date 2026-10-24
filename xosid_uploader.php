<?php
// ======================================================================
// XOSID SECURE FILE UPLOADER & BACKDOOR DEFENSE HUB
// ======================================================================

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// Enforce auth verification
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['status' => false, 'message' => 'Unauthorized access.']);
    exit;
}

header('Content-Type: application/json');

/**
 * Validates magic bytes of the uploaded file to ensure it matches the claimed extension
 */
function xosid_verify_magic_bytes($tmpPath, $extension) {
    $handle = fopen($tmpPath, 'rb');
    if (!$handle) return false;
    $bytes = fread($handle, 4);
    fclose($handle);

    $hex = bin2hex($bytes);

    switch ($extension) {
        case 'pdf':
            // PDF starts with %PDF (25504446)
            return (strpos($hex, '25504446') === 0);
        case 'zip':
        case 'xlsx':
            // ZIP/Office XML starts with PK (504b0304)
            return (strpos($hex, '504b0304') === 0);
        case 'xls':
            // Old Excel starts with D0CF11E0
            return (strpos($hex, 'd0cf11e0') === 0);
    }
    return false;
}

function xosid_is_text_file($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $binary_exts = [
        'pdf', 'xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'rar', '7z', 'tar', 'gz',
        'png', 'jpg', 'jpeg', 'gif', 'bmp', 'ico', 'svg', 'webp', 'mp3', 'mp4', 'avi', 'mov',
        'ttf', 'otf', 'woff', 'woff2', 'eot'
    ];
    if (empty($ext)) {
        return true;
    }
    return !in_array($ext, $binary_exts);
}

/**
 * Deep scans files to check for executable backdoor indicators (e.g. <?php, eval(), exec())
 */
function xosid_detect_backdoor($tmpPath, $extension) {
    if ($extension === 'zip') {
        $zip = new ZipArchive();
        if ($zip->open($tmpPath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                // 1. Block executable extensions
                $blocked_exts = ['php', 'php5', 'phtml', 'phps', 'phar', 'htaccess', 'pl', 'py', 'sh', 'cgi', 'exe', 'asp', 'aspx', 'jsp'];
                if (in_array($ext, $blocked_exts)) {
                    $zip->close();
                    return true; // Backdoor detected!
                }
                
                // 2. Scan content of text files inside the ZIP
                if (xosid_is_text_file($filename)) {
                    $content = $zip->getFromIndex($i);
                    if ($content !== false && strlen($content) > 0) {
                        if (preg_match('/<\?(?!xml)/i', $content)) {
                            $zip->close();
                            return true; // Backdoor detected!
                        }
                        if (preg_match('/<script\s+language=["\']php["\']/i', $content)) {
                            $zip->close();
                            return true; // Backdoor detected!
                        }
                    }
                }
            }
            $zip->close();
            return false; // Clean!
        }
        return true; // Fail-secure
    }

    // For xlsx, xls, pdf
    // We already checked magic bytes, so we bypass deep binary scanner to avoid false positives.
    return false;
}

// Ensure secure folder exists (tenant-isolated)
$subdomain = function_exists('xosid_get_tenant_subdomain') ? xosid_get_tenant_subdomain() : 'default';
if ($subdomain === 'default') {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $host_parts = explode(':', $host);
    $host_no_port = strtolower($host_parts[0]);
    if (preg_match('/^([a-z0-9-]+)\.(localhost|xosid\.id|127\.0\.0\.1)/', $host_no_port, $m)) {
        if (!in_array($m[1], ['www', 'master', 'api'])) {
            $subdomain = $m[1];
        }
    }
}
$upload_dir = __DIR__ . '/desa/upload/analisis_cerdas/' . ($subdomain !== 'default' ? $subdomain . '/' : '');
if (!is_dir($upload_dir)) {
    @mkdir($upload_dir, 0755, true);
}

$response = ['status' => true, 'messages' => [], 'files' => []];

$allowed_keys = ['prodeskel_file', 'indeks_desa_file'];
$allowed_exts = ['zip', 'xls', 'xlsx', 'pdf'];

foreach ($allowed_keys as $key) {
    if (!isset($_FILES[$key]) || $_FILES[$key]['error'] === UPLOAD_ERR_NO_FILE) {
        continue;
    }

    $file = $_FILES[$key];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $response['status'] = false;
        $response['messages'][] = "Error uploading file ($key): " . $file['error'];
        continue;
    }

    $original_name = basename($file['name']);
    $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

    // 1. Check Extension whitelist
    if (!in_array($extension, $allowed_exts)) {
        $response['status'] = false;
        $response['messages'][] = "Format file tidak didukung: .$extension (Hanya diperbolehkan .zip, .xls, .xlsx, .pdf)";
        continue;
    }

    $tmpPath = $file['tmp_name'];

    // 2. Validate Magic Bytes
    if (!xosid_verify_magic_bytes($tmpPath, $extension)) {
        $response['status'] = false;
        $response['messages'][] = "Validasi tipe file gagal: Konten file tidak sesuai dengan ekstensi .$extension";
        continue;
    }

    // 3. Scan for backdoors / executable injection
    if (xosid_detect_backdoor($tmpPath, $extension)) {
        $response['status'] = false;
        $response['messages'][] = "Deteksi Keamanan: File mengandung konten ilegal atau script executable yang diblokir.";
        @unlink($tmpPath);
        continue;
    }

    // 4. Save file with a safe, sanitized filename
    $sanitized_name = preg_replace('/[^a-zA-Z0-9_\.-]/', '', $original_name);
    // Prefix to prevent routing bypass
    $final_filename = $key . '_' . time() . '_' . $sanitized_name;
    $dest_path = $upload_dir . $final_filename;

    if (move_uploaded_file($tmpPath, $dest_path)) {
        // Clear previous files of this type to save disk space and keep active only
        $existing_files = glob($upload_dir . $key . '_*');
        foreach ($existing_files as $ext_file) {
            if (basename($ext_file) !== $final_filename) {
                @unlink($ext_file);
            }
        }

        // Trigger invalidation of analytics cache so the recalculation happens immediately
        $engine_file = __DIR__ . '/xosid_intelligence_engine.php';
        if (file_exists($engine_file)) {
            require_once $engine_file;
            if (function_exists('xosid_invalidate_intelligence_cache')) {
                xosid_invalidate_intelligence_cache();
            }
        }

        $response['files'][$key] = [
            'original_name' => $original_name,
            'saved_name'    => $final_filename,
            'size'          => $file['size']
        ];
    } else {
        $response['status'] = false;
        $response['messages'][] = "Gagal menyimpan berkas ke server.";
    }
}

if ($response['status'] && empty($response['files'])) {
    $response['status'] = false;
    $response['messages'][] = "Tidak ada berkas yang terpilih.";
}

echo json_encode($response);
exit;
