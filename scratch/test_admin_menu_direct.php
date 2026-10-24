<?php
define('BASEPATH', '/var/www/html/vendor/codeigniter/framework/system/');
define('APPPATH', '/var/www/html/donjo-app/');
define('FCPATH', '/var/www/html/');
define('ENVIRONMENT', 'production');

require_once '/var/www/html/vendor/autoload.php';

// Mock DB and CI instance to avoid fatal errors
class DummyCI {
    public $db;
    public $session;
    public function __construct() {
        $this->session = new class {
            public function userdata() { return 1; }
        };
        $this->db = new class {
            public function select() { return $this; }
            public function where() { return $this; }
            public function get() { return $this; }
            public function result_array() {
                return [
                    ['id' => 15, 'modul' => 'Identitas Wilayah', 'slug' => 'identitas-wilayah', 'url' => 'identitas_desa', 'ikon' => 'fa-dashboard', 'parent' => 0]
                ];
            }
            public function result() { return []; }
        };
    }
}
if (!function_exists('get_instance')) {
    function get_instance() {
        static $inst;
        if (!$inst) $inst = new DummyCI();
        return $inst;
    }
}

require_once APPPATH . 'helpers/general_helper.php';

if (function_exists('admin_menu')) {
    $menu = admin_menu();
    print_r($menu);
} else {
    echo "admin_menu function not found\n";
}
