<?php
function test_ajax_isolated($level, $parent = 0) {
    $cmd = "php -r '
        \$_GET = [\"level\" => \"$level\", \"parent\" => \"$parent\", \"draw\" => 1];
        include \"/var/www/html/xosid_ajax_wilayah.php\";
    '";
    
    $json_output = shell_exec($cmd);
    $data = json_decode($json_output, true);
    
    echo "=== Test Level: $level, Parent: $parent ===\n";
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Error parsing JSON: " . json_last_error_msg() . "\n";
        echo "Raw output: " . substr($json_output, 0, 500) . "...\n";
    } else {
        echo "Records Total: " . $data['recordsTotal'] . "\n";
        echo "Records Filtered: " . $data['recordsFiltered'] . "\n";
        if (!empty($data['data'])) {
            foreach ($data['data'] as $idx => $row) {
                printf("  Row %d:\n", $idx + 1);
                foreach ($row as $k => $v) {
                    if (in_array($k, ['dusun', 'rw', 'rt', 'rws_count', 'rts_count', 'id', 'aksi'])) {
                        echo "    $k: " . (is_array($v) ? json_encode($v) : $v) . "\n";
                    }
                }
            }
        } else {
            echo "  No records returned.\n";
        }
    }
    echo "\n";
}

// 1. Test Dusun level
test_ajax_isolated('dusun');

// 2. Test RW level with parent = 73 (Dusun Mekarjaya II)
test_ajax_isolated('rw', 73);

// 3. Test RT level with parent = 74 (RW -)
test_ajax_isolated('rt', 74);
