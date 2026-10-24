<?php
/**
 * XOSID Supradesa Magic Patch v4.1 (STABLE)
 * No-Loop Bypass for Core Premium 2605
 */

// 1. Force Premium Status
if (!function_exists('is_premium')) {
    function is_premium() { return true; }
}

// 2. Global Guard
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$_SESSION['premium_session'] = true;
$_SESSION['token_valid'] = true;
$_SESSION['status_langganan'] = true;
$_SESSION['is_premium'] = true;
$_SESSION['opendesa_token'] = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoyNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

// 3. Fake SERVER responses
$_SERVER['HTTP_X_OPENDESA_LICENSE'] = 'VALID';

// 4. THE SILENT KILLER: Just stop if warning occurs, NO REDIRECT
$current_url = $_SERVER['REQUEST_URI'];
if (strpos($current_url, '/peringatan') !== false || strpos($current_url, '/token') !== false) {
    if (!headers_sent()) {
        header("Location: /index.php/wilayah");
        exit;
    }
}

error_reporting(0);
ini_set('display_errors', 0);
