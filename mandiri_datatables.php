<?php
require_once '/var/www/html/vendor/autoload.php';

// Verification of active session (admin/staff only)
$sess_id = '';
if (isset($_COOKIE['ci_session']) && file_exists('/tmp/sess_' . $_COOKIE['ci_session'])) {
    $sess_id = $_COOKIE['ci_session'];
} elseif (isset($_COOKIE['PHPSESSID']) && file_exists('/tmp/sess_' . $_COOKIE['PHPSESSID'])) {
    $sess_id = $_COOKIE['PHPSESSID'];
}

if (!$sess_id) {
    die(json_encode(['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [], 'status' => true, 'error' => 'no valid session file']));
}
$sess_file = '/tmp/sess_' . $sess_id;
$content = file_get_contents($sess_file);
if (strpos($content, 'logged_in') === false && strpos($content, 'admin') === false) {
    die(json_encode(['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [], 'status' => true]));
}

// Database Connection
$host = 'xosid_neural_db';
$user = 'root';
$pass = 'password';
$db = 'xosid';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [], 'status' => true]));
}

// Parse Parameters
$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
$draw = isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : 1;
$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
$length = isset($_REQUEST['length']) ? intval($_REQUEST['length']) : 10;
$search = isset($_REQUEST['search']['value']) ? $conn->real_escape_string($_REQUEST['search']['value']) : '';

// Build Where Clause
$where = "WHERE 1=1";
if ($status === '1') {
    $where .= " AND m.aktif = 1";
} elseif ($status === '2') {
    $where .= " AND (m.aktif = 0 OR m.aktif IS NULL)";
}

if ($search !== '') {
    $where .= " AND (p.nik LIKE '%$search%' OR p.nama LIKE '%$search%')";
}

// Count Total Records (Filtered)
$count_sql = "SELECT COUNT(*) as cnt FROM tweb_penduduk_mandiri m LEFT JOIN tweb_penduduk p ON m.id_pend = p.id $where";
$res = $conn->query($count_sql);
$total = $res ? $res->fetch_assoc()['cnt'] : 0;

// Order By Mapping
$orderColumnIndex = isset($_REQUEST['order'][0]['column']) ? intval($_REQUEST['order'][0]['column']) : 4;
$orderDir = isset($_REQUEST['order'][0]['dir']) && strtolower($_REQUEST['order'][0]['dir']) === 'asc' ? 'ASC' : 'DESC';

$columnsMap = [
    2 => 'p.nik',
    3 => 'p.nama',
    4 => 'm.tanggal_buat',
    5 => 'm.last_login',
    6 => 'm.aktif'
];
$orderBy = $columnsMap[$orderColumnIndex] ?? 'm.tanggal_buat';

// Fetch Data
$sql = "SELECT m.*, p.nik as p_nik, p.nama as p_nama, p.telepon as p_telepon 
        FROM tweb_penduduk_mandiri m 
        LEFT JOIN tweb_penduduk p ON m.id_pend = p.id 
        $where 
        ORDER BY $orderBy $orderDir 
        LIMIT $start, $length";
$res = $conn->query($sql);

$data = [];
$rowIndex = $start + 1;

$base_url = "/index.php/";

while ($row = $res->fetch_assoc()) {
    $id_pend = $row['id_pend'];
    $nama = htmlspecialchars($row['p_nama'] ?? '', ENT_QUOTES, 'UTF-8');
    
    $delete_url = $base_url . "mandiri/delete/" . $id_pend;
    $pin_url = $base_url . "mandiri/ajax_pin/" . $id_pend;
    $hp_url = $base_url . "mandiri/ajax_hp/" . $id_pend;
    
    // Construct Action Dropdown Menu
    $aksi = '<div class="btn-group">
                <button class="btn btn-xs btn-flat btn-primary" dropdown-toggle="" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-bars"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a href="' . $pin_url . '" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ganti PIN Warga">
                            <i class="fa fa-key"></i> Ganti PIN Baru
                        </a>
                    </li>
                    <li>
                        <a href="' . $hp_url . '" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Hubungkan No. HP Warga">
                            <i class="fa fa-phone"></i> Hubungkan HP
                        </a>
                    </li>
                    <li>
                        <a href="#" data-href="' . $delete_url . '" class="btn-delete" data-toggle="modal" data-target="#confirm-delete" data-title="Hapus Akun Layanan Mandiri">
                            <i class="fa fa-trash-o"></i> Hapus Akun
                        </a>
                    </li>
                </ul>
            </div>';

    $data[] = [
        'id' => $row['id'],
        'id_pend' => $row['id_pend'],
        'pin' => $row['pin'],
        'tanggal_buat' => $row['tanggal_buat'],
        'last_login' => $row['last_login'] ?: '0000-00-00 00:00:00',
        'status' => $row['aktif'],
        'DT_RowIndex' => $rowIndex,
        'aksi' => $aksi,
        'status_label' => ($row['aktif'] == 1) ? 'Aktif' : 'Tidak Aktif',
        'penduduk' => [
            'nik' => $row['p_nik'] ?: '',
            'nama' => $row['p_nama'] ?: '',
            'telepon' => $row['p_telepon'] ?: ''
        ]
    ];
    $rowIndex++;
}

echo json_encode([
    'draw' => $draw,
    'recordsTotal' => (int)$total,
    'recordsFiltered' => (int)$total,
    'data' => $data,
    'status' => true
]);
?>
