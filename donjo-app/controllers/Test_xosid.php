<?php
class Test_xosid extends CI_Controller {
    public function index() {
        header('Content-Type: text/plain');
        require_once APPPATH . 'controllers/Identitas_desa.php';
        $ref = new ReflectionClass('Identitas_desa');
        echo "Class: " . $ref->getName() . "\n";
        echo "Parent: " . ($ref->getParentClass() ? $ref->getParentClass()->getName() : 'None') . "\n";
        echo "Properties:\n";
        foreach ($ref->getProperties() as $prop) {
            echo "  " . $prop->getName() . "\n";
        }
        echo "Methods:\n";
        foreach ($ref->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() === $ref->getName()) {
                echo "  " . $method->getName() . "\n";
            }
        }
    }
}


