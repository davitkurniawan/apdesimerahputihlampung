<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $columns = DB::select("SHOW COLUMNS FROM tweb_wil_clusterdesa");
    foreach ($columns as $col) {
        echo "Column: {$col->Field} ({$col->Type})\n";
    }
} catch (\Throwable $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}
