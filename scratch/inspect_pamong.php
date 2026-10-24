<?php
// scratch/inspect_pamong.php
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

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_database_helpers.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
\Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);

$p = new \App\Models\Pamong();
$rel = $p->user();
echo "Relation class: " . get_class($rel) . "\n";
if ($rel instanceof \Illuminate\Database\Eloquent\Relations\HasOneOrMany) {
    echo "Foreign key: " . $rel->getForeignKeyName() . "\n";
    echo "Local key: " . $rel->getLocalKeyName() . "\n";
} elseif ($rel instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo) {
    echo "Foreign key: " . $rel->getForeignKeyName() . "\n";
    echo "Owner key: " . $rel->getOwnerKeyName() . "\n";
}
