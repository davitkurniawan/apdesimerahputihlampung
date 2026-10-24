<?php
define('ENVIRONMENT', 'development');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('FORCE_HTTPS', false);

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['REQUEST_METHOD'] = 'GET';

require_once FCPATH . 'vendor/autoload.php';
require_once FCPATH . 'xosid_emergency_autoload.php';
require_once FCPATH . 'xosid_database_helpers.php';

$app = require_once FCPATH . 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Setup DB connections
$config = $app['config'];
$connections = $config->get('database.connections') ?: [];
$laravel_db = [
    'driver'    => 'mysql',
    'host'      => 'db',
    'port'      => 3306,
    'database'  => 'xosid_tenant_bondo-bangsri',
    'username'  => 'root',
    'password'  => 'password',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'prefix'    => '',
    'strict'    => false,
    'engine'    => null,
];
$connections['default'] = $laravel_db;
$connections['mysql'] = $laravel_db;
$config->set('database.connections', $connections);
$config->set('database.default', 'default');

// Define Custom PDOStatement class
class XOSIDPDOStatement extends \PDOStatement {
    protected $bindings = [];
    protected function __construct() {}
    public function bindValue($param, $value, $type = \PDO::PARAM_STR): bool {
        $this->bindings[$param] = $value;
        return parent::bindValue($param, $value, $type);
    }
    public function bindParam($param, &$var, $type = \PDO::PARAM_STR, $maxLength = 0, $driverOptions = null): bool {
        $this->bindings[$param] = &$var;
        return parent::bindParam($param, $var, $type, $maxLength, $driverOptions);
    }
    public function execute($params = null): bool {
        echo "XOSID_PDO_STATEMENT EXECUTE SQL: {$this->queryString}\n";
        echo "Passed Params: " . json_encode($params) . " | Bindings: " . json_encode($this->bindings) . "\n";
        
        $is_target = false;
        if (strpos($this->queryString, '`user`') !== false || strpos($this->queryString, '"user"') !== false || strpos($this->queryString, ' user ') !== false) {
            $is_target = true;
        }
        if ($is_target) {
            $first_val = null;
            $first_key = null;
            if (is_array($params) && array_key_exists(0, $params)) {
                $first_val = $params[0];
                $first_key = 'params_0';
            } elseif (array_key_exists(1, $this->bindings)) {
                $first_val = $this->bindings[1];
                $first_key = 'bindings_1';
            } elseif (array_key_exists(0, $this->bindings)) {
                $first_val = $this->bindings[0];
                $first_key = 'bindings_0';
            }
            if ($first_val === '' || $first_val === null) {
                $uid = 999; // Mock user ID
                if ($first_key === 'params_0') {
                    $params[0] = (int)$uid;
                } elseif ($first_key === 'bindings_1') {
                    parent::bindValue(1, (int)$uid, \PDO::PARAM_INT);
                } elseif ($first_key === 'bindings_0') {
                    parent::bindValue(0, (int)$uid, \PDO::PARAM_INT);
                }
                echo "HIJACKED BINDING TO UID: $uid\n";
            }
        }
        return parent::execute($params);
    }
}

// Extend mysql driver in DatabaseManager
$app['db']->extend('mysql', function($config, $name) {
    echo "MY_SQL EXTENSION CALLBACK CALLED FOR CONNECTION: $name\n";
    $connector = new \Illuminate\Database\Connectors\MySqlConnector;
    $pdo = $connector->connect($config);
    // Set custom statement class
    $pdo->setAttribute(\PDO::ATTR_STATEMENT_CLASS, ['XOSIDPDOStatement', []]);
    return new \Illuminate\Database\MySqlConnection($pdo, $config['database'], $config['prefix'] ?? '', $config);
});

// Set connection resolver on Eloquent Model
\Illuminate\Database\Eloquent\Model::setConnectionResolver($app['db']);

echo "App\\Models\\User exists? " . (class_exists('App\Models\User') ? 'YES' : 'NO') . "\n";

if (class_exists('App\Models\User')) {
    echo "Querying App\\Models\\User::where('id', '')->first():\n";
    try {
        $u = \App\Models\User::where('id', '')->first();
        echo "Result: " . ($u ? "Found ID={$u->id}" : "NULL") . "\n";
    } catch (\Throwable $e) {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
}
