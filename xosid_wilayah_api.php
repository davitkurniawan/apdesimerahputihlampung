<?php
header('Content-Type: application/json');
require_once __DIR__ . '/xosid_ajax_db.php'; // Reuse XOSID DB connection

$req_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$clean_req_path = str_replace('/index.php', '', $req_path);
$method = $_SERVER['REQUEST_METHOD'];

/**
 * Get active xoSID registered tenants map [kode_desa => true]
 */
function getActiveXosidTenants($conn) {
    $active = [];
    
    // 1. From master DB
    $res = $conn->query("SELECT kode_desa, nama_desa FROM `xosid`.xosid_registrasi_tenant WHERE status = 'approved'");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $clean_kode = preg_replace('/[^0-9]/', '', $row['kode_desa']);
            if (!empty($clean_kode)) {
                $active[$clean_kode] = true;
            }
            $clean_nama = strtolower(trim(preg_replace('/\s*\([0-9]+\)/', '', $row['nama_desa'])));
            if (!empty($clean_nama)) {
                $active['name_' . $clean_nama] = true;
            }
        }
    }

    // 2. From tenant DBs
    $tenant_res = $conn->query("SELECT schema_name, SCHEMA_NAME FROM information_schema.schemata WHERE schema_name LIKE 'xosid\_tenant\_%'");
    if ($tenant_res) {
        while ($t_row = $tenant_res->fetch_assoc()) {
            $db = $t_row['SCHEMA_NAME'] ?? $t_row['schema_name'] ?? '';
            if (empty($db)) continue;
            $db_esc = $conn->real_escape_string($db);
            $sub_res = @$conn->query("SELECT kode_desa, nama_desa FROM `{$db_esc}`.xosid_registrasi_tenant WHERE status = 'approved'");
            if ($sub_res) {
                while ($row = $sub_res->fetch_assoc()) {
                    $clean_kode = preg_replace('/[^0-9]/', '', $row['kode_desa']);
                    if (!empty($clean_kode)) {
                        $active[$clean_kode] = true;
                    }
                    $clean_nama = strtolower(trim(preg_replace('/\s*\([0-9]+\)/', '', $row['nama_desa'])));
                    if (!empty($clean_nama)) {
                        $active['name_' . $clean_nama] = true;
                    }
                }
            }
        }
    }

    return $active;
}

try {
    $conn->select_db('xosid');

    // Endpoint 1: /api/wilayah/caridesa?q=...
    if (stripos($clean_req_path, '/api/wilayah/caridesa') === 0 && $method === 'GET') {
        $q = isset($_GET['q']) ? $conn->real_escape_string(trim($_GET['q'])) : '';

        $active_tenants = getActiveXosidTenants($conn);

        if (empty($q)) {
            $sql = "SELECT kode_desa, nama_desa, nama_kec, nama_kab, provinsi 
                    FROM ref_wilayah_lampung 
                    ORDER BY nama_desa ASC 
                    LIMIT 50";
        } else {
            $sql = "SELECT kode_desa, nama_desa, nama_kec, nama_kab, provinsi 
                    FROM ref_wilayah_lampung 
                    WHERE nama_desa LIKE '%{$q}%' 
                       OR nama_kec LIKE '%{$q}%' 
                       OR nama_kab LIKE '%{$q}%'
                    ORDER BY 
                       CASE WHEN nama_desa LIKE '{$q}%' THEN 1 ELSE 2 END,
                       nama_desa ASC 
                    LIMIT 50";
        }

        $res = $conn->query($sql);
        $results = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $clean_kode = preg_replace('/[^0-9]/', '', $row['kode_desa']);
                $clean_nama = strtolower(trim($row['nama_desa']));
                
                $is_xosid = isset($active_tenants[$clean_kode]) || isset($active_tenants['name_' . $clean_nama]);
                $badge = $is_xosid ? ' [✅ xoSID Aktif]' : ' [📋 Belum Terdaftar xoSID]';

                $results[] = [
                    'id'        => $row['kode_desa'],
                    'text'      => "{$row['nama_desa']} - Kec. {$row['nama_kec']} - {$row['nama_kab']}{$badge}",
                    'nama_desa' => $row['nama_desa'],
                    'nama_kec'  => $row['nama_kec'],
                    'nama_kab'  => $row['nama_kab'],
                    'is_xosid'  => $is_xosid ? 1 : 0
                ];
            }
        }

        echo json_encode([
            'results'    => $results,
            'pagination' => ['more' => false]
        ]);
        exit;
    }

    // Endpoint 2: /api/wilayah/ambildesa?id_desa=...
    if (stripos($clean_req_path, '/api/wilayah/ambildesa') === 0 && $method === 'GET') {
        $id_desa = isset($_GET['id_desa']) ? $conn->real_escape_string(trim($_GET['id_desa'])) : '';

        if (empty($id_desa)) {
            echo json_encode(['KODE_WILAYAH' => []]);
            exit;
        }

        $active_tenants = getActiveXosidTenants($conn);

        $sql = "SELECT * FROM ref_wilayah_lampung WHERE kode_desa = '{$id_desa}' LIMIT 1";
        $res = $conn->query($sql);

        $wilayah = [];
        if ($res && $row = $res->fetch_assoc()) {
            $clean_kode = preg_replace('/[^0-9]/', '', $row['kode_desa']);
            $clean_nama = strtolower(trim($row['nama_desa']));
            $is_xosid   = isset($active_tenants[$clean_kode]) || isset($active_tenants['name_' . $clean_nama]);

            $wilayah[] = [
                'nama_desa'     => $row['nama_desa'],
                'kode_desa'     => $row['kode_desa'],
                'nama_kec'      => $row['nama_kec'],
                'kode_kec'      => $row['kode_kec'],
                'nama_kab'      => $row['nama_kab'],
                'kode_kab'      => $row['kode_kab'],
                'nama_prov'     => $row['provinsi'],
                'kode_prov'     => '18',
                'is_xosid'      => $is_xosid ? 1 : 0,
                'kode_desa_bps' => $row['kode_desa']
            ];
        }

        echo json_encode(['KODE_WILAYAH' => $wilayah]);
        exit;
    }

    // Not Found
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Endpoint Not Found']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
