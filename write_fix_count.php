<?php
file_put_contents('/var/www/html/fix_count.php', '<?php
namespace App\Repositories;
function count($value = null, $mode = \COUNT_NORMAL) {
    if ($value === null) return 0;
    if (!is_array($value) && !($value instanceof \Countable)) return 1;
    return \count($value, $mode);
}
');
echo "Wrote fix_count.php\n";
