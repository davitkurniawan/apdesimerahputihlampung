import re

with open('xosid_superadmin_api.php', 'r') as f:
    content = f.read()

new_api = """    // 7. Endpoints Pembaruan Sistem (Update Manager - Folder A to Folder B)
    $folder_a = '/Users/kampungjagoan/Downloads/OpenSID-Premium';

    if (stripos($req_path, '/api/superadmin/update/check') !== false && $method === 'GET') {
        $active_dir = __DIR__;
        
        // Cek versi Folder A
        $folder_a_version = 'Belum diunduh';
        if (file_exists($folder_a . '/VERSION')) {
            $folder_a_version = trim(file_get_contents($folder_a . '/VERSION'));
        }

        // Cek versi aktif (XOSID / Folder B)
        $current_version = 'v0.0.0';
        if (file_exists($active_dir . '/VERSION')) {
            $current_version = trim(file_get_contents($active_dir . '/VERSION'));
        }

        // Ambil status Git di Folder A
        $git_status = "";
        if (is_dir($folder_a . '/.git')) {
            exec("cd " . escapeshellarg($folder_a) . " && git log -1 --format=\\"%h - %s (%ci)\\"", $git_out);
            $git_status = $git_out[0] ?? "Tidak ada riwayat commit.";
        }

        $update_available = false;
        if ($folder_a_version !== 'Belum diunduh') {
            $curr_clean = ltrim($current_version, 'v');
            $late_clean = ltrim($folder_a_version, 'v');
            if (version_compare($late_clean, $curr_clean, '>')) {
                $update_available = true;
            }
        }

        echo json_encode([
            'status' => 'success',
            'current_version' => $current_version,
            'folder_a_version' => $folder_a_version,
            'git_status' => $git_status,
            'update_available' => $update_available,
            'folder_a_path' => $folder_a
        ]);
        exit;
    }

    // Ganti "Download ZIP" dengan "Git Pull di Folder A"
    if (stripos($req_path, '/api/superadmin/update/pull') !== false && $method === 'POST') {
        if (!is_dir($folder_a . '/.git')) {
            throw new Exception("Folder A ($folder_a) bukan merupakan repositori git.");
        }

        $escaped_dir = escapeshellarg($folder_a);
        
        // Execute git pull
        exec("cd $escaped_dir && git fetch origin && git reset --hard origin/main 2>&1", $output, $return_var);
        
        if ($return_var !== 0) {
            throw new Exception("Gagal melakukan git pull: " . implode("\\n", $output));
        }

        $new_version = 'Tidak diketahui';
        if (file_exists($folder_a . '/VERSION')) {
            $new_version = trim(file_get_contents($folder_a . '/VERSION'));
        }

        echo json_encode([
            'status' => 'success',
            'message' => "Folder A berhasil disinkronkan dengan Github.",
            'version' => $new_version,
            'output' => implode("\\n", $output)
        ]);
        exit;
    }

    if (stripos($req_path, '/api/superadmin/update/diff') !== false && $method === 'GET') {
        if (!is_dir($folder_a) || !file_exists($folder_a . '/VERSION')) {
            throw new Exception("Folder A belum memiliki file rilis. Silakan Tarik Rilis terlebih dahulu.");
        }

        $active_dir = __DIR__;
        $source_dir = $folder_a;

        $protected_patterns = [];
        if (file_exists($active_dir . '/.xosid_protected')) {
            $protected_patterns = file($active_dir . '/.xosid_protected', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }

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

        $directory = new RecursiveDirectoryIterator($source_dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);

        $report = [
            'modified' => [],
            'added' => [],
            'protected_ignored' => [],
            'migrations' => [],
            'total_files' => 0
        ];

        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile()) {
                $real_path = $fileinfo->getPathname();
                $relative_path = substr($real_path, strlen($source_dir) + 1);
                
                if (strpos($relative_path, '.git/') === 0 || 
                    strpos($relative_path, 'desa/') === 0 || 
                    strpos($relative_path, 'uploads/') === 0 ||
                    basename($relative_path) === '.DS_Store') {
                    continue;
                }
                
                $report['total_files']++;
                
                $is_protected = $is_protected_fn($relative_path, $protected_patterns);
                $active_file = $active_dir . '/' . $relative_path;
                
                if ($is_protected) {
                    $report['protected_ignored'][] = $relative_path;
                    continue;
                }
                
                if (strpos($relative_path, 'database/migrations/') !== false || 
                    (strpos($relative_path, 'Modules/') !== false && strpos($relative_path, '/Migration/') !== false)) {
                    $report['migrations'][] = $relative_path;
                }
                
                if (file_exists($active_file)) {
                    if (md5_file($real_path) !== md5_file($active_file)) {
                        $report['modified'][] = $relative_path;
                    }
                } else {
                    $report['added'][] = $relative_path;
                }
            }
        }

        $folder_a_version = trim(file_get_contents($folder_a . '/VERSION'));

        echo json_encode([
            'status' => 'success',
            'version' => $folder_a_version,
            'data' => $report
        ]);
        exit;
    }

    if (stripos($req_path, '/api/superadmin/update/apply') !== false && $method === 'POST') {
        if (!is_dir($folder_a) || !file_exists($folder_a . '/VERSION')) {
            throw new Exception("Folder A tidak valid.");
        }

        $active_dir = __DIR__;
        $source_dir = $folder_a;
        $version = trim(file_get_contents($folder_a . '/VERSION'));

        $escaped_active = escapeshellarg($active_dir);
        $commit_msg_backup = escapeshellarg("backup: sebelum update ke upstream " . $version . " — " . date('Y-m-d H:i'));
        
        exec("cd $escaped_active && git add -A && git commit -m $commit_msg_backup --allow-empty 2>&1", $git_backup_out, $git_backup_code);

        $protected_file = $active_dir . '/.xosid_protected';
        if (!file_exists($protected_file)) {
            throw new Exception("Berkas proteksi .xosid_protected tidak ditemukan di $active_dir");
        }

        $rsync_cmd = "rsync -av " .
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
        }

        $commit_msg_update = escapeshellarg("feat: sinkronisasi upstream Folder A OpenSID ke " . $version);
        exec("cd $escaped_active && git add -A && git commit -m $commit_msg_update --allow-empty 2>&1", $git_update_out, $git_update_code);

        $cache_paths = [
            $active_dir . '/storage/framework/cache/data/*',
            $active_dir . '/storage/framework/cache/*',
            $active_dir . '/storage/framework/views/*',
            $active_dir . '/donjo-app/cache/*'
        ];
        foreach ($cache_paths as $cp) {
            $files = glob($cp);
            if (is_array($files)) {
                foreach ($files as $file) {
                    if (is_file($file)) @unlink($file);
                }
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => "Pembaruan sinkronisasi Folder A $version berhasil diterapkan secara aman.",
            'git_backup' => $git_backup_out,
            'rsync' => $rsync_out[:50] if isinstance($rsync_out, list) else [],
            'git_commit' => $git_update_out
        ]);
        exit;
    }
"""

# Replace the block
pattern = re.compile(r'// 7\. Endpoints Pembaruan Sistem.*?// --- SMART BRIDGE \(MIGRATION\) API ENDPOINTS ---', re.DOTALL)
new_content = pattern.sub(new_api + '\\n\\n    // --- SMART BRIDGE (MIGRATION) API ENDPOINTS ---', content)

with open('xosid_superadmin_api.php', 'w') as f:
    f.write(new_content)
    
print("Updated xosid_superadmin_api.php")
