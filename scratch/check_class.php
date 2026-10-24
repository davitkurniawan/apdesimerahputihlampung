<?php
define('BASEPATH', 'mocked');
require_once '/var/www/html/vendor/autoload.php';
// Declare dummy CI_Controller to allow loading CodeIgniter controller file
if (!class_exists('CI_Controller', false)) {
    class CI_Controller {
        public function __construct() {}
    }
}
if (!class_exists('Admin_Controller', false)) {
    class Admin_Controller extends CI_Controller {
        public function __construct() {
            parent::__construct();
        }
    }
}

$classes_before = get_declared_classes();
require_once '/var/www/html/donjo-app/controllers/Pengguna.php';
$classes_after = get_declared_classes();

$new_classes = array_diff($classes_after, $classes_before);
echo "New classes: " . implode(', ', $new_classes) . "\n";
