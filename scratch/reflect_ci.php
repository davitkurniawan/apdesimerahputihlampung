<?php
class Reflect_ci extends Admin_Controller {
    public function index() {
        require_once APPPATH . 'controllers/Penduduk.php';
        $rc = new ReflectionClass('Penduduk');
        foreach($rc->getMethods() as $m) {
            echo $m->getName() . "\n";
        }
    }
}
