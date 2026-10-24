<?php
define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('DESAPATH', FCPATH . 'desa/');
define('LOKASI_CONFIG_DESA', 'desa/config/');
define('VIEWPATH', APPPATH . 'views/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('APP_URL', 'http://localhost:8080');
define('FORCE_HTTPS', false);
$_SERVER['HTTP_HOST'] = 'localhost:8080';

if (!function_exists('base_url')) {
    function base_url($path = '') {
        return 'http://localhost:8080/' . $path;
    }
}
if (!function_exists('setting')) {
    function setting($key, $default = null) {
        return 'Desa';
    }
}
if (!function_exists('kades')) {
    function kades() {
        return 'Kepala Desa';
    }
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$config = $app['config'];
echo "Original Cache Store Path: " . $config->get('cache.stores.file.path') . "\n";

// Change path for first tenant
$config->set('cache.stores.file.path', '/var/www/html/storage/framework/cache/data/bumidaya');
// Re-resolve the driver by clearing resolved instance or just calling driver('file') again?
// Wait, CacheManager holds resolved drivers in $this->stores array.
// To force re-resolution, we can check how to clear or if we can just forget it.
// Let's see: app('cache')->forgetDriver('file')? Or is there a way to call app('cache')->driver('file')?
// If we change config, does it affect already resolved cache? Yes, if it is not resolved yet, or we can clear it.
// Let's print:
echo "Resolved Cache Store Path: " . app('cache')->driver('file')->getStore()->getDirectory() . "\n";
// Wait! If the 'file' driver has already been resolved at boot, changing the config won't affect it unless we clear it.
// Let's see if we can forget or re-bind. Or let's see if we can do this BEFORE the cache driver is first requested!
// When does Laravel first resolve cache driver? Usually on demand.
// Let's see what happens if we set the config BEFORE we resolve the driver:
$app->forgetInstance('cache'); // Clear resolved cache manager
$app->register(\Illuminate\Cache\CacheServiceProvider::class, true); // Re-register/force
// Or simpler: just overwrite the directory property directly on the store!
app('cache')->driver('file')->getStore()->setLockDirectory(null); // Just a test
// Let's try changing path in config and re-resolving:
$config->set('cache.stores.file.path', '/var/www/html/storage/framework/cache/data/bumidaya');
$app->forgetInstance('cache'); // Re-initialize CacheManager
// Now get store directory:
echo "New Cache Store Directory after config change & forgetInstance: " . app('cache')->driver('file')->getStore()->getDirectory() . "\n";

app('cache')->put('test_key', 'hello_bumidaya_path', 60);
echo "Bumi Daya path cache: " . app('cache')->get('test_key') . "\n";

// Switch to Bondo path
$config->set('cache.stores.file.path', '/var/www/html/storage/framework/cache/data/bondo');
$app->forgetInstance('cache');
echo "New Cache Store Directory after switching to bondo: " . app('cache')->driver('file')->getStore()->getDirectory() . "\n";
echo "Bondo path cache (should be null): " . (app('cache')->get('test_key') ?? 'NULL') . "\n";
