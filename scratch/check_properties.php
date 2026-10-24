<?php
define('BASEPATH', '1');
require_once __DIR__ . '/../donjo-app/hooks/RouterHook.php';

$ref = new ReflectionClass('RouterHook');
echo "Properties:\n";
foreach ($ref->getProperties() as $prop) {
    echo "  " . $prop->getName() . " - isPublic: " . var_export($prop->isPublic(), true) . " - isProtected: " . var_export($prop->isProtected(), true) . " - isPrivate: " . var_export($prop->isPrivate(), true) . "\n";
}
