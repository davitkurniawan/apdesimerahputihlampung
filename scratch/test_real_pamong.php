<?php
$_SERVER['HTTP_HOST'] = 'demo.localhost:8080';
$_SERVER['SERVER_NAME'] = 'demo.localhost';

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

require_once __DIR__ . '/../xosid_database_helpers.php';

// Let's find a pamong with a valid id_pend pointing to a resident
use Illuminate\Support\Facades\DB;

$pamong = DB::table('tweb_desa_pamong')->where('id_pend', '>', 0)->first();
if (!$pamong) {
    die("No real pamong found in tweb_desa_pamong!\n");
}

echo "Found real pamong: ID {$pamong->pamong_id}, Name {$pamong->pamong_nama}, id_pend {$pamong->id_pend}\n";

// Let's check the resident
$resident = DB::table('tweb_penduduk')->where('id', $pamong->id_pend)->first();
if (!$resident) {
    die("Resident for pamong not found in tweb_penduduk!\n");
}
echo "Resident: ID {$resident->id}, Name {$resident->nama}, id_cluster: " . var_export($resident->id_cluster, true) . "\n";

// Mock ci_auth() to return a user linked to this pamong
// We can define a mock user or find one in the database
$user = \App\Models\User::where('pamong_id', $pamong->pamong_id)->first();
if (!$user) {
    // Create temporary mock user
    $user = new \App\Models\User();
    $user->id = 999;
    $user->username = 'mock_operator';
    $user->pamong_id = $pamong->pamong_id;
    $user->config_id = 1;
    $user->setAttribute('id', 999);
    $user->setAttribute('username', 'mock_operator');
    $user->setAttribute('pamong_id', $pamong->pamong_id);
    $user->setAttribute('config_id', 1);
}

// Log in the user in Laravel Auth
\Illuminate\Support\Facades\Auth::login($user);

// Execute the service method
$service = $app->make(\App\Services\LaporanPenduduk::class);
try {
    $res = $service->listData(1);
    echo "SUCCESS: listData(1) succeeded for real pamong!\n";
} catch (\Throwable $e) {
    echo "FAILED: listData(1) failed: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
