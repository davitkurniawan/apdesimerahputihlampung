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

// Custom Datatables endpoint to bypass encrypted ambiguous column error
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

$cat = isset($_GET['cat']) ? intval($_GET['cat']) : -1;
$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';

$draw = isset($_REQUEST['draw']) ? intval($_REQUEST['draw']) : 1;
$start = isset($_REQUEST['start']) ? intval($_REQUEST['start']) : 0;
$length = isset($_REQUEST['length']) ? intval($_REQUEST['length']) : 10;
$search = isset($_REQUEST['search']['value']) ? $conn->real_escape_string($_REQUEST['search']['value']) : '';

// Build Where
$where = "WHERE 1=1";
if ($cat > 0) {
    $where .= " AND artikel.id_kategori = $cat";
}
if ($cat == 0) {
    $where .= " AND (artikel.id_kategori = 0 OR artikel.id_kategori IS NULL)";
}
if ($status !== '' && $status !== '3') { // 1 = Aktif, 2 = Tidak Aktif
    $enabled_val = ($status == 1) ? 1 : 2;
    $where .= " AND artikel.enabled = $enabled_val";
}
if ($search !== '') {
    $where .= " AND (artikel.judul LIKE '%$search%' OR artikel.isi LIKE '%$search%')";
}

// Count total
$res = $conn->query("SELECT COUNT(*) as cnt FROM artikel $where");
$total = $res->fetch_assoc()['cnt'];

// Order by
$orderColumnIndex = isset($_REQUEST['order'][0]['column']) ? intval($_REQUEST['order'][0]['column']) : 5;
$orderDir = isset($_REQUEST['order'][0]['dir']) && strtolower($_REQUEST['order'][0]['dir']) === 'asc' ? 'ASC' : 'DESC';

$columnsMap = [
    0 => 'id',
    1 => 'id',
    2 => 'id',
    3 => 'judul',
    4 => 'hit',
    5 => 'tgl_upload'
];
$orderBy = $columnsMap[$orderColumnIndex] ?? 'tgl_upload';

// Fetch data
$sql = "SELECT artikel.*, kategori.kategori as nama_kategori FROM artikel LEFT JOIN kategori ON artikel.id_kategori = kategori.id $where ORDER BY $orderBy $orderDir LIMIT $start, $length";
$res = $conn->query($sql);

$data = [];
$rowIndex = $start + 1;
while ($row = $res->fetch_assoc()) {
    $id = $row['id'];
    
    $id_encrypted = encrypt_id($id);
    $cat_encrypted = encrypt_id($cat);
    
    $col_enabled = encrypt_id('enabled');
    $col_boleh_komentar = encrypt_id('boleh_komentar');
    $col_headline = encrypt_id('headline');
    $col_slider = encrypt_id('slider');

    // Build Actions HTML
    $aksi = '<div class="aksi-group">';
    
    // 1. Ubah Data (Blue/Indigo)
    $aksi .= '<a href="'.( "http://localhost:8080/index.php/web/form/$cat_encrypted/$id_encrypted" ).'" class="btn" style="background: rgba(99, 102, 241, 0.15); color: #818cf8; border-color: rgba(99, 102, 241, 0.3);" title="Ubah Data"><i data-lucide="pencil"></i></a>';
    
    // 2. Status/Lock Button (Orange/Green)
    if ($row['enabled'] == 2) {
        $aksi .= '<a href="'.( "http://localhost:8080/index.php/web/lock/$cat_encrypted/$col_enabled/$id_encrypted" ).'" class="btn" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border-color: rgba(16, 185, 129, 0.3);" title="Aktifkan Artikel"><i data-lucide="lock"></i></a>';
    } else {
        $aksi .= '<a href="'.( "http://localhost:8080/index.php/web/lock/$cat_encrypted/$col_enabled/$id_encrypted" ).'" class="btn" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24; border-color: rgba(245, 158, 11, 0.3);" title="Non-aktifkan Artikel"><i data-lucide="unlock"></i></a>';
    }
    
    // 3. Status Komentar (Yellow/Slate)
    if ($row['boleh_komentar'] == 1) {
        $aksi .= '<a href="'.( "http://localhost:8080/index.php/web/lock/$cat_encrypted/$col_boleh_komentar/$id_encrypted" ).'" class="btn" style="background: rgba(234, 179, 8, 0.15); color: #facc15; border-color: rgba(234, 179, 8, 0.3);" title="Tutup Komentar"><i data-lucide="message-square"></i></a>';
    } else {
        $aksi .= '<a href="'.( "http://localhost:8080/index.php/web/lock/$cat_encrypted/$col_boleh_komentar/$id_encrypted" ).'" class="btn" style="background: rgba(148, 163, 184, 0.15); color: #cbd5e1; border-color: rgba(148, 163, 184, 0.3);" title="Buka Komentar"><i data-lucide="message-square-off"></i></a>';
    }
    
    // 4. Headline (Teal/Slate)
    if ($row['headline'] == 1) {
        $aksi .= '<a href="'.( "http://localhost:8080/index.php/web/lock/$cat_encrypted/$col_headline/$id_encrypted" ).'" class="btn" style="background: rgba(20, 184, 166, 0.15); color: #2dd4bf; border-color: rgba(20, 184, 166, 0.3);" title="Batal Headline"><i data-lucide="star" style="fill: currentColor;"></i></a>';
    } else {
        $aksi .= '<a href="'.( "http://localhost:8080/index.php/web/lock/$cat_encrypted/$col_headline/$id_encrypted" ).'" class="btn" style="background: rgba(148, 163, 184, 0.15); color: #cbd5e1; border-color: rgba(148, 163, 184, 0.3);" title="Jadikan Headline"><i data-lucide="star"></i></a>';
    }
    
    // 5. Slide (Rose/Slate)
    if ($row['slider'] == 1) {
        $aksi .= '<a href="'.( "http://localhost:8080/index.php/web/lock/$cat_encrypted/$col_slider/$id_encrypted" ).'" class="btn" style="background: rgba(244, 63, 94, 0.15); color: #fb7185; border-color: rgba(244, 63, 94, 0.3);" title="Batal Slide"><i data-lucide="monitor-play"></i></a>';
    } else {
        $aksi .= '<a href="'.( "http://localhost:8080/index.php/web/lock/$cat_encrypted/$col_slider/$id_encrypted" ).'" class="btn" style="background: rgba(148, 163, 184, 0.15); color: #cbd5e1; border-color: rgba(148, 163, 184, 0.3);" title="Jadikan Slide"><i data-lucide="monitor"></i></a>';
    }
    
    // 6. Ubah Kategori (Violet)
    $aksi .= '<a href="#" data-href="'.( "http://localhost:8080/index.php/web/ubah_kategori_form/$id_encrypted" ).'" class="btn" style="background: rgba(139, 92, 246, 0.15); color: #a78bfa; border-color: rgba(139, 92, 246, 0.3);" title="Ubah Kategori" data-toggle="modal" data-target="#modalBox"><i data-lucide="layers"></i></a>';
    
    // 7. Hapus Data (Red)
    $aksi .= '<a href="#" data-href="'.( "http://localhost:8080/index.php/web/delete/$cat_encrypted/$id_encrypted" ).'" class="btn" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border-color: rgba(239, 68, 68, 0.3);" title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i data-lucide="trash-2"></i></a>';
    
    // 8. Lihat Artikel (Sky)
    $slug = $row['slug'];
    $year = date('Y', strtotime($row['tgl_upload']));
    $month = date('m', strtotime($row['tgl_upload']));
    $day = date('d', strtotime($row['tgl_upload']));
    $aksi .= '<a href="'.( "http://localhost:8080/artikel/$year/$month/$day/$slug" ).'" target="_blank" class="btn" style="background: rgba(14, 165, 233, 0.15); color: #38bdf8; border-color: rgba(14, 165, 233, 0.3);" title="Lihat Artikel"><i data-lucide="eye"></i></a>';
    
    $aksi .= '</div>';

    
    // Build Checkbox
    $ceklist = '<input type="checkbox" name="id_cb[]" value="'.$id.'">';

    $data[] = [
        'ceklist' => $ceklist,
        'DT_RowIndex' => $rowIndex,
        'aksi' => $aksi,
        'judul' => $row['judul'],
        'hit' => $row['hit'],
        'tgl_upload' => $row['tgl_upload'],
        'enabled' => $row['enabled'],
        'kategori' => $row['nama_kategori'] ?: 'Berita Desa',
        'category' => ['kategori' => $row['nama_kategori'] ?: 'Berita Desa']
    ];
    $rowIndex++;
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
