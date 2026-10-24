<?php
// XOSID AJAX BRIDGE FOR WILAYAH (HYBRID CONTROLLER BYPASS)
// This file serves as a direct DB bridge for DataTables to avoid CI routing blocks on AJAX.

require_once 'xosid_ajax_db.php';

$level = $_GET['level'] ?? 'dusun';
$parent = $_GET['parent'] ?? '0';
$draw = $_GET['draw'] ?? 1;

$tenant_paket = 'esensial';
if (file_exists(__DIR__ . '/xosid_tenant_helper.php')) {
    require_once __DIR__ . '/xosid_tenant_helper.php';
    if (function_exists('xosid_get_tenant_package')) {
        $tenant_paket = xosid_get_tenant_package();
    }
}
$is_esensial = ($tenant_paket === 'esensial');

// Base query for wilayah cluster based on hierarchy level
if ($level == 'dusun') {
    $sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt IN ('0', '-', '') AND rw IN ('0', '-', '') ORDER BY urut ASC, id ASC";
} elseif ($level == 'rw') {
    $sql = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = (SELECT dusun FROM tweb_wil_clusterdesa WHERE id = '$parent') AND rt IN ('0', '-', '') ORDER BY urut ASC, id ASC";
} elseif ($level == 'rt') {
    $sql = "SELECT * FROM tweb_wil_clusterdesa WHERE dusun = (SELECT dusun FROM tweb_wil_clusterdesa WHERE id = '$parent') AND rw = (SELECT rw FROM tweb_wil_clusterdesa WHERE id = '$parent') AND rt NOT IN ('0', '') ORDER BY urut ASC, id ASC";
} else {
    $sql = "SELECT * FROM tweb_wil_clusterdesa WHERE rt IN ('0', '-', '') AND rw IN ('0', '-', '') ORDER BY urut ASC, id ASC";
}

$res = $conn->query($sql);
$results = [];

$seen_names = [];

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        // --- 1. HIERARCHICAL FILTERS & DE-DUPLICATION ---
        if ($level == 'dusun') {
            $dusun_name = trim($row['dusun']);
            if (empty($dusun_name)) {
                continue;
            }
            if (in_array($dusun_name, $seen_names)) {
                continue;
            }
            $seen_names[] = $dusun_name;
        } elseif ($level == 'rw') {
            if (intval($row['id']) == intval($parent)) {
                continue; // Skip the parent Dusun cluster row itself
            }
            if ($row['rw'] == '0') {
                continue; // Skip Dusun level rows
            }
            if ($row['rw'] == '-') {
                // Only keep '-' RW if it actually contains RTs
                $check_q = $conn->query("SELECT COUNT(*) as total FROM tweb_wil_clusterdesa WHERE dusun = '".$conn->real_escape_string($row['dusun'])."' AND rw = '-' AND rt NOT IN ('0', '')");
                $has_rts = ($check_q) ? ($check_q->fetch_assoc()['total'] > 0) : false;
                if (!$has_rts) {
                    continue;
                }
            }
            $rw_name = trim($row['rw']);
            if (in_array($rw_name, $seen_names)) {
                continue;
            }
            $seen_names[] = $rw_name;
        } elseif ($level == 'rt') {
            $rt_name = trim($row['rt']);
            if (in_array($rt_name, $seen_names)) {
                continue;
            }
            $seen_names[] = $rt_name;
        }

        // --- 2. FETCH KEPALA WILAYAH & NIK ---
        $kepala_nama = '-';
        $kepala_nik = '-';
        if (!empty($row['id_kepala'])) {
            $k_sql = "SELECT nama, nik FROM tweb_penduduk WHERE id = " . intval($row['id_kepala']);
            $k_q = $conn->query($k_sql);
            if ($k_q && $k_r = $k_q->fetch_assoc()) {
                $kepala_nama = $k_r['nama'];
                $kepala_nik = $k_r['nik'] ?? '-';
            }
        }

        // --- 3. COUNT SUB-TERRITORIES (RW & RT) ---
        $res_rw = 0;
        $res_rt = 0;
        if ($level == 'dusun') {
            $rw_count_q = $conn->query("SELECT DISTINCT rw FROM tweb_wil_clusterdesa WHERE dusun = '".$conn->real_escape_string($row['dusun'])."' AND rt IN ('0', '-', '') AND rw != '0'");
            if ($rw_count_q) {
                while ($rw_row = $rw_count_q->fetch_assoc()) {
                    $rw_val = $rw_row['rw'];
                    if ($rw_val == '-') {
                        $check_q = $conn->query("SELECT COUNT(*) as total FROM tweb_wil_clusterdesa WHERE dusun = '".$conn->real_escape_string($row['dusun'])."' AND rw = '-' AND rt NOT IN ('0', '')");
                        if ($check_q && $check_q->fetch_assoc()['total'] > 0) {
                            $res_rw++;
                        }
                    } else {
                        $res_rw++;
                    }
                }
            }
            
            $rt_q = $conn->query("SELECT COUNT(DISTINCT rt) as total FROM tweb_wil_clusterdesa WHERE dusun = '".$conn->real_escape_string($row['dusun'])."' AND rt NOT IN ('0', '')");
            $res_rt = ($rt_q) ? intval($rt_q->fetch_assoc()['total']) : 0;
        } elseif ($level == 'rw') {
            $rt_q = $conn->query("SELECT COUNT(DISTINCT rt) as total FROM tweb_wil_clusterdesa WHERE dusun = '".$conn->real_escape_string($row['dusun'])."' AND rw = '".$conn->real_escape_string($row['rw'])."' AND rt NOT IN ('0', '')");
            $res_rt = ($rt_q) ? intval($rt_q->fetch_assoc()['total']) : 0;
        }

        // --- 4. DYNAMIC DEMOGRAPHICS CALCULATION (COMBINED QUERY) ---
        $cluster_ids = [];
        if ($level == 'dusun') {
            $c_q = $conn->query("SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '".$conn->real_escape_string($row['dusun'])."'");
            while ($c_r = $c_q->fetch_assoc()) {
                $cluster_ids[] = intval($c_r['id']);
            }
        } elseif ($level == 'rw') {
            $c_q = $conn->query("SELECT id FROM tweb_wil_clusterdesa WHERE dusun = '".$conn->real_escape_string($row['dusun'])."' AND rw = '".$conn->real_escape_string($row['rw'])."'");
            while ($c_r = $c_q->fetch_assoc()) {
                $cluster_ids[] = intval($c_r['id']);
            }
        } else {
            $cluster_ids[] = intval($row['id']);
        }

        if (empty($cluster_ids)) {
            $cluster_ids = [0];
        }
        $cluster_ids_str = implode(',', $cluster_ids);

        // keluarga_aktif_count
        $fam_q = $conn->query("SELECT COUNT(*) as total FROM tweb_keluarga WHERE id_cluster IN ($cluster_ids_str)");
        $keluarga_aktif_count = $fam_q ? intval($fam_q->fetch_assoc()['total']) : 0;

        // Combined penduduk_count, pria_count, and wanita_count in single query
        $penduduk_count = 0;
        $penduduk_pria_count = 0;
        $penduduk_wanita_count = 0;
        $pop_q = $conn->query("SELECT COUNT(*) as total, SUM(CASE WHEN sex = 1 THEN 1 ELSE 0 END) as pria, SUM(CASE WHEN sex = 2 THEN 1 ELSE 0 END) as wanita FROM tweb_penduduk WHERE id_cluster IN ($cluster_ids_str) AND status_dasar = 1");
        if ($pop_q && $pop_r = $pop_q->fetch_assoc()) {
            $penduduk_count = intval($pop_r['total'] ?? 0);
            $penduduk_pria_count = intval($pop_r['pria'] ?? 0);
            $penduduk_wanita_count = intval($pop_r['wanita'] ?? 0);
        }

        // --- 5. ACTION BUTTON GENERATION ---
        $next_level = ($level == 'dusun') ? 'rw' : (($level == 'rw') ? 'rt' : '');
        $url_rincian = "/index.php/wilayah/index?parent=".$row['id']."&level=".$next_level;
        $url_ubah = ($level == 'dusun') ? "/index.php/wilayah/form_dusun/".$row['id'] : "/index.php/wilayah/form_".$level."/".$parent."/".$row['id'];
        
        $url_kantor = ($level == 'dusun') ? "/index.php/wilayah/ajax_kantor_dusun_maps/".$row['id'] : "/index.php/wilayah/ajax_kantor_".$level."_maps/".$row['id']."/".$parent;
        $url_peta = ($level == 'dusun') ? "/index.php/wilayah/ajax_wilayah_dusun_maps/".$row['id'] : "/index.php/wilayah/ajax_wilayah_".$level."_maps/".$row['id']."/".$parent;
        $url_hapus = ($level == 'dusun') ? "/index.php/wilayah/delete/dusun/".$row['id']."/0" : "/index.php/wilayah/delete/".$level."/".$row['id']."/".$parent;
        $label_level = ($level == 'dusun') ? 'Dusun' : strtoupper($level);

        if ($is_esensial) {
            $peta_dropdown = '
            <div class="dropdown" style="display: inline-block;">
                <button class="btn-action-neural dropdown-toggle" type="button" data-toggle="dropdown" title="Peta" style="color: #047857 !important; padding: 0 !important; width: 34px !important; height: 34px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 8px !important; background: #ecfdf5 !important; border: 1px solid #a7f3d0 !important; margin: 0 !important;">
                    <i data-lucide="map" style="width:16px; pointer-events: none;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" style="top: 100% !important; margin-top: 10px !important; background: #ffffff !important; border: 1px solid #cbd5e1; border-radius: 12px; padding: 8px; min-width: 220px; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15); z-index: 999999 !important;">
                    <li>
                        <a href="'.$url_kantor.'" class="dropdown-item" style="color: #0f172a; padding: 10px 14px; border-radius: 8px; display: flex; align-items: center; gap: 10px; font-size: 12px; font-weight: 700; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background=\'#eff6ff\'; this.style.color=\'#153E7E\'" onmouseout="this.style.background=\'transparent\'; this.style.color=\'#0f172a\'">
                            <i data-lucide="map-pin" style="width:16px; height:16px; color: #153E7E;"></i> LOKASI '.strtoupper($label_level).'
                        </a>
                    </li>
                    <li style="margin-top: 5px; border-top: 1px solid #e2e8f0; padding-top: 5px;">
                        <a href="'.$url_peta.'" class="dropdown-item" style="color: #0f172a; padding: 10px 14px; border-radius: 8px; display: flex; align-items: center; gap: 10px; font-size: 12px; font-weight: 700; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background=\'#eff6ff\'; this.style.color=\'#153E7E\'" onmouseout="this.style.background=\'transparent\'; this.style.color=\'#0f172a\'">
                            <i data-lucide="map" style="width:16px; height:16px; color: #153E7E;"></i> PETA WILAYAH '.strtoupper($label_level).'
                        </a>
                    </li>
                </ul>
            </div>';

            $action_html = '<div style="display: flex !important; justify-content: flex-start !important; align-items: center !important; gap: 6px !important; width: fit-content !important; padding: 4px !important; background: #ffffff !important; border-radius: 12px !important; border: 1px solid #cbd5e1 !important; box-shadow: 0 2px 6px rgba(15, 23, 42, 0.06);">
                            '.(($level !== 'rt') ? '<a href="'.$url_rincian.'" class="btn-action-neural" title="Rincian" style="color: #1d4ed8 !important; padding: 0 !important; width: 34px !important; height: 34px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 8px !important; background: #eff6ff !important; border: 1px solid #bfdbfe !important; margin: 0 !important;"><i data-lucide="list" style="width:16px; pointer-events: none;"></i></a>' : '').'
                            <a href="'.$url_ubah.'" class="btn-action-neural" title="Ubah" style="color: #b45309 !important; padding: 0 !important; width: 34px !important; height: 34px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 8px !important; background: #fef3c7 !important; border: 1px solid #fde68a !important; margin: 0 !important;"><i data-lucide="pencil" style="width:16px; pointer-events: none;"></i></a>
                            '.$peta_dropdown.'
                            <a href="#" data-href="'.$url_hapus.'" data-toggle="modal" data-target="#confirm-delete" class="btn-action-neural" title="Hapus" style="color: #dc2626 !important; padding: 0 !important; width: 34px !important; height: 34px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 8px !important; background: #fee2e2 !important; border: 1px solid #fecaca !important; margin: 0 !important;">
                                <i data-lucide="trash-2" style="width:16px; pointer-events: none;"></i>
                            </a>
                          </div>';
            $drag_html = '<div style="cursor: move; color: #94a3b8;"><i data-lucide="grip-vertical" style="width: 14px; pointer-events: none;"></i></div>';
        } else {
            $peta_dropdown = '
            <div class="dropdown" style="display: inline-block;">
                <button class="btn-action-neural dropdown-toggle" type="button" data-toggle="dropdown" title="Peta" style="color: #34d399 !important; padding: 0 !important; width: 34px !important; height: 34px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 8px !important; background: rgba(16, 185, 129, 0.2) !important; border: 1px solid rgba(16, 185, 129, 0.3) !important; margin: 0 !important;">
                    <i data-lucide="map" style="width:16px; pointer-events: none;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" style="top: 100% !important; margin-top: 10px !important; background: rgba(15, 23, 42, 0.95) !important; backdrop-filter: blur(20px); border: 1px solid rgba(16, 185, 129, 0.4); border-radius: 12px; padding: 8px; min-width: 220px; box-shadow: 0 20px 50px rgba(0,0,0,0.6); z-index: 999999 !important;">
                    <li>
                        <a href="'.$url_kantor.'" class="dropdown-item" style="color: #f1f5f9; padding: 12px 15px; border-radius: 8px; display: flex; align-items: center; gap: 12px; font-size: 12px; font-weight: 700; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background=\'rgba(16, 185, 129, 0.15)\'; this.style.color=\'#34d399\'" onmouseout="this.style.background=\'transparent\'; this.style.color=\'#f1f5f9\'">
                            <i data-lucide="map-pin" style="width:16px; height:16px; color: #34d399;"></i> LOKASI '.strtoupper($label_level).'
                        </a>
                    </li>
                    <li style="margin-top: 5px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 5px;">
                        <a href="'.$url_peta.'" class="dropdown-item" style="color: #f1f5f9; padding: 12px 15px; border-radius: 8px; display: flex; align-items: center; gap: 12px; font-size: 12px; font-weight: 700; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background=\'rgba(16, 185, 129, 0.15)\'; this.style.color=\'#34d399\'" onmouseout="this.style.background=\'transparent\'; this.style.color=\'#f1f5f9\'">
                            <i data-lucide="map" style="width:16px; height:16px; color: #34d399;"></i> PETA WILAYAH '.strtoupper($label_level).'
                        </a>
                    </li>
                </ul>
            </div>';

            $action_html = '<div style="display: flex !important; justify-content: flex-start !important; align-items: center !important; gap: 6px !important; width: fit-content !important; padding: 4px !important; background: rgba(15, 23, 42, 0.8) !important; border-radius: 12px !important; border: 1px solid rgba(255,255,255,0.1) !important; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                            '.(($level !== 'rt') ? '<a href="'.$url_rincian.'" class="btn-action-neural" title="Rincian" style="color: #22d3ee !important; padding: 0 !important; width: 34px !important; height: 34px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 8px !important; background: rgba(6, 182, 212, 0.2) !important; border: 1px solid rgba(6, 182, 212, 0.3) !important; margin: 0 !important;"><i data-lucide="list" style="width:16px; pointer-events: none;"></i></a>' : '').'
                            <a href="'.$url_ubah.'" class="btn-action-neural" title="Ubah" style="color: #fbbf24 !important; padding: 0 !important; width: 34px !important; height: 34px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 8px !important; background: rgba(251, 191, 36, 0.2) !important; border: 1px solid rgba(251, 191, 36, 0.3) !important; margin: 0 !important;"><i data-lucide="pencil" style="width:16px; pointer-events: none;"></i></a>
                            '.$peta_dropdown.'
                            <a href="#" data-href="'.$url_hapus.'" data-toggle="modal" data-target="#confirm-delete" class="btn-action-neural" title="Hapus" style="color: #f87171 !important; padding: 0 !important; width: 34px !important; height: 34px !important; display: flex !important; align-items: center !important; justify-content: center !important; border-radius: 8px !important; background: rgba(244, 63, 94, 0.2) !important; border: 1px solid rgba(244, 63, 94, 0.3) !important; margin: 0 !important;">
                                <i data-lucide="trash-2" style="width:16px; pointer-events: none;"></i>
                            </a>
                          </div>';
            $drag_html = '<div style="cursor: move; color: rgba(255,255,255,0.2);"><i data-lucide="grip-vertical" style="width: 14px; pointer-events: none;"></i></div>';
        }

        $results[] = [
            'drag-handle' => $drag_html,
            'DT_RowIndex' => count($results) + 1,
            'aksi' => $action_html,
            $level => $row[$level] ?? '-',
            'kepala' => $kepala_nama,
            'nik_kepala' => $kepala_nik,
            'rws_count' => (int)$res_rw,
            'rts_count' => (int)$res_rt,
            'keluarga_aktif_count' => $keluarga_aktif_count,
            'penduduk_count' => $penduduk_count,
            'penduduk_pria_count' => $penduduk_pria_count,
            'penduduk_wanita_count' => $penduduk_wanita_count,
            'id' => (int)$row['id']
        ];
    }
}

if (empty($results)) {
    $results[] = [
        'drag-handle' => '<i class="fa fa-bars"></i>', 'DT_RowIndex' => 1, 'aksi' => '-',
        $level => 'Belum ada data wilayah', 'kepala' => '-', 'nik_kepala' => '-',
        'rws_count' => 0, 'rts_count' => 0, 'keluarga_aktif_count' => 0, 'penduduk_count' => 0,
        'penduduk_pria_count' => 0, 'penduduk_wanita_count' => 0, 'id' => 1
    ];
}

header('Content-Type: application/json');
echo json_encode(['draw' => intval($draw), 'recordsTotal' => count($results), 'recordsFiltered' => count($results), 'data' => $results]);
$conn->close();
?>
