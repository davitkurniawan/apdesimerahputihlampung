<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$_SERVER['HTTP_HOST'] = 'bumidaya.localhost';
$request = Illuminate\Http\Request::create('http://bumidaya.localhost/index.php/xoSID-login', 'GET');
$app->instance('request', $request);
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

// Define legacy constants if not defined
if (!defined('BASEPATH')) define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
if (!defined('APPPATH')) define('APPPATH', dirname(__DIR__) . '/donjo-app/');
if (!defined('MINIMUM_VERSION_BUILD')) define('App\Libraries\MINIMUM_VERSION_BUILD', '1.0');

if (!class_exists('XOSIDDummySession')) {
    class XOSIDDummySession {
        public function flashdata($key) { return null; }
        public function set_flashdata($key, $val) {}
        public function __call($name, $args) { return null; }
        public function __get($name) { return null; }
    }
}

if (!class_exists('XOSIDDummyConfig')) {
    class XOSIDDummyConfig {
        public function item($key) { return null; }
        public function __call($name, $args) { return null; }
        public function __get($name) { return null; }
    }
}

if (!class_exists('XOSIDDummyDB')) {
    class XOSIDDummyDB {
        public function get($table = null) { return $this; }
        public function where($key, $val = null) { return $this; }
        public function order_by($col, $dir = '') { return $this; }
        public function limit($limit, $offset = 0) { return $this; }
        public function select($select) { return $this; }
        public function join($table, $cond, $type = '') { return $this; }
        public function result() { return []; }
        public function result_array() { return []; }
        public function row() { return new \stdClass(); }
        public function row_array() { return []; }
        public function __call($name, $args) { return $this; }
        public function __get($name) { return null; }
    }
}

if (!class_exists('XOSIDDummyCatchAll')) {
    class XOSIDDummyCatchAll {
        public function __call($name, $args) { return null; }
        public function __get($name) { return null; }
    }
}

if (!class_exists('XOSIDDummySettingClass')) {
    class XOSIDDummySettingClass {
        public $setting;
        public function __construct($settingsArray) {
            $this->setting = (object)$settingsArray;
        }
        public function __get($name) {
            return $this->setting->{$name} ?? null;
        }
    }
}

if (!class_exists('XOSIDDummyCI')) {
    class XOSIDDummyCI {
        public $session;
        public $db;
        public $list_setting;
        public $config;
        public $setting;
        
        public function __construct() {
            $this->session = new XOSIDDummySession();
            $this->config = new XOSIDDummyConfig();
            $this->db = new XOSIDDummyDB();
            try {
                $settings = \Illuminate\Support\Facades\DB::table('setting_aplikasi')->pluck('value', 'key')->toArray();
            } catch (\Throwable $e) {
                $settings = ['sebutan_desa' => 'Desa', 'current_version' => '26.06'];
            }
            $this->list_setting = (object)$settings;
            $this->setting = new XOSIDDummySettingClass($settings);
        }
        
        public function __get($name) {
            return new XOSIDDummyCatchAll();
        }
        
        public function __call($name, $args) {
            return null;
        }
    }
}

if (!function_exists('ci')) {
    function ci() {
        global $dummy_ci;
        if (!isset($dummy_ci)) {
            $dummy_ci = new XOSIDDummyCI();
        }
        return $dummy_ci;
    }
}

// Register DB event listener to print queries
\Illuminate\Support\Facades\DB::listen(function ($query) {
    echo "QUERY: " . $query->sql . " | Bindings: " . json_encode($query->bindings) . "\n";
});

try {
    $db = new App\Libraries\Database();
    echo "sebutan_desa setting: " . var_export(setting('sebutan_desa'), true) . "\n";
    echo "current_version setting: " . var_export(setting('current_version'), true) . "\n";
    echo "Running checkMigration()...\n";
    $db->checkMigration();
    echo "Success!\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
