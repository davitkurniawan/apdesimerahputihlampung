<?php
// scratch/check_hooks_enabled.php
define('BASEPATH', 'true');
define('APP_URL', 'localhost');
define('STORAGEPATH', 'true');
define('DESAPATH', 'true');
define('ENVIRONMENT', 'true');
define('FCPATH', 'true');
define('LOKASI_CACHE', 'true');
include '/var/www/html/donjo-app/config/config.php';
echo "ENABLE_HOOKS: " . (isset($config['enable_hooks']) && $config['enable_hooks'] ? 'TRUE' : 'FALSE') . "\n";
