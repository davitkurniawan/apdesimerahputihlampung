<?php 
$CI =& get_instance();
$CI->load->helper('url');
$db = $CI->db;

if (empty($desa)) {
    $desa = function_exists('identitas') ? identitas() : [];
}

if (defined('FCPATH') && file_exists(FCPATH . 'xosid_database_helpers.php')) {
    include_once FCPATH . 'xosid_database_helpers.php';
}
$tenant_paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
$is_preview_active = !empty($_COOKIE['xosid_preview_enterprise']) || !empty($_SESSION['xosid_preview_enterprise']) || (isset($_GET['preview_enterprise']) && $_GET['preview_enterprise'] == '1') || (isset($_GET['switch_paket']) && $_GET['switch_paket'] === 'enterprise') || file_exists(sys_get_temp_dir() . '/xosid_preview_tamanasri.txt') || (defined('FCPATH') && file_exists(FCPATH . 'storage/xosid_preview_active.txt'));
$is_esensial = ($tenant_paket === 'esensial' && !$is_preview_active);
$GLOBALS['is_esensial'] = $is_esensial;

// Fetch All Active Modules (PURE NAMES)
$all_modules = $db->select('*')
                  ->from('setting_modul')
                  ->where('aktif', 1)
                  ->where('hidden', 0)
                  ->order_by('parent', 'ASC')
                  ->order_by('urut', 'ASC')
                  ->get()
                  ->result_array();

// Find or identify container IDs dynamically
$integrasi_id = 352;
$layanan_warga_id = 550;
$data_pendukung_id = 551;
$pengaturan_sistem_id = 552;
$digital_office_id = 4;
$dtsen_id = 512;

foreach ($all_modules as $mod_check) {
    $m_name = trim($mod_check['modul'] ?? '');
    $m_slug = trim($mod_check['slug'] ?? '');
    $m_id = (int)$mod_check['id'];
    if ($m_name === 'Integrasi Data' || $m_slug === 'satu-data' || $m_id === 352) {
        $integrasi_id = $m_id;
    } elseif ($m_name === 'Layanan Warga' || $m_slug === 'layanan_warga' || $m_id === 550 || $m_id === 547) {
        $layanan_warga_id = $m_id;
    } elseif ($m_name === 'Data Pendukung' || $m_slug === 'data_pendukung' || $m_id === 551 || $m_id === 548) {
        $data_pendukung_id = $m_id;
    } elseif ($m_name === 'Pengaturan Sistem' || $m_slug === 'pengaturan_sistem' || $m_id === 552 || $m_id === 549) {
        $pengaturan_sistem_id = $m_id;
    } elseif ($m_name === 'Digital Office' || $m_slug === 'digital_office' || $m_id === 4) {
        $digital_office_id = $m_id;
    } elseif ($m_name === 'DTSEN' || $m_slug === 'dtsen' || $m_id === 512) {
        $dtsen_id = $m_id;
    }
}

// STRICT XOSID ESSENTIAL & ENTERPRISE MENU NORMALIZATION AND LOCK
foreach ($all_modules as &$m) {
    $mid = (int)$m['id'];
    $mname = trim($m['modul'] ?? '');
    $mslug = trim($m['slug'] ?? '');
    $murl = strtolower(trim($m['url'] ?? ''));

    // Module Name Aliases
    if ($mname === 'Sistem Data Rakyat') {
        $m['modul'] = 'Kependudukan';
    }
    if ($mid === 358 || $mname === 'Pemerintah Desa') {
        $m['modul'] = 'Pemerintahan Desa';
        $m['url'] = 'pengurus';
    }
    if ($mid === 13 || $mname === 'Admin Web') {
        $m['modul'] = 'Kelola Portal Desa';
    }
    if ($mid === 352 || $mname === 'Satu Data' || $mslug === 'satu-data') {
        $m['modul'] = 'Integrasi Data';
        $m['parent'] = 0;
        $m['urut'] = 40;
    }
    if ($mid === 5 || $mname === 'Analisis' || $mslug === 'analisis') {
        $m['modul'] = 'Survey dan Analisa';
    }
    if ($mid === 3 || $mname === 'Neural Analytics' || $mslug === 'statistik') {
        $m['modul'] = 'Data dan Statistik';
    }
    if ($mid === 469 || $mname === 'Pengaduan') {
        $m['modul'] = 'Pengaduan Warga';
    }
    if ($mid === 10 || $mname === 'Hubung Warga') {
        $m['modul'] = 'Kontak Warga';
    }
    if ($mid === 459 || $mname === 'XOSID Market') {
        $m['modul'] = 'OLShop Warga';
    }
    if ($mid === 337 || $mname === 'Kehadiran') {
        $m['modul'] = 'Digital Presensi';
    }
    if ($mid === 15 || $mname === 'Sekretariat') {
        $m['modul'] = 'Kesekretariatan';
    }

    // Top Level Urut Enforcements
    if ($mid === 200) { $m['parent'] = 0; $m['urut'] = 10; }
    if ($mid === 540) { $m['parent'] = 0; $m['urut'] = 20; }
    if ($mid === 600) { $m['parent'] = 0; $m['urut'] = 21; }
    if ($mid === 2)   { $m['parent'] = 0; $m['urut'] = 30; }
    if ($mid === 4)   { $m['parent'] = 0; $m['urut'] = 31; }
    if ($m['modul'] === 'Layanan Warga' || $mid === $layanan_warga_id) { $m['parent'] = 0; $m['urut'] = 35; }
    if ($m['modul'] === 'Data Pendukung' || $mid === $data_pendukung_id) { $m['parent'] = 0; $m['urut'] = 36; }
    if ($m['modul'] === 'Pengaturan Sistem' || $mid === $pengaturan_sistem_id) { $m['parent'] = 0; $m['urut'] = 37; }
    if ($m['modul'] === 'Integrasi Data' || $mid === $integrasi_id) { $m['parent'] = 0; $m['urut'] = 40; }

    // INTEGRASI DATA LOCK: DTKS, DTSEN, Prodeskel, PPID, Data Presisi
    if ($mid === 484 || $mslug === 'dtks' || $murl === 'dtks') {
        $m['parent'] = $integrasi_id;
        $m['urut'] = 1;
    } elseif ($mid === 512 || $mslug === 'dtsen' || $mname === 'DTSEN') {
        $m['parent'] = $integrasi_id;
        $m['urut'] = 2;
    } elseif ($mid === 555 || $mslug === 'prodeskel' || $murl === 'prodeskel') {
        $m['parent'] = $integrasi_id;
        $m['urut'] = 3;
    } elseif ($mid === 561 || $mslug === 'ppid' || $murl === 'ppid') {
        $m['parent'] = $integrasi_id;
        $m['urut'] = 4;
    } elseif ($mid === 568 || $mslug === 'data-presisi' || $murl === 'data_presisi') {
        $m['parent'] = $integrasi_id;
        $m['urut'] = 5;
    }

    // DTSEN Sub-items Lock (under DTSEN ID)
    if (in_array($mid, [513, 514, 546]) || str_starts_with($murl, 'dtsen/')) {
        $m['parent'] = $dtsen_id;
    }

    // DATA PENDUKUNG LOCK: Pemetaan, Pembangunan, Pertanahan, Survey dan Analisa, Data dan Statistik
    if ($mid === 9) { $m['parent'] = $data_pendukung_id; $m['urut'] = 1; }
    if ($mid === 442) { $m['parent'] = $data_pendukung_id; $m['urut'] = 2; }
    if ($mid === 7) { $m['parent'] = $data_pendukung_id; $m['urut'] = 3; }
    if ($mid === 5 || $m['modul'] === 'Survey dan Analisa') { $m['parent'] = $data_pendukung_id; $m['urut'] = 4; }
    if ($mid === 3 || $m['modul'] === 'Data dan Statistik') { $m['parent'] = $data_pendukung_id; $m['urut'] = 5; }

    // LAYANAN WARGA LOCK: Kesehatan, Bantuan, Pengaduan, Kontak, Layanan Mandiri, Buku Tamu, OLShop
    if ($mid === 206) { $m['parent'] = $layanan_warga_id; $m['urut'] = 1; }
    if ($mid === 6) { $m['parent'] = $layanan_warga_id; $m['urut'] = 2; }
    if ($mid === 469 || $m['modul'] === 'Pengaduan Warga') { $m['parent'] = $layanan_warga_id; $m['urut'] = 3; }
    if ($mid === 10 || $m['modul'] === 'Kontak Warga') { $m['parent'] = $layanan_warga_id; $m['urut'] = 4; }
    if ($mid === 14) { $m['parent'] = $layanan_warga_id; $m['urut'] = 5; }
    if ($mid === 354) { $m['parent'] = $layanan_warga_id; $m['urut'] = 6; }
    if ($mid === 459 || $m['modul'] === 'OLShop Warga') { $m['parent'] = $layanan_warga_id; $m['urut'] = 7; }

    // DIGITAL OFFICE LOCK: Digital Presensi, Kesekretariatan, Surat Dinas, Buku Administrasi
    if ($mid === 493) { $m['parent'] = $digital_office_id; $m['urut'] = 2; }
    if ($mid === 301) { $m['parent'] = $digital_office_id; $m['urut'] = 3; }
    if ($mid === 337 || $m['modul'] === 'Digital Presensi') { $m['parent'] = $digital_office_id; $m['urut'] = 4; }
    if ($mid === 15 || $m['modul'] === 'Kesekretariatan') { $m['parent'] = $digital_office_id; $m['urut'] = 6; }

    // PENGATURAN SISTEM LOCK: Pengaturan, Kelola Portal Desa, Anjungan
    if ($mid === 11) { $m['parent'] = $pengaturan_sistem_id; $m['urut'] = 1; }
    if ($mid === 13 || $m['modul'] === 'Kelola Portal Desa') { $m['parent'] = $pengaturan_sistem_id; $m['urut'] = 2; }
    if ($mid === 312) { $m['parent'] = $pengaturan_sistem_id; $m['urut'] = 3; }
}
unset($m);

// Build Tree
$menu_tree = [];
$items_by_id = [];
foreach ($all_modules as $m) {
    $m['childrens'] = [];
    $items_by_id[$m['id']] = $m;
}
foreach ($items_by_id as $id => &$item) {
    if ($item['parent'] == 0) {
        $menu_tree[] = &$item;
    } else {
        if (isset($items_by_id[$item['parent']])) {
            $items_by_id[$item['parent']]['childrens'][] = &$item;
        }
    }
}
unset($item);

// Sort top-level menu by urut ASC
usort($menu_tree, function($a, $b) {
    $ua = isset($a['urut']) ? (int)$a['urut'] : 999;
    $ub = isset($b['urut']) ? (int)$b['urut'] : 999;
    if ($ua === $ub) return (int)$a['id'] - (int)$b['id'];
    return $ua - $ub;
});

// Sort children by urut ASC as well
foreach ($menu_tree as &$top_item) {
    if (!empty($top_item['childrens'])) {
        usort($top_item['childrens'], function($a, $b) {
            $ua = isset($a['urut']) ? (int)$a['urut'] : 999;
            $ub = isset($b['urut']) ? (int)$b['urut'] : 999;
            if ($ua === $ub) return (int)$a['id'] - (int)$b['id'];
            return $ua - $ub;
        });
    }
}
unset($top_item);

// FILTERING LOGIC
$menu_tree = array_values(array_filter($menu_tree, function($item) {
    return !in_array(trim($item['modul']), ['Supradesa', 'OpenDK']);
}));

foreach ($menu_tree as &$item) {
    if ($item['modul'] == 'Identitas Wilayah' && !empty($item['childrens'])) {
        $item['childrens'] = array_values(array_filter($item['childrens'], function($child) {
            return !in_array($child['modul'], ['Layanan Pelanggan', 'Kategori Lembaga', 'Pendaftaran Kerjasama']);
        }));
    }
}
unset($item);

// Simple Icon Mapping
if (!function_exists('xosid_get_icon')) {
    function xosid_get_icon($fa_icon) {
        $map = [
            'fa-home' => 'home', 
            'fa-users' => 'users', 
            'fa-user' => 'user',
            'fa-file-text' => 'file-text', 
            'fa-database' => 'database',
            'fa-map' => 'map', 
            'fa-gear' => 'settings', 
            'fa-bar-chart' => 'pie-chart',
            'fa-heartbeat' => 'heart-pulse',
            'fa-heart' => 'heart',
            'fa-envelope' => 'mail',
            'fa-inbox' => 'inbox',
            'fa-book' => 'book-open',
            'fa-info' => 'info',
            'fa-phone' => 'phone',
            'fa-comments' => 'messages-square',
            'fa-comment' => 'message-square',
            'fa-archive' => 'archive',
            'fa-check-square' => 'clipboard-check',
            'fa-map-signs' => 'map-pin',
            'fa-globe' => 'globe',
            'fa-institution' => 'landmark',
            'fa-desktop' => 'monitor',
            'fa-cogs' => 'sliders',
            'fa-cog' => 'settings',
            'line-chart' => 'trending-up',
            'fa-line-chart' => 'trending-up',
            'fa-cart-plus' => 'shopping-cart',
            'fa-exchange' => 'arrow-left-right',
        ];
        foreach($map as $key => $val) {
            if(strpos($fa_icon, str_replace('fa-', '', $key)) !== false) return $val;
        }
        return 'circle-dot';
    }
}

$is_beranda_active = ($CI->uri->segment(1) == 'beranda' || empty($CI->uri->segment(1)) || $CI->uri->segment(1) == 'main');
?>

<aside class="main-sidebar" style="background: <?= $is_esensial ? '#153E7E' : '#0f172a' ?> !important; border-right: 1px solid <?= $is_esensial ? 'rgba(255, 255, 255, 0.12)' : 'rgba(6, 182, 212, 0.1)' ?>; box-shadow: none !important;">
    <section class="sidebar">
        <!-- NEURAL IDENTITY BRANDING (SEAMLESS SINGLE COLOR BACKGROUND) -->
        <div class="user-panel xosid-village-panel" style="padding: 20px 20px 15px; text-align: center; border-bottom: none !important; background: transparent !important;">
            <div class="full-logo">
                <div style="width: 80px; height: 80px; background: <?= $is_esensial ? '#ffffff' : 'rgba(15, 23, 42, 0.8)' ?>; border-radius: 20px; border: 1px solid rgba(255,255,255,<?= $is_esensial ? '0.3' : '0.1' ?>); padding: 12px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,<?= $is_esensial ? '0.08' : '0.5' ?>);">
                    <?php 
                        $logo_display = !empty($desa['logo']) ? base_url('desa/logo/' . $desa['logo']) : base_url('assets/images/xosid_logo.png');
                    ?>
                    <img src="<?= $logo_display ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
                <h1 style="color: white; font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 16px; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;"><?= $desa['nama_desa'] ?></h1>
                <div style="margin-top: 5px;">
                    <div style="font-size: 10px; color: <?= $is_esensial ? 'rgba(255, 255, 255, 0.95)' : '#06b6d4' ?>; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">KEC. <?= $desa['nama_kecamatan'] ?></div>
                    <div style="font-size: 9px; color: <?= $is_esensial ? 'rgba(255, 255, 255, 0.8)' : '#64748b' ?>; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;"><?= $desa['nama_kabupaten'] ?> - <?= $desa['nama_propinsi'] ?></div>
                </div>
            </div>
            <div class="mini-logo">
                <img src="<?= $logo_display ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree" style="padding: 0 10px;">
            <li class="header xosid-menu-header" style="background: transparent; color: <?= $is_esensial ? 'rgba(255, 255, 255, 0.65)' : '#475569' ?>; font-size: 10px; font-weight: 800; letter-spacing: 2px; padding: 15px 15px 10px;">UTAMA</li>
            
            <li style="margin: 2px 0;" class="<?= $is_beranda_active ? 'active' : '' ?>">
                <a href="<?= site_url('beranda') ?>" style="display: flex; align-items: center;">
                    <div class="sidebar-icon-box" style="flex-shrink: 0; display: flex; align-items: center; justify-content: center;"><i data-lucide="layout-dashboard" style="width: 18px;"></i></div>
                    <span class="xosid-menu-label"><?= $is_esensial ? 'Dashboard Desa' : 'Intelligence Center' ?></span>
                </a>
            </li>

            <?php if (!$is_esensial): ?>
            <li style="margin: 2px 0;">
                <a href="<?= site_url('beranda?view=katadesa') ?>" style="display: flex; align-items: center; background: rgba(6, 182, 212, 0.05); border-left: 3px solid #06b6d4;">
                    <div class="sidebar-icon-box" style="flex-shrink: 0; display: flex; align-items: center; justify-content: center;"><i data-lucide="brain-circuit" style="width: 18px; color: #06b6d4;"></i></div>
                    <span class="xosid-menu-label" style="color: #f1f5f9; font-weight: 700;">KATADESA AI</span>
                </a>
            </li>

            <?php
            $is_analisis_active = (strpos($_SERVER['REQUEST_URI'] ?? '', 'analisis_cerdas') !== false || (isset($_SESSION['xosid_tab']) && $_SESSION['xosid_tab'] === 'analisis_cerdas'));
            $li_active_style = $is_analisis_active ? 'background: rgba(139, 92, 246, 0.08); border-left: 3px solid #8b5cf6;' : '';
            $text_active_style = $is_analisis_active ? 'color: #a78bfa; font-weight: 700;' : '';
            ?>
            <li style="margin: 2px 0;">
                <a href="<?= site_url('status_desa/navigasi/analisis_cerdas') ?>" style="display: flex; align-items: center; <?= $li_active_style ?>">
                    <div class="sidebar-icon-box" style="flex-shrink: 0; display: flex; align-items: center; justify-content: center;"><i data-lucide="brain" style="width: 18px; color: #8b5cf6;"></i></div>
                    <span class="xosid-menu-label" style="<?= $text_active_style ?>">Analisis Cerdas</span>
                </a>
            </li>
            <?php endif; ?>

            <?php 
            if (!function_exists('render_xosid_menu')) {
                function render_xosid_menu($items, $is_submenu = false, $integrasi_level = 0) {
                    global $is_esensial;
                    foreach ($items as $mod):
                        $has_children = !empty($mod['childrens']);
                        $icon = xosid_get_icon($mod['ikon'] ?? '');
                        
                        if (!$is_submenu && in_array($mod['url'] ?? '', ['beranda', 'dashboard_neural'])) continue;
                        
                        $mod_id = $mod['id'] ?? 0;
                        $mod_name = strtolower($mod['modul'] ?? '');
                        $mod_url = strtolower($mod['url'] ?? '');
                        
                        $is_integrasi_root = ($mod_id == 352 || $mod_name === 'integrasi data' || $mod_name === 'satu data');
                        $is_anjungan_root = ($mod_id == 312 || $mod_name === 'anjungan' || $mod_name === 'kiosk' || $mod_url === 'anjungan');

                        $is_anjungan_item = ($is_anjungan_root || str_contains($mod_url, 'anjungan') || str_contains($mod_name, 'anjungan') || str_contains($mod_url, 'kiosk'));

                        $current_integrasi_level = $is_integrasi_root ? 0 : ($integrasi_level > 0 ? $integrasi_level : (
                            (str_contains($mod_url, 'dtks') || str_contains($mod_url, 'dtsen') || str_contains($mod_url, 'prodeskel') || str_contains($mod_url, 'ppid') || str_contains($mod_url, 'data_presisi')) ? 1 : 0
                        ));

                        $current_enterprise_level = ($current_integrasi_level > 0) ? $current_integrasi_level : ($is_anjungan_item ? 1 : 0);

                        $is_locked_feature = $is_esensial && ($current_enterprise_level > 0);
                        
                        $li_style = $is_submenu ? 'margin: 0;' : 'margin: 2px 0;';
                        $a_style = $is_submenu ? 'font-size: 12px; padding: 8px 10px; display: flex; align-items: center; justify-content: space-between; overflow: hidden;' : 'display: flex; align-items: center; justify-content: space-between; overflow: hidden;';
                        if ($is_locked_feature) {
                            $a_style .= ' opacity: 0.85; cursor: pointer;';
                        }
                        
                        $target_url = preg_replace('/\/clear$/', '', $mod['url'] ?? '');
                        $href = ($has_children && !$is_locked_feature) ? '#' : ($is_locked_feature ? 'javascript:void(0)' : site_url($target_url));
                        $onclick = $is_locked_feature ? 'onclick="showXOSIDEnterpriseLockModal(\''.addslashes($mod['modul']).'\'); return false;"' : '';
                        
                        $show_badge = ($is_locked_feature && ($current_integrasi_level === 1 || $is_anjungan_root || $mod_url === 'anjungan'));
                        ?>
                        <li class="<?= ($has_children && !$is_locked_feature) ? 'treeview' : ($has_children ? 'treeview' : '') ?>" style="<?= $li_style ?>">
                            <a href="<?= $href ?>" <?= $onclick ?> style="<?= $a_style ?>">
                                <div style="display: flex; align-items: center; gap: 8px; flex: 1; min-width: 0; overflow: hidden;">
                                    <div class="sidebar-icon-box" style="flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                        <?php if ($is_locked_feature): ?>
                                            <i data-lucide="lock" style="width: 12px; color: #f59e0b; opacity: 0.9;"></i>
                                        <?php elseif ($is_submenu && !$has_children): ?>
                                            <i data-lucide="minus" style="width: 8px; opacity: 0.5;"></i>
                                        <?php else: ?>
                                            <i data-lucide="<?= $icon ?>" style="width: <?= $is_submenu ? '14px' : '16px' ?>;"></i>
                                        <?php endif; ?>
                                    </div>
                                    <span class="xosid-menu-label" style="flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: <?= $show_badge ? '700' : '400' ?>; color: inherit;">
                                        <?= $mod['modul'] ?>
                                    </span>
                                </div>
                                
                                <div style="display: flex; align-items: center; gap: 6px; flex-shrink: 0; margin-left: 6px;">
                                    <?php if ($show_badge): ?>
                                        <span style="font-size: 7.5px; font-weight: 800; text-transform: uppercase; background: rgba(245, 158, 11, 0.15); color: #d97706; padding: 2px 6px; border-radius: 4px; border: 1px solid rgba(245, 158, 11, 0.3); white-space: nowrap; flex-shrink: 0; letter-spacing: 0.5px;">ENTERPRISE</span>
                                    <?php endif; ?>

                                    <?php if ($has_children): ?>
                                        <i data-lucide="chevron-right" class="pull-right arrow-icon" style="width: 14px; opacity: 0.5; transition: transform 0.3s ease; flex-shrink: 0;"></i>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <?php if ($has_children): ?>
                                <ul class="treeview-menu" style="background: <?= $is_esensial ? 'rgba(0, 0, 0, 0.15)' : 'rgba(0, 0, 0, 0.3)' ?>; padding: 5px 0 5px 12px; border-radius: 8px; margin: 2px 0; list-style: none;">
                                    <?php render_xosid_menu($mod['childrens'], true, ($is_integrasi_root || $is_anjungan_root ? 1 : ($current_enterprise_level > 0 ? $current_enterprise_level + 1 : 0))); ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                        <?php
                    endforeach;
                }
            }
            
            render_xosid_menu($menu_tree);
            ?>
        </ul>
    </section>
</aside>

<style>
/* XOSID SIDEBAR BASE STYLING */
<?php if ($is_esensial): ?>
#sidebar_collapse .main-sidebar,
.main-sidebar,
aside.main-sidebar,
.sidebar {
    background-color: #153E7E !important;
    background: #153E7E !important;
    box-shadow: none !important;
    border-right: 1px solid rgba(255, 255, 255, 0.12) !important;
    padding-top: 60px !important;
    transition: width 0.3s ease-in-out !important;
}

body .main-sidebar .sidebar-menu > li > a {
    padding: 12px 20px !important;
    display: flex !important;
    align-items: center !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    border-left: 4px solid transparent !important;
    background: transparent !important;
    color: rgba(255, 255, 255, 0.95) !important;
    height: auto !important;
}

body .main-sidebar .sidebar-menu li a .sidebar-icon-box {
    width: 42px !important;
    height: 42px !important;
    line-height: 40px !important;
    text-align: center !important;
    background: rgba(255, 255, 255, 0.15) !important;
    border: 1px solid rgba(255, 255, 255, 0.22) !important;
    border-radius: 12px !important;
    margin-right: 20px !important;
    color: #ffffff !important;
    transition: all 0.3s ease !important;
    font-size: 16px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

body .main-sidebar .sidebar-menu li a .sidebar-icon-box i,
body .main-sidebar .sidebar-menu li a .sidebar-icon-box svg {
    color: #ffffff !important;
    stroke: #ffffff !important;
    transition: all 0.3s ease !important;
}

body .main-sidebar .sidebar-menu > li.active > a,
body .main-sidebar .sidebar-menu > li:hover > a {
    background: rgba(255, 255, 255, 0.16) !important;
    color: white !important;
    border-left-color: #ffffff !important;
}

body .main-sidebar .sidebar-menu > li.active > a .sidebar-icon-box,
body .main-sidebar .sidebar-menu > li:hover > a .sidebar-icon-box {
    background: #ffffff !important;
    color: #153E7E !important;
    border-color: #ffffff !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
}

body .main-sidebar .sidebar-menu > li.active > a .sidebar-icon-box i,
body .main-sidebar .sidebar-menu > li.active > a .sidebar-icon-box svg,
body .main-sidebar .sidebar-menu > li:hover > a .sidebar-icon-box i,
body .main-sidebar .sidebar-menu > li:hover > a .sidebar-icon-box svg {
    color: #153E7E !important;
    stroke: #153E7E !important;
}

body .main-sidebar .sidebar-menu .treeview-menu {
    background: rgba(0, 0, 0, 0.15) !important;
}

body .main-sidebar .sidebar-menu li.header {
    color: rgba(255, 255, 255, 0.65) !important;
}
<?php else: ?>
#sidebar_collapse .main-sidebar {
    background-color: #060910 !important;
    box-shadow: 10px 0 30px rgba(0,0,0,0.5) !important;
    border-right: 1px solid rgba(255,255,255,0.02) !important;
    padding-top: 60px !important;
    transition: width 0.3s ease-in-out !important;
}

body .main-sidebar .sidebar-menu > li > a {
    padding: 12px 20px !important;
    display: flex !important;
    align-items: center !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    border-left: 4px solid transparent !important;
    background: transparent !important;
    color: #94a3b8 !important;
    height: auto !important;
}

body .main-sidebar .sidebar-menu li a .sidebar-icon-box {
    width: 42px !important;
    height: 42px !important;
    line-height: 40px !important;
    text-align: center !important;
    background: rgba(6, 182, 212, 0.05) !important;
    border: 1px solid rgba(6, 182, 212, 0.15) !important;
    border-radius: 12px !important;
    margin-right: 20px !important;
    color: #06b6d4 !important;
    transition: all 0.3s ease !important;
    font-size: 16px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

body .main-sidebar .sidebar-menu > li.active > a,
body .main-sidebar .sidebar-menu > li:hover > a {
    background: rgba(6, 182, 212, 0.08) !important;
    color: white !important;
    border-left-color: #06b6d4 !important;
}

body .main-sidebar .sidebar-menu > li.active > a .sidebar-icon-box,
body .main-sidebar .sidebar-menu > li:hover > a .sidebar-icon-box {
    background: #06b6d4 !important;
    color: white !important;
    box-shadow: 0 0 20px rgba(6, 182, 212, 0.5) !important;
    border-color: #06b6d4 !important;
}
<?php endif; ?>

body .main-sidebar .sidebar-menu > li > a span {
    font-family: 'Outfit', sans-serif !important;
    font-weight: 600 !important;
    letter-spacing: 1px !important;
    font-size: 13px !important;
    white-space: normal !important;
    text-transform: uppercase !important;
}

body .main-sidebar .sidebar-menu > li.active > a span {
    font-weight: 800 !important;
    color: white !important;
}

body .sidebar-menu li.header {
    text-transform: uppercase !important;
    color: #475569 !important;
    font-size: 10px !important;
    font-weight: 800 !important;
    letter-spacing: 2px !important;
    padding: 25px 25px 10px !important;
    background: transparent !important;
}

.mini-logo {
    display: none;
    width: 44px;
    height: 44px;
    background: #ffffff;
    border-radius: 12px;
    margin: 0 auto;
    align-items: center;
    justify-content: center;
    padding: 6px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* ==========================================================================
   COLLAPSED SIDEBAR SYSTEM (FLYOUT SUBMENUS ON HOVER)
   ========================================================================== */
body.sidebar-collapse .main-sidebar,
#sidebar_collapse.sidebar-collapse .main-sidebar {
    width: 76px !important;
    overflow: visible !important;
}

body.sidebar-collapse .main-sidebar .sidebar,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar {
    overflow: visible !important;
    padding-bottom: 60px !important;
}

body.sidebar-collapse .main-sidebar .xosid-village-panel,
#sidebar_collapse.sidebar-collapse .main-sidebar .xosid-village-panel {
    padding: 12px 0 10px !important;
}

body.sidebar-collapse .main-sidebar .xosid-village-panel .full-logo,
#sidebar_collapse.sidebar-collapse .main-sidebar .xosid-village-panel .full-logo,
body.sidebar-collapse .main-sidebar .xosid-menu-header,
#sidebar_collapse.sidebar-collapse .main-sidebar .xosid-menu-header {
    display: none !important;
}

body.sidebar-collapse .main-sidebar .xosid-village-panel .mini-logo,
#sidebar_collapse.sidebar-collapse .main-sidebar .xosid-village-panel .mini-logo {
    display: flex !important;
}

body.sidebar-collapse .main-sidebar .sidebar-menu,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu {
    padding: 0 8px !important;
    overflow: visible !important;
}

body.sidebar-collapse .main-sidebar .sidebar-menu > li,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu > li {
    position: relative !important;
    margin: 4px 0 !important;
}

body.sidebar-collapse .main-sidebar .sidebar-menu > li > a,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu > li > a {
    padding: 8px 0 !important;
    justify-content: center !important;
    width: 100% !important;
    border-left: 3px solid transparent !important;
}

body.sidebar-collapse .main-sidebar .sidebar-menu > li > a .sidebar-icon-box,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu > li > a .sidebar-icon-box {
    margin-right: 0 !important;
    width: 42px !important;
    height: 42px !important;
}

body.sidebar-collapse .main-sidebar .sidebar-menu > li > a > .xosid-menu-label,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu > li > a > .xosid-menu-label,
body.sidebar-collapse .main-sidebar .sidebar-menu > li > a > .arrow-icon,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu > li > a > .arrow-icon {
    display: none !important;
}

/* HOVER FLYOUT BADGE */
body.sidebar-collapse .main-sidebar .sidebar-menu > li:hover > a > .xosid-menu-label,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu > li:hover > a > .xosid-menu-label {
    display: block !important;
    position: absolute !important;
    top: 0 !important;
    left: 68px !important;
    width: 230px !important;
    padding: 12px 18px !important;
    background: <?= $is_esensial ? '#153E7E' : '#0f172a' ?> !important;
    color: #ffffff !important;
    font-weight: 800 !important;
    font-size: 13px !important;
    letter-spacing: 0.5px !important;
    border-radius: 0 10px 10px 0 !important;
    box-shadow: 10px 4px 20px rgba(0, 0, 0, 0.25) !important;
    border-left: 1px solid rgba(255, 255, 255, 0.15) !important;
    z-index: 1300 !important;
    white-space: nowrap !important;
}

body.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview:hover > a > .xosid-menu-label,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview:hover > a > .xosid-menu-label {
    border-radius: 0 10px 0 0 !important;
}

body.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview > .treeview-menu,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview > .treeview-menu {
    display: none !important;
}

body.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview:hover > .treeview-menu,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview:hover > .treeview-menu {
    display: block !important;
    position: absolute !important;
    top: 42px !important;
    left: 68px !important;
    width: 230px !important;
    background: <?= $is_esensial ? '#0d2750' : '#060910' ?> !important;
    border-radius: 0 0 10px 10px !important;
    padding: 8px 10px 10px 10px !important;
    box-shadow: 10px 10px 25px rgba(0, 0, 0, 0.3) !important;
    border-left: 1px solid rgba(255, 255, 255, 0.15) !important;
    border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
    z-index: 1299 !important;
}

body.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview:hover > .treeview-menu li,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview:hover > .treeview-menu li {
    margin: 2px 0 !important;
}

body.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview:hover > .treeview-menu li a,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview:hover > .treeview-menu li a {
    padding: 8px 12px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    color: rgba(255, 255, 255, 0.9) !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    border-radius: 6px !important;
    background: transparent !important;
    transition: all 0.2s ease !important;
}

body.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview:hover > .treeview-menu li a:hover,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview:hover > .treeview-menu li a:hover {
    background: rgba(255, 255, 255, 0.15) !important;
    color: #38bdf8 !important;
}

body.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview:hover > .treeview-menu li a .sidebar-icon-box,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview:hover > .treeview-menu li a .sidebar-icon-box {
    width: 22px !important;
    height: 22px !important;
    margin-right: 8px !important;
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    display: inline-flex !important;
}

body.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview:hover > .treeview-menu li a .xosid-menu-label,
#sidebar_collapse.sidebar-collapse .main-sidebar .sidebar-menu > li.treeview:hover > .treeview-menu li a .xosid-menu-label {
    display: inline-block !important;
    position: static !important;
    width: auto !important;
    padding: 0 !important;
    background: transparent !important;
    box-shadow: none !important;
    color: inherit !important;
    font-size: 12px !important;
    font-weight: 600 !important;
}

/* BRIGHT WHITE SUBMENU FONTS EXCLUSIVELY */
body .main-sidebar .sidebar-menu .treeview-menu li a,
body .main-sidebar .sidebar-menu .treeview-menu li a span,
body .main-sidebar .sidebar-menu .treeview-menu li a .xosid-menu-label {
    color: rgba(255, 255, 255, 0.9) !important;
    opacity: 1 !important;
    font-weight: 600 !important;
    transition: all 0.2s ease !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
}

body .main-sidebar .sidebar-menu .treeview-menu li a:hover,
body .main-sidebar .sidebar-menu .treeview-menu li a:hover span,
body .main-sidebar .sidebar-menu .treeview-menu li a:hover .xosid-menu-label {
    color: <?= $is_esensial ? '#ffffff' : '#06b6d4' ?> !important;
    opacity: 1 !important;
    text-shadow: <?= $is_esensial ? '0 0 10px rgba(255,255,255,0.5)' : '0 0 10px rgba(6,182,212,0.5)' ?>;
}

.xosid-menu-label {
    text-transform: uppercase !important;
}
</style>