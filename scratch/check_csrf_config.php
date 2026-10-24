<?php
if (!defined('FORCE_HTTPS')) define('FORCE_HTTPS', false);
if (!defined('FCPATH')) define('FCPATH', '/var/www/html/');
if (!defined('ENVIRONMENT')) define('ENVIRONMENT', 'development');

define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('VIEWPATH', '/var/www/html/donjo-app/views/');

require_once APPPATH . 'config/constants.php';
require_once BASEPATH . 'core/Common.php';

$config =& get_config();

echo "=== CodeIgniter Config ===\n";
echo "Base URL: " . $config['base_url'] . "\n";
echo "Index Page: " . $config['index_page'] . "\n";
echo "CSRF Protection: " . ($config['csrf_protection'] ? 'TRUE' : 'FALSE') . "\n";
echo "CSRF Token Name: " . $config['csrf_token_name'] . "\n";
echo "CSRF Cookie Name: " . $config['csrf_cookie_name'] . "\n";
echo "CSRF Expire: " . $config['csrf_expire'] . "\n";
echo "CSRF Regenerate: " . ($config['csrf_regenerate'] ? 'TRUE' : 'FALSE') . "\n";
echo "Cookie Domain: '" . $config['cookie_domain'] . "'\n";
echo "Cookie Path: '" . $config['cookie_path'] . "'\n";
echo "Cookie Secure: " . ($config['cookie_secure'] ? 'TRUE' : 'FALSE') . "\n";
echo "Cookie HTTPOnly: " . ($config['cookie_httponly'] ? 'TRUE' : 'FALSE') . "\n";
echo "==========================\n";
