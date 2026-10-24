<?php
header('Content-Type: text/plain');
if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        echo "OPcache reset successful!\n";
    } else {
        echo "OPcache reset failed!\n";
    }
}
