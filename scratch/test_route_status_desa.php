<?php
// Simulate loading CodeIgniter and print routes
$_SERVER['REQUEST_URI'] = '/index.php/siteman';
$_SERVER['PHP_SELF'] = '/index.php/siteman';
$_SERVER['PATH_INFO'] = '/siteman';

require_once dirname(__DIR__) . '/index.php';

// Wait, we need to let CI bootstrap a bit, or we can just access route helper if loaded.
if (function_exists('ci_route')) {
    echo "IDM Refresh Route: " . ci_route('status_desa.perbarui_idm', 2024) . "\n";
    echo "SDGs Refresh Route: " . ci_route('status_desa.perbarui_sdgs') . "\n";
} else {
    echo "ci_route not defined\n";
}
