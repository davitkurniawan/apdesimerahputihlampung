<?php
// scratch/inspect_identitas.php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../xosid_emergency_autoload.php';

define('ENVIRONMENT', 'production');
define('FCPATH', dirname(__DIR__) . '/');
define('BASEPATH', dirname(__DIR__) . '/vendor/codeigniter/framework/system/');
define('APPPATH', dirname(__DIR__) . '/donjo-app/');

require_once APPPATH . 'core/MY_Controller.php';
require_once APPPATH . 'controllers/Identitas_desa.php';

$ref = new ReflectionClass('Identitas_desa');
echo "Class: " . $ref->getName() . "\n";
echo "Parent Class: " . ($ref->getParentClass() ? $ref->getParentClass()->getName() : 'None') . "\n";
echo "Methods:\n";
foreach ($ref->getMethods() as $m) {
    echo "  - " . $m->getName() . " (declaring class: " . $m->getDeclaringClass()->getName() . ")\n";
}
echo "Properties:\n";
foreach ($ref->getProperties() as $p) {
    echo "  - " . $p->getName() . "\n";
}
