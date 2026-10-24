<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
    public function index() {
        require_once APPPATH . 'controllers/Penduduk.php';
        echo implode("\n", get_class_methods('Penduduk'));
    }
}
