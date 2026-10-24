<?php
require_once '/var/www/html/vendor/autoload.php';
use Illuminate\Encryption\Encrypter;

function encrypt_id($id) {
    static $enc = null;
    if ($enc === null) {
        $key = base64_decode(str_replace('base64:', '', 'base64:t/z2KerZRqV5+NOcTwKRjJcjRcioD6xrlKIXMdGX9zo='));
        $enc = new Encrypter($key, 'AES-256-CBC');
    }
    return rawurlencode($enc->encrypt($id));
}

$sess_id = '';
if (isset($_COOKIE['ci_session']) && file_exists('/tmp/sess_' . $_COOKIE['ci_session'])) {
    $sess_id = $_COOKIE['ci_session'];
} elseif (isset($_COOKIE['PHPSESSID']) && file_exists('/tmp/sess_' . $_COOKIE['PHPSESSID'])) {
    $sess_id = $_COOKIE['PHPSESSID'];
}

if (!$sess_id) { die(json_encode(['draw'=>1, 'recordsTotal'=>0, 'recordsFiltered'=>0, 'data'=>[], 'status'=>true, 'error'=>'no valid session file'])); }
$sess_file = '/tmp/sess_' . $sess_id;
$content = file_get_contents($sess_file);
if (strpos($content, 'logged_in') === false && strpos($content, 'admin') === false) { 
    die(json_encode(['draw'=>1, 'recordsTotal'=>0, 'recordsFiltered'=>0, 'data'=>[], 'status'=>true])); 
}

$host = 'xosid_neural_db';
$user = 'root';
$pass = 'password';
$db = 'xosid';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(['draw'=>1, 'recordsTotal'=>0, 'recordsFiltered'=>0, 'data'=>[], 'status'=>true]));
}

$parent = isset($_REQUEST['parent']) && $_REQUEST['parent'] !== '' ? intval($_REQUEST['parent']) : 0;
$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

$draw = isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : 1;
$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
$length = isset($_REQUEST['length']) ? intval($_REQUEST['length']) : 10;
$search = isset($_REQUEST['search']['value']) ? $conn->real_escape_string($_REQUEST['search']['value']) : '';

$where = "WHERE config_id = 1 AND parrent = $parent";
if ($status !== '') {
    $enabled_val = ($status == 1) ? 1 : 2;
    $where .= " AND enabled = $enabled_val";
}
if ($search !== '') {
    $where .= " AND (nama LIKE '%$search%' OR link LIKE '%$search%')";
}

$res = $conn->query("SELECT COUNT(*) as cnt FROM menu $where");
$total = $res ? $res->fetch_assoc()['cnt'] : 0;

$sql = "SELECT * FROM menu $where ORDER BY urut ASC LIMIT $start, $length";
$res = $conn->query($sql);

$data = [];
$rowIndex = $start + 1;
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $id = $row['id'];
        $id_encrypted = encrypt_id($id);
        $parent_encrypted = encrypt_id($parent);
        
        $aksi = '<div class="aksi-group">';
        
        // 1. Sub Menu (hamburger list icon) — only shown for main menus (parent = 0)
        if ($parent == 0) {
            $aksi .= '<a href="'.("http://localhost:8080/index.php/menu?parent=$id_encrypted").'" class="btn" style="background: rgba(30, 41, 59, 0.6); color: #94a3b8; border-color: rgba(148, 163, 184, 0.2);" title="Sub Menu"><i data-lucide="list"></i></a>';
        }
        
        // 2. Ubah (edit modal dialog)
        $aksi .= '<a href="#" data-href="'.("http://localhost:8080/index.php/menu/ajax_menu/$parent_encrypted/$id_encrypted").'" class="btn" style="background: rgba(99, 102, 241, 0.15); color: #818cf8; border-color: rgba(99, 102, 241, 0.3);" title="Ubah Menu" data-toggle="modal" data-target="#modalBox" data-title="Ubah Menu"><i data-lucide="pencil"></i></a>';
        
        // 3. Status (Aktif / Nonaktif)
        if ($row['enabled'] == 1) {
            $aksi .= '<a href="'.("http://localhost:8080/index.php/menu/lock/$parent_encrypted/$id_encrypted").'" class="btn" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; border-color: rgba(245, 158, 11, 0.3);" title="Non-aktifkan"><i data-lucide="unlock"></i></a>';
        } else {
            $aksi .= '<a href="'.("http://localhost:8080/index.php/menu/lock/$parent_encrypted/$id_encrypted").'" class="btn" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border-color: rgba(16, 185, 129, 0.3);" title="Aktifkan"><i data-lucide="lock"></i></a>';
        }
        
        // 4. Hapus (delete modal confirm)
        $aksi .= '<a href="#" data-href="'.("http://localhost:8080/index.php/menu/delete/$parent_encrypted/$id_encrypted").'" class="btn" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border-color: rgba(239, 68, 68, 0.3);" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i data-lucide="trash-2"></i></a>';
        
        $aksi .= '</div>';
        
        $ceklist = '<input type="checkbox" name="id_cb[]" value="'.$id.'">';
        $drag_handle = '<span class="drag-icon"><i data-lucide="grip-vertical"></i></span>';
        
        $status_label = ($row['enabled'] == 1) ? '<span class="label label-success">Aktif</span>' : '<span class="label label-danger">Tidak Aktif</span>';

        $data[] = [
            'drag-handle' => $drag_handle,
            'id' => $id,
            'ceklist' => $ceklist,
            'DT_RowIndex' => $rowIndex,
            'aksi' => $aksi,
            'nama' => $row['nama'],
            'link' => $row['link'],
            'enabled' => $status_label,
            'status' => $row['enabled']
        ];
        $rowIndex++;
    }
}

$out_json = json_encode([
    'draw' => $draw,
    'recordsTotal' => (int)$total,
    'recordsFiltered' => (int)$total,
    'data' => $data,
    'status' => true
]);
$protocol = 'http';
if (
    (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === 1)) ||
    (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
    (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')
) {
    $protocol = 'https';
}
if (isset($_SERVER['HTTP_HOST'])) {
    $out_json = str_replace('http://localhost:8080/', $protocol . '://' . $_SERVER['HTTP_HOST'] . '/', $out_json);
    $out_json = str_replace('http://localhost/', $protocol . '://' . $_SERVER['HTTP_HOST'] . '/', $out_json);
}
echo $out_json;
?>
