<?php
header('Content-Type: application/json');
require_once __DIR__ . '/xosid_ajax_db.php';

$req_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$clean_req_path = str_replace('/index.php', '', $req_path);
$method = $_SERVER['REQUEST_METHOD'];

try {
    $conn->select_db('xosid');

    // Endpoint: /api/wilayah/caridesa
    if (stripos($clean_req_path, '/api/wilayah/caridesa') === 0 && $method === 'GET') {
        $q = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
        
        if (empty($q)) {
            $sql = "SELECT kode_desa AS id, CONCAT(nama_desa, ' - ', kecamatan, ' - ', kabupaten, ' - ', provinsi) AS text FROM xosid_registrasi_tenant WHERE status = 'approved' LIMIT 50";
        } else {
            $sql = "SELECT kode_desa AS id, CONCAT(nama_desa, ' - ', kecamatan, ' - ', kabupaten, ' - ', provinsi) AS text FROM xosid_registrasi_tenant WHERE status = 'approved' AND nama_desa LIKE '%$q%' LIMIT 50";
        }
        
        $res = $conn->query($sql);
        $results = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $results[] = $row;
            }
        }
        
        echo json_encode([
            'results' => $results,
            'pagination' => ['more' => false]
        ]);
        exit;
    }

    // Endpoint: /api/wilayah/ambildesa
    if (stripos($clean_req_path, '/api/wilayah/ambildesa') === 0 && $method === 'GET') {
        $id_desa = isset($_GET['id_desa']) ? $conn->real_escape_string($_GET['id_desa']) : '';
        
        if (empty($id_desa)) {
            echo json_encode(['KODE_WILAYAH' => []]);
            exit;
        }
        
        // 1. Cek di database Netmon
        $sql = "SELECT * FROM xosid_netmon_wilayah WHERE kode_desa = '$id_desa' LIMIT 1";
        $res = $conn->query($sql);
        
        $wilayah = [];
        if ($res && $row = $res->fetch_assoc()) {
            // Sudah ada di Netmon DB
            $wilayah[] = [
                'nama_desa' => $row['nama_desa'],
                'kode_desa' => $row['kode_desa'],
                'nama_kec' => $row['nama_kecamatan'],
                'kode_kec' => $row['kode_kecamatan'],
                'nama_kab' => $row['nama_kabupaten'],
                'kode_kab' => $row['kode_kabupaten'],
                'nama_prov' => $row['nama_provinsi'],
                'kode_prov' => $row['kode_provinsi'],
                'kode_desa_bps' => $row['kode_desa_bps']
            ];
        } else {
            // 2. Belum ada di Netmon DB, scrape dari netmon.xosid.id
            $pantau_url = 'https://netmon.xosid.id/api/wilayah/ambildesa?id_desa=' . $id_desa;
            $ctx = stream_context_create(['http' => ['timeout' => 5]]);
            $pantau_data = @file_get_contents($pantau_url, false, $ctx);
            
            if ($pantau_data) {
                $p_json = json_decode($pantau_data, true);
                if (isset($p_json['KODE_WILAYAH'][0])) {
                    $pw = $p_json['KODE_WILAYAH'][0];
                    $bps_code = '';
                    if (!empty($pw['kode_desa_bps'])) {
                        $bps_code = $pw['kode_desa_bps'];
                    } elseif (!empty($pw['bps_kemendagri_desa']['kode_desa_bps'])) {
                        $bps_code = $pw['bps_kemendagri_desa']['kode_desa_bps'];
                    } elseif (!empty($pw['bps_kemendagri_desa']['kode_bps'])) {
                        $bps_code = $pw['bps_kemendagri_desa']['kode_bps'];
                    }
                    
                    $nama_desa = $conn->real_escape_string($pw['nama_desa'] ?? '');
                    $nama_kec = $conn->real_escape_string($pw['nama_kec'] ?? '');
                    $kode_kec = $conn->real_escape_string($pw['kode_kec'] ?? '');
                    $nama_kab = $conn->real_escape_string($pw['nama_kab'] ?? '');
                    $kode_kab = $conn->real_escape_string($pw['kode_kab'] ?? '');
                    $nama_prov = $conn->real_escape_string($pw['nama_prov'] ?? '');
                    $kode_prov = $conn->real_escape_string($pw['kode_prov'] ?? '');
                    $bps_code_safe = $conn->real_escape_string($bps_code);
                    
                    // Simpan ke Netmon DB
                    $insert_sql = "INSERT INTO xosid_netmon_wilayah (kode_desa, kode_desa_bps, nama_desa, kode_kecamatan, nama_kecamatan, kode_kabupaten, nama_kabupaten, kode_provinsi, nama_provinsi) 
                                   VALUES ('$id_desa', '$bps_code_safe', '$nama_desa', '$kode_kec', '$nama_kec', '$kode_kab', '$nama_kab', '$kode_prov', '$nama_prov')";
                    $conn->query($insert_sql);
                    
                    $wilayah[] = [
                        'nama_desa' => $pw['nama_desa'] ?? '',
                        'kode_desa' => $id_desa,
                        'nama_kec' => $pw['nama_kec'] ?? '',
                        'kode_kec' => $pw['kode_kec'] ?? '',
                        'nama_kab' => $pw['nama_kab'] ?? '',
                        'kode_kab' => $pw['kode_kab'] ?? '',
                        'nama_prov' => $pw['nama_prov'] ?? '',
                        'kode_prov' => $pw['kode_prov'] ?? '',
                        'kode_desa_bps' => $bps_code
                    ];
                }
            }
        }
        
        echo json_encode(['KODE_WILAYAH' => $wilayah]);
        exit;
    }

    // Not Found
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Netmon Endpoint Not Found']);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
