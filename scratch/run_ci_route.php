<?php
// Define is_cli to be false
function is_cli() { return false; }

$_SERVER['HTTP_HOST'] = 'bumidaya.localhost:8080';
$_SERVER['SERVER_NAME'] = 'bumidaya.localhost';
$_SERVER['REQUEST_URI'] = '/index.php/wilayah';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = '/var/www/html/index.php';
$_SERVER['PHP_SELF'] = '/index.php/wilayah';
$_SERVER['PATH_INFO'] = '/wilayah';
$_SERVER['REMOTE_ADDR'] = '172.18.0.1';
$_SERVER['SERVER_PORT'] = '8080';
$_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36';

// Set active session ID to match the database 'session' column for admin
session_id('68053d4e9b0e4349451ad9fcc42d93bf');
session_start();

// Populate $_SESSION with the same key values as the browser session
$_SESSION['config_id'] = 1;
$_SESSION['id_desa'] = 1;
$_SESSION['user_id'] = 1;
$_SESSION['user'] = 1;
$_SESSION['username'] = 'admin';
$_SESSION['nama'] = 'Administrator';
$_SESSION['siteman'] = 1;
$_SESSION['grup'] = 1;
$_SESSION['admin_level'] = 1;
$_SESSION['sesi'] = 'aktif';
$_SESSION['premium_session'] = true;
$_SESSION['status_langganan'] = true;
$_SESSION['is_premium'] = true;
$_SESSION['is_premium_sid'] = true;
$_SESSION['layanan_premium'] = 1;
$_SESSION['token_premium'] = 'XOSID-PREMIUM-MOCK-2099';
$_SESSION['member_premium'] = true;
$_SESSION['premium'] = true;
$_SESSION['token_valid'] = true;
$_SESSION['_token'] = 'PH1TfiFaEJaBXlJxU6pYQw8oqawC7nrUhSao7OEw';

echo "SESSION DATA BEFORE KERNEL:\n";
print_r($_SESSION);

// Enable error reporting to screen
error_reporting(E_ALL);
ini_set('display_errors', 1);

register_shutdown_function(function() {
    $err = error_get_last();
    if ($err) {
        echo "\nSHUTDOWN ERROR DETECTED:\n";
        print_r($err);
    }
});

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';

// Booting the HTTP Kernel will trigger CodeIgniterBootstrap, which boots CI and runs the controller!
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
try {
    $response = $kernel->handle($request);
    echo "\nResponse Status: " . $response->getStatusCode() . "\n";
    echo "Response Content Length: " . strlen($response->getContent()) . "\n";
    echo "Response Content: " . substr($response->getContent(), 0, 5000) . "\n";
} catch (\Throwable $e) {
    echo "\nEXCEPTION THROWN:\n";
    echo $e->getMessage() . "\n";
    echo $e->getFile() . " Line " . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
