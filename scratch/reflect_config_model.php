<?php
// scratch/reflect_config_model.php

define('BASEPATH', __DIR__ . '/../vendor/codeigniter/framework/system/');
require_once dirname(__DIR__) . '/vendor/autoload.php';

$ref = new ReflectionClass(\App\Models\Config::class);
echo "Class: " . $ref->getName() . "\n";
echo "Methods:\n";
foreach ($ref->getMethods() as $method) {
    if ($method->getDeclaringClass()->getName() === $ref->getName()) {
        echo "  " . $method->getName() . "\n";
    }
}
