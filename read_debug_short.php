<?php
$content = file_get_contents('/var/www/html/debug_dbmanager.txt');
echo substr($content, 0, 200);
