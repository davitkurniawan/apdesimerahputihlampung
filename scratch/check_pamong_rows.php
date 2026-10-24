<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$count = DB::table('tweb_desa_pamong')->count();
echo "Total pamong rows: $count\n";

$pamong = DB::table('tweb_desa_pamong')->get();
foreach ($pamong as $p) {
    echo "Pamong: ID {$p->pamong_id}, Name {$p->pamong_nama}, id_pend: " . var_export($p->id_pend, true) . "\n";
}
