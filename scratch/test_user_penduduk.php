<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$user = User::find(1);
if ($user) {
    echo "User pamong relation:\n";
    $pamong = $user->pamong;
    if ($pamong) {
        echo "  Pamong class: " . get_class($pamong) . "\n";
        $pend = $pamong->penduduk;
        if ($pend) {
            echo "    Penduduk class: " . get_class($pend) . "\n";
        } else {
            echo "    Penduduk is null\n";
        }
    } else {
        echo "  Pamong is null\n";
    }
}
