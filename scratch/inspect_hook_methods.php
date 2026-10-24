<?php
define('BASEPATH', '1');
require_once __DIR__ . '/../donjo-app/hooks/RouterHook.php';

$ref = new ReflectionClass('RouterHook');
foreach (['handlePreSystem', 'handlePostControllerConstructor', 'handlePostController'] as $m) {
    if ($ref->hasMethod($m)) {
        $method = $ref->getMethod($m);
        echo "Method $m:\n";
        echo "  ReturnType: " . ($method->hasReturnType() ? (string)$method->getReturnType() : 'none') . "\n";
        echo "  Parameters:\n";
        foreach ($method->getParameters() as $p) {
            echo "    " . $p->getName() . " - type=" . ($p->hasType() ? (string)$p->getType() : 'none') . " - isOptional=" . ($p->isOptional() ? 'yes' : 'no') . "\n";
        }
    }
}
