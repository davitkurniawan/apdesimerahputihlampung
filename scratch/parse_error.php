<?php
$js = file_get_contents('/var/www/html/scratch/ignition_script_5.js');
$pos = strpos($js, 'window.fromModel=');
if ($pos !== false) {
    echo "Found window.fromModel!\n";
    $snippet = substr($js, $pos, 2000);
    echo $snippet . "\n";
} else {
    echo "Could not find window.fromModel.\n";
}
