<?php
// Include the autoloaders and boot minimal environment
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Include helpers
$helpers = [
    'donjo-app/helpers/pict_helper.php',
    'donjo-app/helpers/illuminate_helper.php',
    'donjo-app/helpers/web_helper.php',
    'donjo-app/helpers/general_helper.php',
    'donjo-app/helpers/opensid_helper.php',
];

foreach ($helpers as $h) {
    $path = dirname(__DIR__) . '/' . $h;
    if (file_exists($path)) {
        @include_once $path;
    }
}

if (function_exists('AmbilFotoArtikel')) {
    $ref = new ReflectionFunction('AmbilFotoArtikel');
    echo "FOUND: " . $ref->getFileName() . ":" . $ref->getStartLine() . "\n";
} else {
    echo "NOT FOUND in direct helpers. Checking all loaded functions...\n";
}
