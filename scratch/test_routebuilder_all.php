<?php
define('FCPATH', __DIR__ . '/../');
define('APPPATH', __DIR__ . '/../donjo-app/');

if (!function_exists('is_cli')) {
    function is_cli() {
        return false;
    }
}
if (!function_exists('show_error')) {
    function show_error($message) {
        echo "ERROR: $message\n";
    }
}

require_once FCPATH . 'vendor/autoload.php';

if (class_exists('OpenSID\RouteBuilder', true)) {
    $controllers = [
        "pengurus" => "Pengurus",
        "dokumen_sekretariat" => "Dokumen_sekretariat",
        "ekspedisi" => "Ekspedisi",
        "lembaran_desa" => "Lembaran_desa",
        "surat_keluar" => "Surat_keluar",
        "surat_masuk" => "Surat_masuk"
    ];
    foreach ($controllers as $url_ctrl => $class_ctrl) {
        \OpenSID\RouteBuilder::any($url_ctrl, 'buku_umum/' . $class_ctrl . '@index');
        \OpenSID\RouteBuilder::any($url_ctrl . '/datatables', 'buku_umum/' . $class_ctrl . '@datatables');
        \OpenSID\RouteBuilder::any($url_ctrl . '/form', 'buku_umum/' . $class_ctrl . '@form');
        \OpenSID\RouteBuilder::any($url_ctrl . '/form/{id}', 'buku_umum/' . $class_ctrl . '@form');
        \OpenSID\RouteBuilder::any($url_ctrl . '/delete', 'buku_umum/' . $class_ctrl . '@delete');
        \OpenSID\RouteBuilder::any($url_ctrl . '/delete/{id}', 'buku_umum/' . $class_ctrl . '@delete');
        \OpenSID\RouteBuilder::any($url_ctrl . '/jabatan', 'buku_umum/' . $class_ctrl . '@jabatan');
        \OpenSID\RouteBuilder::any($url_ctrl . '/jabatanform', 'buku_umum/' . $class_ctrl . '@jabatanform');
        \OpenSID\RouteBuilder::any($url_ctrl . '/jabatanform/{id}', 'buku_umum/' . $class_ctrl . '@jabatanform');
        \OpenSID\RouteBuilder::any($url_ctrl . '/jabatandelete', 'buku_umum/' . $class_ctrl . '@jabatandelete');
        \OpenSID\RouteBuilder::any($url_ctrl . '/{any}', 'buku_umum/' . $class_ctrl . '@index');
    }
}

\OpenSID\RouteBuilder::compileAll();
$routes = \OpenSID\RouteBuilder::getRoutes();
echo "Compiled routes count: " . count($routes) . "\n";
echo "Keys example:\n";
print_r(array_keys($routes));
