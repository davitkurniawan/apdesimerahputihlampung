<?php
$start = microtime(true);
require_once __DIR__ . '/../xosid_intelligence_engine.php';
$t1 = microtime(true);
$data = xosid_get_intelligence_data();
$t2 = microtime(true);
echo "Load engine: " . ($t1 - $start) . "\n";
echo "Get data: " . ($t2 - $t1) . "\n";
