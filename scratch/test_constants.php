<?php
define('ENVIRONMENT', 'development');
define('FCPATH', __DIR__ . '/../');
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

echo "LOKASI_USER_PICT: " . (defined('LOKASI_USER_PICT') ? LOKASI_USER_PICT : 'not defined') . "\n";
echo "FOTO_DEFAULT_WANITA: " . (defined('FOTO_DEFAULT_WANITA') ? FOTO_DEFAULT_WANITA : 'not defined') . "\n";
echo "FOTO_DEFAULT_PRIA: " . (defined('FOTO_DEFAULT_PRIA') ? FOTO_DEFAULT_PRIA : 'not defined') . "\n";
?>
