<?php
function safe_copy($src, $dest) {
    if (file_exists($dest)) {
        unlink($dest);
    }
    return copy($src, $dest);
}

safe_copy('/var/www/html/index_new.php', '/var/www/html/index.php');
safe_copy('/var/www/html/debug_hook_new.php', '/var/www/html/debug_hook.php');
safe_copy('/var/www/html/xosid_database_helpers_new.php', '/var/www/html/xosid_database_helpers.php');
echo "sync_done_v3";
?>
