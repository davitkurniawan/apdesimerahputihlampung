<?php
/**
 * XOSID System Integrity & Regression Prevention Tool
 * 
 * Run this script via CLI to verify layout files, styling overrides,
 * database menu integrity, and configuration states.
 * 
 * Usage: php xosid_integrity_check.php [tenant_database_name]
 */

if (php_sapi_name() !== 'cli') {
    die("This tool can only be run via CLI.\n");
}

echo "====================================================\n";
echo "       XOSID NEURAL SYSTEM INTEGRITY CHECKER        \n";
echo "====================================================\n\n";

$errors = [];
$warnings = [];

// 1. VERIFY FILE INTEGRITY
echo "[1/4] Checking file integrity...\n";

$critical_files = [
    'resources/views/admin/layouts/index.blade.php' => [
        'must_contain' => ['XOSID PREMIUM DESIGN SYSTEM', 'lucide', 'Outfit'],
        'must_not_contain' => ['bg-identitas', 'img-identitas']
    ],
    'resources/views/admin/layouts/partials/sidebar.blade.php' => [
        'must_contain' => ['XOSID NEURAL SIDEBAR', 'xosid-menu-label', 'usort($menu_tree']
    ],
    'resources/views/admin/layouts/partials/header.blade.php' => [
        'must_contain' => ['XOSID LOGO AREA']
    ],
    'resources/views/admin/layouts/partials/footer.blade.php' => [
        'must_contain' => ['XO', 'xosid-footer-link', 'margin-left: 0 !important']
    ],
    'resources/views/admin/identitas_desa/index.blade.php' => [
        'must_contain' => ['NEURAL VILLAGE IDENTITY WIDGETS', 'neural-card']
    ],
    'resources/views/admin/pengguna/index.blade.php' => [
        'must_contain' => ['neural-card', 'profile-image-wrapper', 'foto_pengguna']
    ]
];

foreach ($critical_files as $file => $rules) {
    $path = __DIR__ . '/' . $file;
    if (!file_exists($path)) {
        $errors[] = "File missing: $file";
        echo "  [FAIL] $file (File not found)\n";
        continue;
    }

    $content = file_get_contents($path);
    $file_ok = true;

    if (isset($rules['must_contain'])) {
        foreach ($rules['must_contain'] as $term) {
            if (strpos($content, $term) === false) {
                $errors[] = "File '$file' is missing required signature: '$term'. It might have been reverted to default OpenSID.";
                $file_ok = false;
            }
        }
    }

    if (isset($rules['must_not_contain'])) {
        foreach ($rules['must_not_contain'] as $term) {
            if (strpos($content, $term) !== false) {
                $errors[] = "File '$file' contains legacy terms: '$term'. Legacy styling override might be active.";
                $file_ok = false;
            }
        }
    }

    if ($file_ok) {
        echo "  [ OK ] $file\n";
    } else {
        echo "  [FAIL] $file\n";
    }
}

// 2. VERIFY GLOBAL CSS REGRESSION RISKS
echo "\n[2/4] Checking layout style safety...\n";
$layout_path = __DIR__ . '/resources/views/admin/layouts/index.blade.php';
if (file_exists($layout_path)) {
    $layout_content = file_get_contents($layout_path);
    if (strpos($layout_content, 'body .content-wrapper .box-body a {') !== false || strpos($layout_content, 'body .content-wrapper .box-body a,') !== false) {
        $errors[] = "Aggressive link override selector detected in index.blade.php! Ensure 'body .content-wrapper .box-body a' is NOT used as a global style.";
        echo "  [FAIL] Detected aggressive 'body .content-wrapper .box-body a' style override\n";
    } else {
        echo "  [ OK ] Style selector safety check passed\n";
    }
}

// 3. DATABASE MENU INTEGRITY
echo "\n[3/4] Checking database menu structure...\n";

$db_name = isset($argv[1]) ? $argv[1] : 'xosid_tenant_bumidaya';
$db_host = getenv('DB_HOST') ?: (file_exists('/.dockerenv') ? 'db' : '127.0.0.1');
$db_user = getenv('DB_USERNAME') ?: 'root';
$db_pass = getenv('DB_PASSWORD') ?: 'password';

$conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    $warnings[] = "Could not connect to database '$db_name' (User: $db_user on $db_host): " . $conn->connect_error;
    echo "  [WARN] Database connection skipped ($db_name). Make sure database credentials are set correctly.\n";
} else {
    // Check custom menu categories
    $expected_menus = [
        'Layanan Warga' => 35,
        'Data Pendukung' => 36,
        'Pengaturan Sistem' => 37
    ];

    foreach ($expected_menus as $menu_name => $expected_urut) {
        $stmt = $conn->prepare("SELECT id, urut, aktif FROM setting_modul WHERE modul = ? AND parent = 0");
        $stmt->bind_param("s", $menu_name);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        if (!$res) {
            $errors[] = "Custom menu '$menu_name' is missing from 'setting_modul' table. Run 'scratch/restore_menu_xosid.sql' to recreate it.";
            echo "  [FAIL] Menu '$menu_name' not found in database\n";
        } elseif ($res['aktif'] != 1) {
            $errors[] = "Custom menu '$menu_name' is disabled in database.";
            echo "  [FAIL] Menu '$menu_name' is inactive\n";
        } else {
            echo "  [ OK ] Menu '$menu_name' (urut={$res['urut']}) is active\n";
        }
        $stmt->close();
    }
    
    // Check if submenus have been moved correctly
    $kesehatan_stmt = $conn->prepare("SELECT parent FROM setting_modul WHERE modul = 'Kesehatan' LIMIT 1");
    $kesehatan_stmt->execute();
    $kesehatan = $kesehatan_stmt->get_result()->fetch_assoc();
    if ($kesehatan) {
        $parent_id = $kesehatan['parent'];
        $parent_stmt = $conn->prepare("SELECT modul FROM setting_modul WHERE id = ?");
        $parent_stmt->bind_param("i", $parent_id);
        $parent_stmt->execute();
        $parent = $parent_stmt->get_result()->fetch_assoc();
        
        if (!$parent || $parent['modul'] !== 'Layanan Warga') {
            $errors[] = "Sub-menu 'Kesehatan' is not nested under 'Layanan Warga'. Its parent is currently ID: $parent_id (" . ($parent['modul'] ?? 'unknown') . ").";
            echo "  [FAIL] Menu reorganization check failed (Kesehatan parent is " . ($parent['modul'] ?? 'unknown') . ")\n";
        } else {
            echo "  [ OK ] Submenu routing and nesting verified\n";
        }
        $parent_stmt->close();
    }
    $kesehatan_stmt->close();
    
    $conn->close();
}

// 4. CONCLUSION
echo "\n[4/4] Verification Summary:\n";

if (empty($errors)) {
    echo "\n  \033[32;1m✓ ALL CHECKS PASSED. XOSID SYSTEM INTEGRITY SECURED.\033[0m\n";
    if (!empty($warnings)) {
        echo "  Note warnings:\n";
        foreach ($warnings as $w) {
            echo "  - $w\n";
        }
    }
    echo "====================================================\n";
    exit(0);
} else {
    echo "\n  \033[31;1m✗ CRITICAL INTEGRITY ISSUES DETECTED:\033[0m\n";
    foreach ($errors as $e) {
        echo "  - \033[31m$e\033[0m\n";
    }
    if (!empty($warnings)) {
        echo "  Warnings:\n";
        foreach ($warnings as $w) {
            echo "  - $w\n";
        }
    }
    echo "\n  \033[33;1mHow to fix:\033[0m\n";
    echo "  1. If views are broken, restore them from your last known good commit or git check out.\n";
    echo "  2. Run 'docker exec -i xosid_neural_db mysql -u root -ppassword <tenant_db> < scratch/restore_menu_xosid.sql' to restore custom sidebar structure.\n";
    echo "  3. Run 'docker exec xosid_neural_web rm -f /var/www/html/storage/framework/views/*.php' to clear template cache.\n";
    echo "====================================================\n";
    exit(1);
}
