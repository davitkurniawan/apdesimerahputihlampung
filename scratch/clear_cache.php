<?php
$dir = '../storage/framework/views/';
foreach(glob($dir . '*') as $file) {
    if(is_file($file)) unlink($file);
}
echo "Cleared";
