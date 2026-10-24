<?php
// XOSID GLOBAL AGGRESSIVE PATCH
// Forces all known licensing flags to TRUE in both CI and Laravel contexts.

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// 1. Force Identity (Matches DB)
$_SESSION['id_desa'] = 1;
$_SESSION['nama_desa'] = 'Bumi Daya';
$_SESSION['kode_desa'] = '1801102013';

// 2. Force Licensing Flags
$_SESSION['premium_session'] = true;
$_SESSION['token_valid'] = true;
$_SESSION['status_langganan'] = true;
$_SESSION['is_premium'] = true;
$_SESSION['opendesa_token'] = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoyNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c';

if (!defined('IS_PREMIUM')) define('IS_PREMIUM', true);
if (!defined('STATUS_LANGGANAN')) define('STATUS_LANGGANAN', 1);

// 3. Mock the CI Config (if CI is loaded)
if (isset($get_instance) || function_exists('get_instance')) {
    $CI =& get_instance();
    if ($CI) {
        $CI->config->set_item('premium', true);
        $CI->session->set_userdata('premium_session', true);
        $CI->session->set_userdata('nama_desa', 'Bumi Daya');
        $CI->session->set_userdata('kode_desa', '1801102013');
    }
}
