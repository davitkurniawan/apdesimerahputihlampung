<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Method_dumper extends CI_Controller {
    public function index() {
        require_once APPPATH . 'controllers/Penduduk.php';
        $methods = get_class_methods('Penduduk');
        echo "<pre>";
        print_r($methods);
        echo "</pre>";
    }
}
