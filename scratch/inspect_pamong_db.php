<?php
// scratch/inspect_pamong_db.php

define('ENVIRONMENT', 'production');
define('FCPATH', '/var/www/html/');
define('SELF', 'index.php');
define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('FORCE_HTTPS', false);

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REQUEST_METHOD'] = 'GET';

require_once FCPATH . 'vendor/autoload.php';
require_once FCPATH . 'xosid_emergency_autoload.php';

$app = require_once FCPATH . 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pamongs = \Illuminate\Support\Facades\DB::select("select pamong_id, pamong_nama, jabatan_id, id_pend, pamong_status from tweb_desa_pamong where config_id = 1");
echo "PAMONGS IN BONDO:\n";
foreach ($pamongs as $p) {
    echo "  - ID: {$p->pamong_id} | Nama: {$p->pamong_nama} | Jabatan ID: {$p->jabatan_id} | ID Pend: " . json_encode($p->id_pend) . " | Status: {$p->pamong_status}\n";
}
