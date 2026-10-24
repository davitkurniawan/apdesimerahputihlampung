<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$user = User::find(1);
$user->setAttribute('idCluster', 0);
$user->syncOriginalAttribute('idCluster');

echo "Is idCluster dirty? " . var_export($user->isDirty('idCluster'), true) . "\n";
echo "Dirty attributes: " . json_encode($user->getDirty()) . "\n";

// Save the model and see if it runs without database error!
$user->last_login = now();
$user->save();
echo "User saved successfully without column error!\n";
