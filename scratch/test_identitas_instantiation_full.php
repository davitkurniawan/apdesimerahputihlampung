<?php
// scratch/test_identitas_instantiation_full.php

define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('SELF', 'index.php');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');
define('VIEWPATH', APPPATH . 'views/');
define('DESAPATH', FCPATH . 'desa/');
define('STORAGEPATH', FCPATH . 'storage/');
define('MODPATH', FCPATH . 'Modules/');
define('FORCE_HTTPS', false);

$_SERVER['HTTP_HOST'] = 'bondo-bangsri.localhost:8080';
$_SERVER['REQUEST_URI'] = '/index.php/identitas_desa';
$_SERVER['PATH_INFO'] = '/identitas_desa';
$_SERVER['QUERY_STRING'] = '';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/xosid_emergency_autoload.php';

// Mock session user to avoid login redirect
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!class_exists('XosidSessionUser')) {
    class XosidSessionUser implements ArrayAccess, JsonSerializable {
        public $id = 1;
        public $username = 'admin';
        public $id_grup = 1;
        public $grup = 1;
        public $batasi_wilayah = 0;
        public $nama = 'Administrator';
        public $foto = 'user.png';
        public $email = 'admin@desa.id';
        public $email_verified_at = '2026-01-01 00:00:00';
        public $notif_telegram = 0;
        public $id_telegram = '';
        public $userGrup;
        public function __construct() {
            $this->userGrup = (object)['nama' => 'Administrator'];
        }

        #[\ReturnTypeWillChange]
        public function offsetExists($offset) { return isset($this->$offset); }
        #[\ReturnTypeWillChange]
        public function offsetGet($offset) { return $this->$offset ?? null; }
        #[\ReturnTypeWillChange]
        public function offsetSet($offset, $value) { $this->$offset = $value; }
        #[\ReturnTypeWillChange]
        public function offsetUnset($offset) { unset($this->$offset); }
        public function __get($name) { return $this->$name ?? null; }
        public function __set($name, $value) { $this->$name = $value; }
        public function __isset($name) { return isset($this->$name); }
        public function __toString() { return 'admin'; }
        #[\ReturnTypeWillChange]
        public function jsonSerialize() {
            return [
                'id' => $this->id,
                'username' => $this->username,
                'id_grup' => $this->id_grup,
                'grup' => $this->grup,
                'batasi_wilayah' => $this->batasi_wilayah,
                'nama' => $this->nama
            ];
        }
    }
}
$userObj = new XosidSessionUser();
$_SESSION['user'] = $userObj;
$_SESSION['user_id'] = $userObj;
$_SESSION['username'] = $userObj;
$_SESSION['nama'] = 'Administrator';
$_SESSION['siteman'] = 1;
$_SESSION['grup'] = 1;
$_SESSION['admin_level'] = 1;
$_SESSION['sesi'] = 'aktif';

// Mock CLI arguments for CodeIgniter router
$GLOBALS['argv'] = ['index.php', 'identitas_desa', 'index'];
$GLOBALS['argc'] = 3;
$_SERVER['argv'] = ['index.php', 'identitas_desa', 'index'];
$_SERVER['argc'] = 3;

// Fully boot CodeIgniter
try {
    echo "Booting CodeIgniter with route to identitas_desa...\n";
    require_once BASEPATH . 'core/CodeIgniter.php';
} catch (\Throwable $e) {
    echo "\n\n========================================\n";
    echo "CRASH DETECTED:\n";
    echo "Exception: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
    echo "========================================\n";
}
