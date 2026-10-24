<?php
copy('/var/www/html/index_new.php', '/var/www/html/index.php');
copy('/var/www/html/debug_hook_new.php', '/var/www/html/debug_hook.php');
copy('/var/www/html/xosid_database_helpers_new.php', '/var/www/html/xosid_database_helpers.php');
echo "sync_done_v2";
?>
