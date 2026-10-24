<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = \App\Models\User::find(1);
$penduduk = \App\Models\Penduduk::find(999999);

echo "User keys:\n";
print_r(array_keys($user->toArray()));

echo "Penduduk keys:\n";
print_r(array_keys($penduduk->toArray()));
