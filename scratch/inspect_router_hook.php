<?php
define('BASEPATH', '1');
require_once __DIR__ . '/../donjo-app/hooks/RouterHook.php';

$ref = new ReflectionClass('RouterHook');
echo "Static Properties:\n";
foreach ($ref->getProperties(ReflectionProperty::IS_STATIC) as $prop) {
    echo "  " . $prop->getName() . "\n";
}
echo "Static Methods:\n";
foreach ($ref->getMethods(ReflectionMethod::IS_STATIC) as $method) {
    echo "  " . $method->getName() . "\n";
}
echo "Methods:\n";
foreach ($ref->getMethods() as $method) {
    if (!$method->isStatic()) {
        echo "  " . $method->getName() . "\n";
    }
}
