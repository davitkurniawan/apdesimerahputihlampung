<?php
define('BASEPATH', '1');
require_once __DIR__ . '/../donjo-app/hooks/RouterHook.php';

$ref = new ReflectionMethod('RouterHook', 'processWebRouteParameters');
echo "processWebRouteParameters isPublic: " . var_export($ref->isPublic(), true) . "\n";
echo "processWebRouteParameters isProtected: " . var_export($ref->isProtected(), true) . "\n";
echo "processWebRouteParameters isPrivate: " . var_export($ref->isPrivate(), true) . "\n";
