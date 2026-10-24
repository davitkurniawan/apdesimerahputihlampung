import re

with open('xosid_superadmin_api.php', 'r') as f:
    php = f.read()

old_apply_block = """        $rsync_cmd = "rsync -av " .
            "--exclude-from=" . escapeshellarg($protected_file) . " " .
            "--exclude='.git/' " .
            "--exclude='desa/' " .
            "--exclude='uploads/' " .
            "--exclude='storage/framework/sessions/' " .
            "--exclude='storage/framework/cache/' " .
            "--exclude='storage/logs/' " .
            "--exclude='storage/invoices/' " .
            "--exclude='storage/uploads/' " .
            escapeshellarg($source_dir . '/') . " " .
            escapeshellarg($active_dir . '/') . " 2>&1";

        exec($rsync_cmd, $rsync_out, $rsync_code);

        if ($rsync_code !== 0) {
            throw new Exception("Gagal menjalankan rsync update: " . implode("\\n", $rsync_out));
        }"""

new_apply_block = """        // Native PHP Copy because rsync is missing in Docker
        $protected_patterns = file($protected_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        $is_protected_fn = function($path, $patterns) {
            foreach ($patterns as $pattern) {
                $pattern = trim($pattern);
                if (empty($pattern) || strpos($pattern, '#') === 0) continue;
                if (substr($pattern, -1) === '/') {
                    if (strpos($path, $pattern) === 0) return true;
                }
                if ($path === $pattern) return true;
                if (strpos($pattern, '*') !== false) {
                    $regex = '/^' . str_replace(['.', '*'], ['\\.', '.*'], $pattern) . '$/';
                    if (preg_match($regex, $path)) return true;
                }
            }
            return false;
        };

        $rsync_out = [];
        $directory = new RecursiveDirectoryIterator($source_dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $fileinfo) {
            $real_path = $fileinfo->getPathname();
            $relative_path = substr($real_path, strlen($source_dir) + 1);
            
            if (strpos($relative_path, '.git/') === 0 || 
                strpos($relative_path, 'desa/') === 0 || 
                strpos($relative_path, 'uploads/') === 0 ||
                strpos($relative_path, 'storage/framework/sessions/') === 0 ||
                strpos($relative_path, 'storage/framework/cache/') === 0 ||
                strpos($relative_path, 'storage/logs/') === 0 ||
                strpos($relative_path, 'storage/invoices/') === 0 ||
                strpos($relative_path, 'storage/uploads/') === 0 ||
                basename($relative_path) === '.DS_Store') {
                continue;
            }

            if ($is_protected_fn($relative_path, $protected_patterns)) {
                continue;
            }

            $target_path = $active_dir . '/' . $relative_path;

            if ($fileinfo->isDir()) {
                if (!is_dir($target_path)) {
                    mkdir($target_path, 0755, true);
                    $rsync_out[] = "created dir " . $relative_path;
                }
            } else {
                // To avoid massive memory/time limit, we only copy if modified
                if (!file_exists($target_path) || md5_file($real_path) !== md5_file($target_path)) {
                    $target_dir = dirname($target_path);
                    if (!is_dir($target_dir)) {
                        mkdir($target_dir, 0755, true);
                    }
                    if (copy($real_path, $target_path)) {
                        $rsync_out[] = "copied " . $relative_path;
                    } else {
                        throw new Exception("Gagal menyalin file: " . $relative_path);
                    }
                }
            }
        }
"""

php = php.replace(old_apply_block, new_apply_block)

with open('xosid_superadmin_api.php', 'w') as f:
    f.write(php)
