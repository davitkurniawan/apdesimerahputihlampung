<?php
/**
 * Setup XOSID layout backups for the Immortality Shield engine.
 */

$backup_dir = __DIR__ . '/../storage/xosid_backups';
if (!file_exists($backup_dir)) {
    mkdir($backup_dir, 0777, true);
}

$files_to_backup = [
    'resources/views/admin/layouts/index.blade.php',
    'resources/views/admin/layouts/partials/sidebar.blade.php',
    'resources/views/admin/layouts/partials/header.blade.php',
    'resources/views/admin/layouts/partials/footer.blade.php',
    'resources/views/admin/identitas_desa/index.blade.php',
    'resources/views/admin/pengguna/index.blade.php',
    'resources/views/admin/pengguna/tab-2fa.blade.php',
    'resources/views/admin/pengguna/tab-profil.blade.php',
    'resources/views/admin/pengguna/tab-sandi.blade.php',
    'resources/views/admin/pengaturan/pengguna/form.blade.php',
    'resources/views/admin/pengaturan/pengguna/index.blade.php',
    'resources/views/admin/pengaturan/pengguna/menu.blade.php'
];

echo "Creating XOSID layout backups...\n";

foreach ($files_to_backup as $file) {
    $src = __DIR__ . '/../' . $file;
    if (!file_exists($src)) {
        echo "[ERROR] Source file missing: $file\n";
        continue;
    }
    
    // Create subdirs in backups if needed
    $dest = $backup_dir . '/' . $file;
    $dest_dir = dirname($dest);
    if (!file_exists($dest_dir)) {
        mkdir($dest_dir, 0777, true);
    }
    
    if (copy($src, $dest)) {
        echo "[ OK ] Backed up: $file\n";
    } else {
        echo "[FAIL] Failed to back up: $file\n";
    }
}

echo "Setup completed successfully.\n";
