<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * XOSID - Neural Penduduk Controller (Independent + Smart View)
 * Bypassing ionCube-encrypted Admin_Controller::render() pipeline
 * which silently produces 0 bytes output.
 *
 * Provides index, datatables, detail, modals, cetak, and CRUD operations.
 */
class Penduduk_neural extends CI_Controller
{
    private $db_real = null;
    private $desa = [];

    public function __construct()
    {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE) { @session_start(); }

        // Mock CI session
        $this->session = new class {
            public function userdata($key) { return $_SESSION[$key] ?? null; }
            public function set_userdata($key, $val) { $_SESSION[$key] = $val; }
            public function flashdata($key) { return $_SESSION['flash_' . $key] ?? null; }
            public function set_flashdata($key, $val) { $_SESSION['flash_' . $key] = $val; }
            public function all_userdata() { return $_SESSION ?? []; }
        };

        // Smart DB Bridge for sidebar menu
        $this->db = new class($this) {
            private $ci;
            public function __construct($ci){ $this->ci = $ci; }
            public function select($s){ return $this; }
            public function from($f){ return $this; }
            public function where($k, $v = NULL, $escape = NULL){ return $this; }
            public function insert_id(){ return 0; }
            public function affected_rows(){ return 0; }
            public function order_by($k, $v = 'ASC', $escape = NULL){ return $this; }
            public function limit($l, $o = NULL){ return $this; }
            public function count_all($t){ return 0; }
            public function error(){ return ['code' => 0, 'message' => '']; }
            public function get($t = '', $l = null, $o = null){ return $this; }
            public function row_array(){ return []; }
            public function row(){ return new \stdClass(); }
            public function result_array(){
                try {
                    $real_db = $this->ci->load->database('default', TRUE);
                    $query = $real_db->select('*')->from('setting_modul')->where('aktif', 1)->where('hidden', 0)->order_by('urut', 'ASC')->get();
                    if ($query && $query->num_rows() > 0) return $query->result_array();
                } catch (\Exception $e) {}
                return [
                    ['id' => 1, 'modul' => 'Beranda', 'url' => 'main', 'ikon' => 'fa-home', 'parent' => 0, 'urut' => 1],
                    ['id' => 2, 'modul' => 'Kependudukan', 'url' => 'penduduk', 'ikon' => 'fa-users', 'parent' => 0, 'urut' => 2],
                    ['id' => 3, 'modul' => 'Wilayah', 'url' => 'wilayah', 'ikon' => 'fa-map', 'parent' => 0, 'urut' => 3],
                    ['id' => 4, 'modul' => 'Pengaturan', 'url' => 'setting', 'ikon' => 'fa-gear', 'parent' => 0, 'urut' => 4]
                ];
            }
        };

        // Get real DB connection
        try {
            $this->db_real = $this->load->database('default', TRUE);
        } catch (\Throwable $e) {}

        // Get desa config
        try {
            if ($this->db_real) {
                $q = $this->db_real->get('config');
                $this->desa = ($q && $q->num_rows() > 0) ? $q->row_array() : [];
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Render a Blade view using Laravel's view system
     */
    private function renderBlade($viewName, $data = [])
    {
        global $app;
        $output = null;

        try {
            if (isset($app) && method_exists($app, 'make')) {
                if (class_exists('\Illuminate\Support\ViewErrorBag')) {
                    $app->make('view')->share('errors', new \Illuminate\Support\ViewErrorBag);
                }
                $app->make('view')->share('ci', $this);
                if (function_exists('config_item')) {
                    $app->make('view')->share('token_name', config_item('csrf_token_name'));
                }
                if (isset($this->security) && method_exists($this->security, 'get_csrf_hash')) {
                    $app->make('view')->share('token_value', $this->security->get_csrf_hash());
                }
                $output = $app->make('view')->make($viewName, $data)->render();
            }
        } catch (\Throwable $e) {
            @file_put_contents(FCPATH . 'storage/logs/xosid_penduduk_error.log',
                "[" . date('Y-m-d H:i:s') . "] RENDER ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n" . $e->getTraceAsString() . "\n",
                FILE_APPEND);
        }

        if ($output) {
            echo $output;
        } else {
            echo '<div style="color:red;padding:20px;">Error rendering view: ' . htmlspecialchars($viewName) . '. Check storage/logs/xosid_penduduk_error.log</div>';
        }
    }

    /**
     * Build wilayah hierarchy array for filter dropdowns
     * Returns nested: [ 'DusunA' => [ 'RW01' => [ (object){id,rt} ], ... ] ]
     */
    private function buildWilayahHierarchy()
    {
        $wilayah = [];
        if (!$this->db_real) return $wilayah;

        try {
            $q = $this->db_real->order_by('urut', 'ASC')->order_by('id', 'ASC')->get('tweb_wil_clusterdesa');
            if ($q && $q->num_rows() > 0) {
                foreach ($q->result() as $row) {
                    $d = trim($row->dusun);
                    $rw = trim($row->rw);
                    $rt = trim($row->rt);

                    if (empty($d)) continue;

                    if (!isset($wilayah[$d])) {
                        $wilayah[$d] = [];
                    }

                    if (!empty($rw) && $rw !== '0' && $rw !== '-') {
                        if (!isset($wilayah[$d][$rw])) {
                            $wilayah[$d][$rw] = [];
                        }
                        if (!empty($rt) && $rt !== '0') {
                            $obj = new \stdClass();
                            $obj->id = $row->id;
                            $obj->rt = $rt;
                            $wilayah[$d][$rw][] = $obj;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {}

        return $wilayah;
    }

    /**
     * Index - Daftar penduduk (main page)
     */
    public function index()
    {
        // Status Penduduk options
        $list_status_penduduk = [
            '1' => 'TETAP',
            '2' => 'TIDAK TETAP / PENDATANG',
        ];

        // Status Dasar options
        $list_status_dasar = [
            '1' => 'HIDUP',
            '2' => 'MATI',
            '3' => 'PINDAH',
            '4' => 'HILANG',
            '9' => 'PERGI',
        ];

        // Jenis Kelamin options
        $list_jenis_kelamin = [
            '1' => 'LAKI-LAKI',
            '2' => 'PEREMPUAN',
        ];

        // Define HEADER_PEKERJAAN if not defined
        if (!defined('HEADER_PEKERJAAN')) {
            define('HEADER_PEKERJAAN', 'PEKERJAAN');
        }

        // Build wilayah hierarchy for filter
        $wilayah = $this->buildWilayahHierarchy();

        $data = [
            'desa'                 => $this->desa,
            'nama_desa'            => $this->desa['nama_desa'] ?? 'Desa',
            'sebutan_desa'         => $this->desa['sebutan_desa'] ?? 'Desa',
            'logo'                 => $this->desa['logo'] ?? 'logo_xosid.png',
            'module_name'          => 'Penduduk',
            'controller'           => 'penduduk',
            'disableFilter'        => false,
            'list_status_penduduk' => $list_status_penduduk,
            'list_status_dasar'    => $list_status_dasar,
            'defaultStatusDasar'   => 1, // default: HIDUP
            'list_jenis_kelamin'   => $list_jenis_kelamin,
            'wilayah'              => $wilayah,
            'labelWilayah'         => false,
            'judul_statistik'      => null,
            'advanceSearch'        => [],
            'statistikFilter'      => [],
            'filterColumn'         => [],
            'akses'                => 1, // superadmin group ID
        ];

        $this->renderBlade('admin.penduduk.index', $data);
    }

    /**
     * DataTables server-side handler for penduduk
     * This is a POST endpoint called via AJAX
     */
    public function datatables()
    {
        if (!$this->db_real) {
            echo json_encode(['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
            return;
        }

        $draw   = $this->input->post('draw') ?: 1;
        $start  = (int)($this->input->post('start') ?: 0);
        $length = (int)($this->input->post('length') ?: 10);
        $search = $this->input->post('search')['value'] ?? '';

        // Filters
        $status_penduduk = $this->input->post('status_penduduk') ?: '';
        $status_dasar    = $this->input->post('status_dasar') !== null && $this->input->post('status_dasar') !== '' ? $this->input->post('status_dasar') : '1';
        $jenis_kelamin   = $this->input->post('jenis_kelamin') ?: '';
        $dusun           = $this->input->post('dusun') ?: '';
        $rw              = $this->input->post('rw') ?: '';
        $rt              = $this->input->post('rt') ?: '';
        $kumpulan_nik    = $this->input->post('kumpulan_nik') ?: '';
        $nik_sementara   = $this->input->post('nik_sementara') ?: '';
        $bantuan         = $this->input->post('bantuan') ?: '';
        $id_cb_raw       = $this->input->post('id_cb') ?: '';

        try {
            // Build filter WHERE
            $where = [];
            $params = [];

            // Handle checked rows override
            $has_checked = false;
            if (!empty($id_cb_raw)) {
                $ids = is_array($id_cb_raw) ? $id_cb_raw : json_decode($id_cb_raw, true);
                if (is_array($ids) && count($ids) > 0) {
                    $clean_ids = array_map('intval', $ids);
                    $where[] = "p.id IN (" . implode(',', $clean_ids) . ")";
                    $has_checked = true;
                }
            }

            if (!$has_checked) {
                // NIK Sementara: bypass status_dasar, filter khusus NIK non-standar
                if (!empty($nik_sementara)) {
                    $where[] = "(p.nik LIKE '0%' OR p.nik NOT REGEXP '^[0-9]{16}$')";
                } else {
                    if ($status_dasar !== '' && $status_dasar !== '0' && $status_dasar !== 'TOTAL') {
                        $where[] = "p.status_dasar = ?";
                        $params[] = $status_dasar;
                    }
                }

                if ($status_penduduk !== '') {
                    $where[] = "p.status = ?";
                    $params[] = $status_penduduk;
                }
                if ($jenis_kelamin !== '') {
                    $where[] = "p.sex = ?";
                    $params[] = $jenis_kelamin;
                }
                if ($dusun !== '') {
                    $where[] = "w.dusun = ?";
                    $params[] = $dusun;
                }
                if ($rw !== '') {
                    $rw_parts = explode('__', $rw);
                    if (count($rw_parts) == 2) {
                        $where[] = "w.dusun = ?";
                        $params[] = $rw_parts[0];
                        $where[] = "w.rw = ?";
                        $params[] = $rw_parts[1];
                    }
                }
                if ($rt !== '') {
                    $where[] = "p.id_cluster = ?";
                    $params[] = $rt;
                }
                if (!empty($kumpulan_nik)) {
                    $niks = is_array($kumpulan_nik)
                        ? array_map('trim', $kumpulan_nik)
                        : array_map('trim', explode(',', $kumpulan_nik));
                    $niks = array_filter($niks);
                    if (!empty($niks)) {
                        $placeholders = implode(',', array_fill(0, count($niks), '?'));
                        $where[] = "p.nik IN ({$placeholders})";
                        foreach ($niks as $n) { $params[] = $n; }
                    }
                }
                if (!empty($bantuan)) {
                    $where[] = "p.id IN (SELECT id_penduduk FROM program_peserta WHERE program_id = ?)";
                    $params[] = (int)$bantuan;
                }
            }

            // Search
            if (!empty($search)) {
                $where[] = "(p.nik LIKE ? OR p.nama LIKE ? OR p.tag_id_card LIKE ?)";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
            }

            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

            // Order
            $orderCol = 'p.nik';
            $orderDir = 'ASC';
            $order = $this->input->post('order');
            if ($order && isset($order[0])) {
                $colIdx = $order[0]['column'] ?? 4;
                $dir = strtoupper($order[0]['dir'] ?? 'ASC');
                if (!in_array($dir, ['ASC', 'DESC'])) $dir = 'ASC';
                $orderDir = $dir;
                $colMap = [
                    4 => 'p.nik', 6 => 'p.nama', 7 => 'k.no_kk',
                    17 => 'p.tanggallahir', 20 => 'p.created_at'
                ];
                $orderCol = $colMap[$colIdx] ?? 'p.nik';
            }

            // Count total (unfiltered)
            $totalQuery = $this->db_real->query("SELECT COUNT(*) as total FROM tweb_penduduk");
            $recordsTotal = ($totalQuery) ? $totalQuery->row()->total : 0;

            // Count filtered
            $countJoins = "LEFT JOIN tweb_wil_clusterdesa w ON p.id_cluster = w.id LEFT JOIN tweb_keluarga k ON p.id_kk = k.id";
            $countSql = "SELECT COUNT(*) as total FROM tweb_penduduk p {$countJoins} {$whereClause}";
            $countQ = $this->db_real->query($countSql, $params);
            $recordsFiltered = ($countQ) ? $countQ->row()->total : 0;

            // Fetch data
            $sql = "SELECT p.id, p.nik, p.nama, p.sex, p.tempatlahir, p.tanggallahir, p.status_dasar, p.status,
                        p.foto, p.id_kk, p.tag_id_card, p.nama_ayah, p.nama_ibu,
                        p.alamat_sekarang, p.created_at, p.id_cluster,
                        k.no_kk, k.alamat as alamat_kk,
                        w.dusun, w.rw, w.rt,
                        pd.nama as pendidikan_kk_nama,
                        pk.nama as pekerjaan_nama,
                        sp.nama as status_perkawinan_nama,
                        h.nama as hubungan_nama,
                        ag.nama as agama_nama
                   FROM tweb_penduduk p
                   LEFT JOIN tweb_wil_clusterdesa w ON p.id_cluster = w.id
                   LEFT JOIN tweb_keluarga k ON p.id_kk = k.id
                   LEFT JOIN tweb_penduduk_pendidikan_kk pd ON p.pendidikan_kk_id = pd.id
                   LEFT JOIN tweb_penduduk_pekerjaan pk ON p.pekerjaan_id = pk.id
                   LEFT JOIN tweb_penduduk_kawin sp ON p.status_kawin = sp.id
                   LEFT JOIN tweb_penduduk_hubungan h ON p.kk_level = h.id
                   LEFT JOIN tweb_penduduk_agama ag ON p.agama_id = ag.id
                   {$whereClause}
                   ORDER BY {$orderCol} {$orderDir}
                   LIMIT ?, ?";

            $sqlParams = array_merge($params, [$start, $length]);
            $result = $this->db_real->query($sql, $sqlParams);
            $data = [];

            if ($result && $result->num_rows() > 0) {
                $idx = $start + 1;
                foreach ($result->result() as $row) {
                    // Calculate age
                    $umur = '-';
                    if ($row->tanggallahir) {
                        try {
                            $birth = new \DateTime($row->tanggallahir);
                            $now = new \DateTime();
                            $umur = $now->diff($birth)->y;
                        } catch (\Throwable $e) { $umur = '-'; }
                    }

                    // Jenis kelamin text
                    $jk = ($row->sex == 1) ? 'LAKI-LAKI' : (($row->sex == 2) ? 'PEREMPUAN' : '-');

                    // Photo avatar
                    $foto = '';
                    if (!empty($row->foto) && $row->foto !== 'kuser.png' && file_exists(FCPATH . 'desa/upload/user_pict/' . $row->foto)) {
                        $foto = '<div style="display:inline-flex;align-items:center;justify-content:center;">'
                            . '<img src="' . base_url("desa/upload/user_pict/{$row->foto}") . '" alt="" style="width:36px;height:36px;border-radius:10px;object-fit:cover;border:1.5px solid #cbd5e1;box-shadow:0 2px 6px rgba(15,23,42,0.08);">'
                            . '</div>';
                    } else {
                        $isFemale = ($row->sex == 2);
                        $bgGradient = $isFemale ? 'linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%)' : 'linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%)';
                        $borderColor = $isFemale ? '#fecdd3' : '#bfdbfe';
                        $iconColor = $isFemale ? '#e11d48' : '#2563eb';
                        $glowShadow = $isFemale ? '0 2px 8px rgba(225, 29, 72, 0.18)' : '0 2px 8px rgba(37, 99, 235, 0.18)';
                        $iconClass = $isFemale ? 'fa-female' : 'fa-user';
                        $foto = '<div class="xosid-avatar-glow" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:10px;background:' . $bgGradient . ';border:1.5px solid ' . $borderColor . ';color:' . $iconColor . ';box-shadow:' . $glowShadow . ';font-size:15px;transition:all 0.2s cubic-bezier(0.4, 0, 0.2, 1);cursor:default;" title="' . ($isFemale ? 'Perempuan' : 'Laki-Laki') . '">'
                            . '<i class="fa ' . $iconClass . '"></i>'
                            . '</div>';
                    }

                    // Aksi dropdown
                    $detailUrl     = site_url("penduduk/detail/{$row->id}");
                    $formUrl       = site_url("penduduk/form/{$row->id}");
                    $mapsUrl       = site_url("penduduk/ajax_penduduk_maps/{$row->id}");
                    $statusDasarUrl = site_url("penduduk/ajax_edit_status_dasar/{$row->id}");
                    $dokumenUrl    = site_url("penduduk/dokumen/{$row->id}");
                    $cetakUrl      = site_url("penduduk/cetak_biodata/{$row->id}");
                    $deleteUrl     = site_url("penduduk/delete/{$row->id}");

                    $aksi = '<div class="btn-group xosid-action-dropdown" style="position: relative;">'
                        . '<button type="button" class="btn dropdown-toggle xosid-btn-aksi" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 6px 14px; font-weight: 700; border-radius: 10px; font-size: 12px; font-family: \'Outfit\', sans-serif; display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #153E7E 0%, #1d4ed8 100%); color: #ffffff; border: none; box-shadow: 0 3px 10px rgba(21, 62, 126, 0.2); transition: all 0.2s ease;">'
                        . '<i class="fa fa-arrow-circle-down" style="font-size: 13px;"></i> <span>Pilih Aksi</span> <span class="caret" style="margin-left: 2px;"></span>'
                        . '</button>'
                        . '<ul class="dropdown-menu dropdown-menu-left xosid-action-menu" role="menu" style="border-radius: 16px; box-shadow: 0 16px 40px rgba(15, 23, 42, 0.14), 0 4px 12px rgba(15, 23, 42, 0.06); border: 1px solid #e2e8f0; padding: 8px; min-width: 265px; font-size: 12.5px; z-index: 1050; text-align: left; background: #ffffff;">'
                        . '<li><a href="' . $detailUrl . '" class="xosid-menu-item" style="padding: 9px 12px; color: #1e293b; display: flex; align-items: center; gap: 12px; font-weight: 600; border-radius: 10px; transition: all 0.15s ease;"><span class="xosid-icon-glow-sm" style="width: 28px; height: 28px; border-radius: 8px; background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 0 10px rgba(37,99,235,0.15); flex-shrink: 0;"><i class="fa fa-id-card-o" style="font-size: 12px;"></i></span> <span>Lihat Detail Biodata Penduduk</span></a></li>'
                        . '<li><a href="' . $formUrl . '" class="xosid-menu-item" style="padding: 9px 12px; color: #1e293b; display: flex; align-items: center; gap: 12px; font-weight: 600; border-radius: 10px; transition: all 0.15s ease;"><span class="xosid-icon-glow-sm" style="width: 28px; height: 28px; border-radius: 8px; background: #fffbeb; border: 1px solid #fde68a; color: #d97706; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 0 10px rgba(245,158,11,0.15); flex-shrink: 0;"><i class="fa fa-pencil" style="font-size: 12px;"></i></span> <span>Ubah Biodata Penduduk</span></a></li>'
                        . '<li><a href="' . $mapsUrl . '" class="xosid-menu-item" style="padding: 9px 12px; color: #1e293b; display: flex; align-items: center; gap: 12px; font-weight: 600; border-radius: 10px; transition: all 0.15s ease;"><span class="xosid-icon-glow-sm" style="width: 28px; height: 28px; border-radius: 8px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #059669; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 0 10px rgba(16,185,129,0.15); flex-shrink: 0;"><i class="fa fa-map-marker" style="font-size: 13px;"></i></span> <span>Lihat Lokasi Tempat Tinggal</span></a></li>'
                        . '<li><a href="' . $statusDasarUrl . '" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Status Dasar" class="xosid-menu-item" style="padding: 9px 12px; color: #1e293b; display: flex; align-items: center; gap: 12px; font-weight: 600; border-radius: 10px; transition: all 0.15s ease;"><span class="xosid-icon-glow-sm" style="width: 28px; height: 28px; border-radius: 8px; background: #faf5ff; border: 1px solid #e9d5ff; color: #9333ea; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 0 10px rgba(147,51,234,0.15); flex-shrink: 0;"><i class="fa fa-exchange" style="font-size: 12px;"></i></span> <span>Ubah Status Dasar</span></a></li>'
                        . '<li><a href="' . $dokumenUrl . '" class="xosid-menu-item" style="padding: 9px 12px; color: #1e293b; display: flex; align-items: center; gap: 12px; font-weight: 600; border-radius: 10px; transition: all 0.15s ease;"><span class="xosid-icon-glow-sm" style="width: 28px; height: 28px; border-radius: 8px; background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 0 10px rgba(22,163,74,0.15); flex-shrink: 0;"><i class="fa fa-upload" style="font-size: 12px;"></i></span> <span>Upload Dokumen Penduduk</span></a></li>'
                        . '<li><a href="' . $cetakUrl . '" target="_blank" class="xosid-menu-item" style="padding: 9px 12px; color: #1e293b; display: flex; align-items: center; gap: 12px; font-weight: 600; border-radius: 10px; transition: all 0.15s ease;"><span class="xosid-icon-glow-sm" style="width: 28px; height: 28px; border-radius: 8px; background: #f0f9ff; border: 1px solid #bae6fd; color: #0284c7; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 0 10px rgba(2,132,199,0.15); flex-shrink: 0;"><i class="fa fa-print" style="font-size: 12px;"></i></span> <span>Cetak Biodata Penduduk</span></a></li>'
                        . '<li class="divider" style="margin: 6px 4px; border-top: 1px solid #f1f5f9;"></li>'
                        . '<li><a href="' . $deleteUrl . '" onclick="return confirm(\'Apakah Anda yakin ingin menghapus data penduduk ini?\');" class="xosid-menu-item-danger" style="padding: 9px 12px; color: #dc2626; display: flex; align-items: center; gap: 12px; font-weight: 700; border-radius: 10px; transition: all 0.15s ease;"><span class="xosid-icon-glow-sm" style="width: 28px; height: 28px; border-radius: 8px; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 0 10px rgba(220,38,38,0.15); flex-shrink: 0;"><i class="fa fa-trash-o" style="font-size: 13px;"></i></span> <span>Hapus</span></a></li>'
                        . '</ul>'
                        . '</div>';

                    $alamat_wilayah = trim(($row->alamat_sekarang ?: ($row->alamat_kk ?: '')));

                    $data[] = [
                        'ceklist'           => '<input type="checkbox" name="id_cb[]" value="' . $row->id . '">',
                        'DT_RowIndex'       => $idx++,
                        'aksi'              => $aksi,
                        'foto'              => $foto,
                        'nik'               => $row->nik ?: '-',
                        'tag_id_card'       => $row->tag_id_card ?: '-',
                        'nama'              => $row->nama ?: '-',
                        'keluarga'          => ['no_kk' => $row->no_kk ?: ''],
                        'id_kk'             => $row->id_kk,
                        'nama_ayah'         => $row->nama_ayah ?: '-',
                        'nama_ibu'          => $row->nama_ibu ?: '-',
                        'no_kk'             => '',
                        'jenis_kelamin'     => $jk,
                        'alamat_wilayah'    => $alamat_wilayah,
                        'wilayah'           => [
                            'dusun' => $row->dusun ?: '-',
                            'rw'    => $row->rw ?: '-',
                            'rt'    => $row->rt ?: '-',
                        ],
                        'pendidikan_kk'     => $row->pendidikan_kk_nama ?: '-',
                        'umur'              => $umur,
                        'pekerjaan'         => $row->pekerjaan_nama ?: '-',
                        'status_perkawinan' => $row->status_perkawinan_nama ?: '-',
                        'tgl_peristiwa'     => '-',
                        'created_at'        => $row->created_at ?: '-',
                        'valid_kk'          => '',
                        'id'                => $row->id,
                    ];
                }
            }

            echo json_encode([
                'draw'            => (int) $draw,
                'recordsTotal'    => (int) $recordsTotal,
                'recordsFiltered' => (int) $recordsFiltered,
                'data'            => $data,
            ]);
        } catch (\Throwable $e) {
            @file_put_contents(FCPATH . 'storage/logs/xosid_penduduk_error.log',
                "[" . date('Y-m-d H:i:s') . "] DATATABLES ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
            echo json_encode(['draw' => (int) $draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
        }
    }

    /**
     * Detail Biodata Penduduk
     */
    public function detail($id = null)
    {
        if (!$id || !$this->db_real) {
            redirect(site_url('penduduk'));
            return;
        }

        try {
            $sql = "SELECT p.*, 
                        k.no_kk, k.alamat as alamat_kk,
                        w.dusun, w.rw, w.rt,
                        pd.nama as pendidikan_kk,
                        pk.nama as pekerjaan,
                        sp.nama as status_kawin,
                        ag.nama as agama,
                        wn.nama as warganegara,
                        gd.nama as golongan_darah,
                        pdl.nama as pendidikan_sedang,
                        hub.nama as penduduk_hubungan,
                        c.nama as cacat,
                        kb.nama as cara_kb,
                        sm.nama as sakit_menahun,
                        asu.nama as asuransi
                   FROM tweb_penduduk p
                   LEFT JOIN tweb_wil_clusterdesa w ON p.id_cluster = w.id
                   LEFT JOIN tweb_keluarga k ON p.id_kk = k.id
                   LEFT JOIN tweb_penduduk_pendidikan_kk pd ON p.pendidikan_kk_id = pd.id
                   LEFT JOIN tweb_penduduk_pekerjaan pk ON p.pekerjaan_id = pk.id
                   LEFT JOIN tweb_penduduk_kawin sp ON p.status_kawin = sp.id
                   LEFT JOIN tweb_penduduk_agama ag ON p.agama_id = ag.id
                   LEFT JOIN tweb_penduduk_warganegara wn ON p.warganegara_id = wn.id
                   LEFT JOIN tweb_golongan_darah gd ON p.golongan_darah_id = gd.id
                   LEFT JOIN tweb_penduduk_pendidikan pdl ON p.pendidikan_sedang_id = pdl.id
                   LEFT JOIN tweb_penduduk_hubungan hub ON p.kk_level = hub.id
                   LEFT JOIN tweb_cacat c ON p.cacat_id = c.id
                   LEFT JOIN tweb_cara_kb kb ON p.cara_kb_id = kb.id
                   LEFT JOIN tweb_sakit_menahun sm ON p.sakit_menahun_id = sm.id
                   LEFT JOIN tweb_penduduk_asuransi asu ON p.id_asuransi = asu.id
                   WHERE p.id = ?";
            $q = $this->db_real->query($sql, [$id]);
            if (!$q || $q->num_rows() == 0) {
                redirect(site_url('penduduk'));
                return;
            }
            $penduduk = $q->row();

            // Set sub-objects
            $penduduk->keluarga = (object)[
                'no_kk' => $penduduk->no_kk ?? ''
            ];
            $penduduk->wilayah = (object)[
                'dusun' => $penduduk->dusun ?? '',
                'rw'    => $penduduk->rw ?? '',
                'rt'    => $penduduk->rt ?? ''
            ];

            // Helpers for text attributes
            $tempatDilahirkan = [1 => 'RS/RB', 2 => 'Puskesmas', 3 => 'Polindes', 4 => 'Rumah Bersalin', 5 => 'Rumah', 6 => 'Lainnya'];
            $jenisKelahiran = [1 => 'Tunggal', 2 => 'Kembar 2', 3 => 'Kembar 3', 4 => 'Kembar 4'];
            $penolongKelahiran = [1 => 'Dokter', 2 => 'Bidan/Perawat', 3 => 'Dukun', 4 => 'Lainnya'];

            $penduduk->dilahirkan = $tempatDilahirkan[$penduduk->tempat_dilahirkan] ?? ($penduduk->tempat_dilahirkan ?: '-');
            $penduduk->jenisLahir = $jenisKelahiran[$penduduk->jenis_kelahiran] ?? ($penduduk->jenis_kelahiran ?: '-');
            $penduduk->penolongLahir = $penolongKelahiran[$penduduk->penolong_kelahiran] ?? ($penduduk->penolong_kelahiran ?: '-');
            $penduduk->pekerja_migran = $penduduk->pekerja_migran ?? '-';
            $penduduk->suku = $penduduk->suku ?? '-';
            $penduduk->marga = $penduduk->marga ?? '-';
            $penduduk->adat = $penduduk->adat ?? '-';

            // Convert tanggallahir to Carbon/DateTime object
            if (!empty($penduduk->tanggallahir)) {
                try {
                    $penduduk->tanggallahir = \Carbon\Carbon::parse($penduduk->tanggallahir);
                } catch (\Throwable $e) {}
            }

            // Fetch Documents
            $list_dokumen = [];
            try {
                $qd = $this->db_real->query("SELECT * FROM dokumen_hidup WHERE id_pend = ? ORDER BY id DESC", [$id]);
                if ($qd) {
                    $list_dokumen = $qd->result();
                }
            } catch (\Throwable $e) {}

            // Fetch Assistance Programs
            $program = [];
            try {
                $qp = $this->db_real->query("SELECT p.*, b.nama as program_nama, b.sdate, b.edate, b.ndesc FROM program_peserta p JOIN program b ON p.program_id = b.id WHERE p.peserta = ? ORDER BY p.id DESC", [$penduduk->nik]);
                if ($qp) {
                    $program = $qp->result();
                    foreach ($program as &$pr) {
                        $pr->bantuanPenduduk = (object)[
                            'nama'  => $pr->program_nama ?? $pr->nama ?? 'Program Bantuan',
                            'sdate' => $pr->sdate ?? null,
                            'edate' => $pr->edate ?? null,
                            'ndesc' => $pr->ndesc ?? '',
                        ];
                    }
                }
            } catch (\Throwable $e) {}

        } catch (\Throwable $e) {
            @file_put_contents(FCPATH . 'storage/logs/xosid_penduduk_error.log',
                "[" . date('Y-m-d H:i:s') . "] DETAIL ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
            redirect(site_url('penduduk'));
            return;
        }

        $data = [
            'module_name'  => 'Penduduk',
            'desa'         => $this->desa,
            'nama_desa'    => $this->desa['nama_desa'] ?? 'Desa',
            'sebutan_desa' => $this->desa['sebutan_desa'] ?? 'Desa',
            'logo'         => $this->desa['logo'] ?? 'logo_xosid.png',
            'penduduk'     => $penduduk,
            'list_dokumen' => $list_dokumen,
            'program'      => $program,
            'controller'   => 'penduduk',
        ];

        $this->renderBlade('admin.penduduk.detail', $data);
    }

    /**
     * Cetak Biodata per Penduduk
     */
    public function cetak_biodata($id = null)
    {
        if (!$id || !$this->db_real) {
            redirect(site_url('penduduk'));
            return;
        }

        try {
            $sql = "SELECT p.*, 
                        k.no_kk, k.alamat as alamat_kk,
                        w.dusun, w.rw, w.rt,
                        pd.nama as pendidikan_kk,
                        pk.nama as pekerjaan,
                        sp.nama as status_perkawinan,
                        ag.nama as agama,
                        wn.nama as warganegara,
                        gd.nama as golongan_darah,
                        pdl.nama as pendidikan_sedang,
                        hub.nama as penduduk_hubungan,
                        c.nama as cacat,
                        kb.nama as cara_kb,
                        sm.nama as sakit_menahun,
                        asu.nama as asuransi
                   FROM tweb_penduduk p
                   LEFT JOIN tweb_wil_clusterdesa w ON p.id_cluster = w.id
                   LEFT JOIN tweb_keluarga k ON p.id_kk = k.id
                   LEFT JOIN tweb_penduduk_pendidikan_kk pd ON p.pendidikan_kk_id = pd.id
                   LEFT JOIN tweb_penduduk_pekerjaan pk ON p.pekerjaan_id = pk.id
                   LEFT JOIN tweb_penduduk_kawin sp ON p.status_kawin = sp.id
                   LEFT JOIN tweb_penduduk_agama ag ON p.agama_id = ag.id
                   LEFT JOIN tweb_penduduk_warganegara wn ON p.warganegara_id = wn.id
                   LEFT JOIN tweb_golongan_darah gd ON p.golongan_darah_id = gd.id
                   LEFT JOIN tweb_penduduk_pendidikan pdl ON p.pendidikan_sedang_id = pdl.id
                   LEFT JOIN tweb_penduduk_hubungan hub ON p.kk_level = hub.id
                   LEFT JOIN tweb_cacat c ON p.cacat_id = c.id
                   LEFT JOIN tweb_cara_kb kb ON p.cara_kb_id = kb.id
                   LEFT JOIN tweb_sakit_menahun sm ON p.sakit_menahun_id = sm.id
                   LEFT JOIN tweb_penduduk_asuransi asu ON p.id_asuransi = asu.id
                   WHERE p.id = ?";
            $q = $this->db_real->query($sql, [$id]);
            if (!$q || $q->num_rows() == 0) {
                redirect(site_url('penduduk'));
                return;
            }
            $penduduk = $q->row();
            $penduduk->keluarga = (object)[
                'no_kk' => $penduduk->no_kk ?? ''
            ];
            $penduduk->wilayah = (object)[
                'dusun' => $penduduk->dusun ?? '',
                'rw'    => $penduduk->rw ?? '',
                'rt'    => $penduduk->rt ?? ''
            ];
            $penduduk->id_sex = $penduduk->sex;
            $penduduk->jenis_kelamin = ($penduduk->sex == 1) ? 'LAKI-LAKI' : (($penduduk->sex == 2) ? 'PEREMPUAN' : '-');

            if (!empty($penduduk->tanggallahir)) {
                try {
                    $penduduk->tanggallahir = \Carbon\Carbon::parse($penduduk->tanggallahir);
                } catch (\Throwable $e) {}
            }

            $data = [
                'file'     => 'Biodata Penduduk - ' . $penduduk->nama,
                'desa'     => $this->desa,
                'penduduk' => $penduduk,
            ];

            $this->renderBlade('admin.penduduk.cetak_biodata', $data);
        } catch (\Throwable $e) {
            redirect(site_url('penduduk'));
        }
    }

    /**
     * Modal Dialogs for Cetak & Unduh
     */
    public function ajax_cetak($aksi = 'cetak')
    {
        $data = [
            'aksi'   => $aksi,
            'action' => site_url("penduduk/{$aksi}"),
        ];
        $this->renderBlade('admin.penduduk.ajax_cetak_bersama', $data);
    }

    /**
     * Cetak Laporan Penduduk Bersama
     */
    public function cetak($privasi_nik = 0)
    {
        $this->_export_data('cetak', $privasi_nik);
    }

    /**
     * Unduh Dokumen Penduduk Bersama
     */
    public function unduh($privasi_nik = 0)
    {
        $this->_export_data('unduh', $privasi_nik);
    }

    /**
     * Helper for Cetak & Unduh Penduduk
     */
    private function _export_data($aksi = 'cetak', $privasi_nik = 0)
    {
        if (!$this->db_real) {
            redirect(site_url('penduduk'));
            return;
        }

        // Get filter inputs from GET or POST
        $search          = trim($this->input->get_post('search')['value'] ?? $this->input->get_post('search') ?? '');
        $status_penduduk = $this->input->get_post('status_penduduk') ?: '';
        $status_dasar    = $this->input->get_post('status_dasar') !== null && $this->input->get_post('status_dasar') !== '' ? $this->input->get_post('status_dasar') : '1';
        $jenis_kelamin   = $this->input->get_post('jenis_kelamin') ?: '';
        $dusun           = $this->input->get_post('dusun') ?: '';
        $rw              = $this->input->get_post('rw') ?: '';
        $rt              = $this->input->get_post('rt') ?: '';
        $kumpulan_nik    = $this->input->get_post('kumpulan_nik') ?: '';
        $nik_sementara   = $this->input->get_post('nik_sementara') ?: '';
        $bantuan         = $this->input->get_post('bantuan') ?: '';
        $id_cb_raw       = $this->input->get_post('id_cb') ?: '';
        $judul           = $this->input->get_post('judul') ?: 'DAFTAR PENDUDUK';

        $where = [];
        $params = [];

        // Check if specific IDs were checked in table
        $has_checked = false;
        if (!empty($id_cb_raw)) {
            $ids = is_array($id_cb_raw) ? $id_cb_raw : json_decode($id_cb_raw, true);
            if (is_array($ids) && count($ids) > 0) {
                $clean_ids = array_map('intval', $ids);
                $where[] = "p.id IN (" . implode(',', $clean_ids) . ")";
                $has_checked = true;
            }
        }

        if (!$has_checked) {
            // Saat filter NIK Sementara aktif, jangan paksa status_dasar agar semua status diikutsertakan
            if (!empty($nik_sementara)) {
                // NIK Sementara: NIK diawali '0' ATAU tidak berformat 16 digit numerik standar
                $where[] = "(p.nik LIKE '0%' OR p.nik NOT REGEXP '^[0-9]{16}$')";
            } else {
                if ($status_dasar !== '' && $status_dasar !== '0' && $status_dasar !== 'TOTAL') {
                    $where[] = "p.status_dasar = ?";
                    $params[] = $status_dasar;
                }
            }
            // Filter berikut berlaku di semua mode (nik_sementara aktif maupun tidak)
            if ($status_penduduk !== '') {
                $where[] = "p.status = ?";
                $params[] = $status_penduduk;
            }
            if ($jenis_kelamin !== '') {
                $where[] = "p.sex = ?";
                $params[] = $jenis_kelamin;
            }
            if ($dusun !== '') {
                $where[] = "w.dusun = ?";
                $params[] = $dusun;
            }
            if ($rw !== '') {
                $rw_parts = explode('__', $rw);
                if (count($rw_parts) == 2) {
                    $where[] = "w.dusun = ?";
                    $params[] = $rw_parts[0];
                    $where[] = "w.rw = ?";
                    $params[] = $rw_parts[1];
                }
            }
            if ($rt !== '') {
                $where[] = "p.id_cluster = ?";
                $params[] = $rt;
            }
            if (!empty($kumpulan_nik)) {
                $niks = array_map('trim', explode(',', $kumpulan_nik));
                $nik_placeholders = implode(',', array_fill(0, count($niks), '?'));
                $where[] = "p.nik IN ({$nik_placeholders})";
                foreach ($niks as $n) { $params[] = $n; }
            }
            if (!empty($bantuan)) {
                $where[] = "p.id IN (SELECT id_penduduk FROM program_peserta WHERE program_id = ?)";
                $params[] = (int)$bantuan;
            }
            if (!empty($search)) {
                $where[] = "(p.nik LIKE ? OR p.nama LIKE ? OR p.tag_id_card LIKE ?)";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
            }
        }


        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        // Order
        $orderCol = 'p.nik';
        $orderDir = 'ASC';
        $order = $this->input->get_post('order');
        if ($order && isset($order[0])) {
            $colIdx = $order[0]['column'] ?? 4;
            $dir = strtoupper($order[0]['dir'] ?? 'ASC');
            if (!in_array($dir, ['ASC', 'DESC'])) $dir = 'ASC';
            $orderDir = $dir;

            $colMap = [
                4 => 'p.nik', 6 => 'p.nama', 7 => 'k.no_kk',
                17 => 'p.tanggallahir', 20 => 'p.created_at'
            ];
            $orderCol = $colMap[$colIdx] ?? 'p.nik';
        }

        $sql = "SELECT p.id, p.nik, p.nama, p.sex, p.tempatlahir, p.tanggallahir, p.status_dasar, p.status,
                    p.foto, p.id_kk, p.tag_id_card, p.nama_ayah, p.nama_ibu,
                    p.alamat_sekarang, p.created_at, p.id_cluster,
                    k.no_kk, k.alamat as alamat_kk,
                    w.dusun, w.rw, w.rt,
                    pd.nama as pendidikan_kk_nama,
                    pk.nama as pekerjaan_nama,
                    sp.nama as status_perkawinan_nama,
                    h.nama as hubungan_nama,
                    ag.nama as agama_nama
               FROM tweb_penduduk p
               LEFT JOIN tweb_wil_clusterdesa w ON p.id_cluster = w.id
               LEFT JOIN tweb_keluarga k ON p.id_kk = k.id
               LEFT JOIN tweb_penduduk_pendidikan_kk pd ON p.pendidikan_kk_id = pd.id
               LEFT JOIN tweb_penduduk_pekerjaan pk ON p.pekerjaan_id = pk.id
               LEFT JOIN tweb_penduduk_kawin sp ON p.status_kawin = sp.id
               LEFT JOIN tweb_penduduk_hubungan h ON p.kk_level = h.id
               LEFT JOIN tweb_penduduk_agama ag ON p.agama_id = ag.id
               {$whereClause}
               ORDER BY {$orderCol} {$orderDir}";

        $result = $this->db_real->query($sql, $params);
        $main = [];

        if ($result && $result->num_rows() > 0) {
            foreach ($result->result() as $row) {
                // Calculate age
                $umur = '-';
                if ($row->tanggallahir) {
                    try {
                        $birth = new \DateTime($row->tanggallahir);
                        $now = new \DateTime();
                        $umur = $now->diff($birth)->y;
                    } catch (\Throwable $e) { $umur = '-'; }
                }

                $jk = ($row->sex == 1) ? 'LAKI-LAKI' : (($row->sex == 2) ? 'PEREMPUAN' : '-');

                $obj = (object)[
                    'id'                => $row->id,
                    'nik'               => $row->nik,
                    'tag_id_card'       => $row->tag_id_card ?: '-',
                    'nama'              => $row->nama,
                    'keluarga'          => (object)[
                        'no_kk'          => $row->no_kk ?: '-',
                        'alamat_wilayah' => $row->alamat_kk ?: $row->alamat_sekarang ?: '-'
                    ],
                    'alamat_wilayah'    => $row->alamat_kk ?: $row->alamat_sekarang ?: '-',
                    'wilayah'           => (object)[
                        'dusun'          => $row->dusun ?: '-',
                        'rw'             => $row->rw ?: '-',
                        'rt'             => $row->rt ?: '-'
                    ],
                    'jenis_kelamin'     => $jk,
                    'tempatlahir'       => $row->tempatlahir ?: '-',
                    'tanggallahir'      => $row->tanggallahir,
                    'umur'              => $umur,
                    'agama'             => $row->agama_nama ?: '-',
                    'pendidikan_kk'     => $row->pendidikan_kk_nama ?: '-',
                    'pekerjaan'         => $row->pekerjaan_nama ?: '-',
                    'status_perkawinan' => $row->status_perkawinan_nama ?: '-',
                    'penduduk_hubungan' => $row->hubungan_nama ?: '-',
                    'nama_ayah'         => $row->nama_ayah ?: '-',
                    'nama_ibu'          => $row->nama_ibu ?: '-',
                    'status'            => $row->status
                ];
                $main[] = $obj;
            }
        }

        $data = [
            'aksi'        => $aksi,
            'judul'       => $judul,
            'main'        => $main,
            'privasi_nik' => (bool)$privasi_nik,
            'start'       => 0,
            'file'        => 'data_penduduk_' . date('Ymd_His'),
            'desa'        => $this->desa
        ];

        if ($aksi === 'unduh') {
            header('Content-Type: application/vnd.ms-excel; charset=utf-8');
            header('Content-Disposition: attachment; filename="data_penduduk_' . date('Ymd_His') . '.xls"');
            header('Pragma: no-cache');
            header('Expires: 0');
        }

        $this->renderBlade('admin.penduduk.cetak', $data);
    }

    /**
     * Modal Advanced Search
     */
    public function ajax_adv_search()
    {
        $data = [
            'form_action'          => site_url('penduduk'),
            'no_kk_sebelumnya'     => '',
            'input_umur'           => true,
            'umur_min'             => '',
            'umur_max'             => '',
            'umur'                 => 'tahun',
            'list_agama'           => $this->getRefTable('tweb_penduduk_agama'),
            'list_pendidikan'      => $this->getRefTable('tweb_penduduk_pendidikan'),
            'list_pendidikan_kk'   => $this->getRefTable('tweb_penduduk_pendidikan_kk'),
            'list_pekerjaan'       => $this->getRefTable('tweb_penduduk_pekerjaan'),
            'list_status_kawin'    => $this->getRefTable('tweb_penduduk_kawin'),
            'list_hubungan'        => $this->getRefTable('tweb_penduduk_hubungan'),
            'list_golongan_darah'  => $this->getRefTable('tweb_golongan_darah'),
            'list_cacat'           => $this->getRefTable('tweb_cacat'),
            'list_cara_kb'         => $this->getRefTable('tweb_cara_kb'),
            'list_status_dasar'    => $this->getRefTable('tweb_status_dasar'),
            'list_status_penduduk' => [
                '1' => 'TETAP',
                '2' => 'TIDAK TETAP / PENDATANG',
            ],
            'pekerjaan_id'         => '',
            'status'               => '',
            'hubungan'             => '',
            'agama'                => '',
            'pendidikan_sedang_id' => '',
            'pendidikan_kk_id'     => '',
            'status_penduduk'      => '',
            'cacat'                => '',
            'cara_kb_id'           => '',
            'golongan_darah'       => '',
            'status_dasar'         => '',
        ];
        $this->renderBlade('admin.penduduk.ajax_adv_search_form', $data);
    }

    /**
     * Modal Kumpulan NIK
     */
    public function search_kumpulan_nik()
    {
        $data = [
            'form_action' => site_url('penduduk'),
        ];
        $this->renderBlade('admin.penduduk.modal.kumpulan_nik', $data);
    }

    /**
     * AJAX Search NIK for Select2
     */
    public function list_nik_ajax()
    {
        $q = trim($this->input->get('q') ?: '');
        $page = (int)($this->input->get('page') ?: 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $results = [];
        $has_more = false;
        if ($this->db_real) {
            $this->db_real->select('p.id, p.nik, p.nama, p.tag_id_card, c.dusun, c.rw, c.rt')
                ->from('tweb_penduduk p')
                ->join('tweb_wil_clusterdesa c', 'p.id_cluster = c.id', 'left')
                ->where('p.status_dasar', 1);

            if ($q !== '') {
                $this->db_real->group_start()
                    ->like('p.nik', $q)
                    ->or_like('p.nama', $q)
                    ->or_like('p.tag_id_card', $q)
                    ->group_end();
            }

            $this->db_real->limit($limit + 1, $offset);
            $query = $this->db_real->get();
            $rows = $query ? $query->result_array() : [];

            $has_more = count($rows) > $limit;
            $items = array_slice($rows, 0, $limit);

            foreach ($items as $row) {
                $alamat = '';
                if (!empty($row['dusun']) || !empty($row['rw']) || !empty($row['rt'])) {
                    $alamat = ' - RT ' . ($row['rt'] ?: '-') . '/RW ' . ($row['rw'] ?: '-') . ' ' . ($row['dusun'] ?: '');
                }
                $results[] = [
                    'id'   => $row['nik'],
                    'text' => $row['nik'] . ' - ' . strtoupper($row['nama']) . $alamat
                ];
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'results'    => $results,
            'pagination' => ['more' => $has_more]
        ]);
        exit;
    }

    /**
     * Modal Program Bantuan
     */
    public function program_bantuan()
    {
        $program_bantuan = [];
        if ($this->db_real) {
            $qp = $this->db_real->where('sasaran', 1)->get('program');
            if ($qp) {
                $program_bantuan = $qp->result_array();
            }
        }

        $data = [
            'form_action'     => site_url('penduduk'),
            'program_bantuan' => $program_bantuan,
        ];
        $this->renderBlade('admin.penduduk.modal.program_bantuan', $data);
    }

    /**
     * Impor Penduduk
     */
    public function impor()
    {
        $data = [
            'form_action' => site_url('penduduk/proses_impor'),
            'desa'        => $this->desa,
            'module_name' => 'Penduduk',
        ];
        $this->renderBlade('admin.penduduk.impor', $data);
    }

    /**
     * Impor BIP
     */
    public function impor_bip()
    {
        $data = [
            'form_action' => site_url('penduduk/proses_impor_bip'),
            'desa'        => $this->desa,
            'module_name' => 'Penduduk',
        ];
        $this->renderBlade('admin.penduduk.impor_bip', $data);
    }

    /**
     * Delete Single Penduduk
     */
    public function delete($id = null)
    {
        if ($id && $this->db_real) {
            $this->db_real->where('id', (int)$id)->delete('tweb_penduduk');
            $_SESSION['flash_success'] = 'Data Penduduk berhasil dihapus';
        }
        redirect(site_url('penduduk'));
    }

    /**
     * Delete Multiple Penduduk
     */
    public function delete_all()
    {
        $id_cb = $this->input->post('id_cb');
        if (!empty($id_cb) && is_array($id_cb) && $this->db_real) {
            $this->db_real->where_in('id', array_map('intval', $id_cb))->delete('tweb_penduduk');
            $_SESSION['flash_success'] = count($id_cb) . ' Data Penduduk berhasil dihapus';
        }
        redirect(site_url('penduduk'));
    }

    /**
     * Form Peristiwa (1 = Lahir, 5 = Masuk, 2 = Mati, dll.)
     */
    public function form_peristiwa($peristiwa = 1, $id = 0)
    {
        $data = $this->prepareFormData((int)$peristiwa, (int)$id);
        $this->renderBlade('admin.penduduk.form', $data);
    }

    /**
     * Form Tambah / Edit Umum Penduduk
     */
    public function form($id = null)
    {
        $data = $this->prepareFormData(0, (int)$id);
        $this->renderBlade('admin.penduduk.form', $data);
    }

    /**
     * Edit Status Dasar Modal
     */
    public function ajax_edit_status_dasar($id = null)
    {
        if (!$id || !$this->db_real) {
            echo "Penduduk tidak ditemukan";
            return;
        }
        $q = $this->db_real->where('id', (int)$id)->get('tweb_penduduk');
        $penduduk = ($q && $q->num_rows() > 0) ? $q->row_array() : [];

        // Build list_status_dasar
        $list_status_dasar = [];
        $sd = $this->db_real->get('tweb_status_dasar');
        if ($sd && $sd->num_rows() > 0) {
            foreach ($sd->result_array() as $r) {
                $list_status_dasar[$r['id']] = $r['nama'];
            }
        }

        // Build list_ref_pindah
        $list_ref_pindah = [];
        $rp = $this->db_real->get('ref_pindah');
        if ($rp && $rp->num_rows() > 0) {
            foreach ($rp->result_array() as $r) {
                $list_ref_pindah[$r['id']] = $r['nama'];
            }
        }

        // log_status_dasar
        $log_status_dasar = [
            'tgl_peristiwa' => date('d-m-Y')
        ];
        $lq = $this->db_real->where('id_pend', (int)$id)->order_by('id', 'DESC')->limit(1)->get('log_perubahan_penduduk');
        if ($lq && $lq->num_rows() > 0) {
            $log_status_dasar = $lq->row_array();
            if (!empty($log_status_dasar['tgl_peristiwa'])) {
                $log_status_dasar['tgl_peristiwa'] = date('d-m-Y', strtotime($log_status_dasar['tgl_peristiwa']));
            }
        }

        $nikObj = (object)$penduduk;
        if (!isset($nikObj->id)) {
            $nikObj->id = (int)$id;
        }

        $data = [
            'form_action'      => site_url("penduduk/update_status_dasar/{$id}"),
            'ajax_anggota_url' => site_url("penduduk/ajax_anggota_keluarga"),
            'nik'              => $nikObj,
            'list_status_dasar'=> $list_status_dasar,
            'list_ref_pindah'  => $list_ref_pindah,
            'log_status_dasar' => $log_status_dasar,
            'sebab'            => [
                1 => 'Sakit biasa / tua',
                2 => 'Wabah Penyakit',
                3 => 'Kecelakaan',
                4 => 'Kriminalitas',
                5 => 'Bunuh Diri',
                6 => 'Lainnya'
            ],
            'penolong_mati'    => [
                1 => 'Dokter',
                2 => 'Tenaga Kesehatan',
                3 => 'Kepolisian',
                4 => 'Lainnya'
            ]
        ];
        $this->renderBlade('admin.penduduk.ajax_edit_status_dasar', $data);
    }

    /**
     * AJAX Anggota Keluarga untuk Pindah
     */
    public function ajax_anggota_keluarga($id = null)
    {
        header('Content-Type: application/json');
        if (!$id || !$this->db_real) {
            echo json_encode(['data' => []]);
            return;
        }

        $penduduk = $this->db_real->where('id', (int)$id)->get('tweb_penduduk')->row_array();
        if (!$penduduk || empty($penduduk['id_kk'])) {
            echo json_encode(['data' => []]);
            return;
        }

        $this->db_real->select('p.id, p.nik, p.nama, p.sex, h.nama as hubungan');
        $this->db_real->from('tweb_penduduk p');
        $this->db_real->join('tweb_penduduk_hubungan h', 'h.id = p.kk_level', 'left');
        $this->db_real->where('p.id_kk', $penduduk['id_kk']);
        $this->db_real->where('p.status_dasar', 1);
        $this->db_real->order_by('p.kk_level', 'ASC');
        $rows = $this->db_real->get()->result_array();

        $data = [];
        foreach ($rows as $r) {
            $data[] = [
                'id'            => $r['id'],
                'nik'           => $r['nik'],
                'nama'          => $r['nama'],
                'hubungan'      => $r['hubungan'] ?: 'ANGGOTA',
                'jenis_kelamin' => ($r['sex'] == 1 ? 'LAKI-LAKI' : 'PEREMPUAN')
            ];
        }

        echo json_encode(['data' => $data]);
    }

    /**
     * Insert New Penduduk
     */
    public function insert($peristiwa = 1)
    {
        if (!$this->db_real) {
            redirect(site_url('penduduk'));
            return;
        }

        try {
            $post = $this->input->post();
            $data = [
                'config_id'             => 1,
                'nik'                   => trim($post['nik'] ?? ''),
                'nama'                  => strtoupper(trim($post['nama'] ?? '')),
                'sex'                   => !empty($post['sex']) ? (int)$post['sex'] : null,
                'tempatlahir'           => trim($post['tempatlahir'] ?? ''),
                'tanggallahir'          => $this->parseDate($post['tanggallahir'] ?? ''),
                'agama_id'              => !empty($post['agama_id']) ? (int)$post['agama_id'] : null,
                'pendidikan_kk_id'      => !empty($post['pendidikan_kk_id']) ? (int)$post['pendidikan_kk_id'] : null,
                'pendidikan_sedang_id'  => !empty($post['pendidikan_sedang_id']) ? (int)$post['pendidikan_sedang_id'] : null,
                'pekerjaan_id'          => !empty($post['pekerjaan_id']) ? (int)$post['pekerjaan_id'] : null,
                'status_kawin'          => !empty($post['status_kawin']) ? (int)$post['status_kawin'] : null,
                'warganegara_id'        => !empty($post['warganegara_id']) ? (int)$post['warganegara_id'] : 1,
                'dokumen_pasport'       => trim($post['dokumen_pasport'] ?? ''),
                'dokumen_kitas'         => trim($post['dokumen_kitas'] ?? ''),
                'ayah_nik'              => trim($post['ayah_nik'] ?? ''),
                'nama_ayah'             => trim($post['nama_ayah'] ?? ''),
                'ibu_nik'               => trim($post['ibu_nik'] ?? ''),
                'nama_ibu'              => trim($post['nama_ibu'] ?? ''),
                'golongan_darah_id'     => !empty($post['golongan_darah_id']) ? $post['golongan_darah_id'] : null,
                'id_cluster'            => !empty($post['id_cluster']) ? (int)$post['id_cluster'] : null,
                'status'                => !empty($post['status']) ? (int)$post['status'] : 1,
                'alamat_sebelumnya'     => trim($post['alamat_sebelumnya'] ?? ''),
                'alamat_sekarang'       => trim($post['alamat_sekarang'] ?? ''),
                'status_dasar'          => 1,
                'cacat_id'              => !empty($post['cacat_id']) ? (int)$post['cacat_id'] : null,
                'sakit_menahun_id'      => !empty($post['sakit_menahun_id']) ? (int)$post['sakit_menahun_id'] : null,
                'akta_lahir'            => trim($post['akta_lahir'] ?? ''),
                'akta_perkawinan'       => trim($post['akta_perkawinan'] ?? ''),
                'tanggalperkawinan'     => $this->parseDate($post['tanggalperkawinan'] ?? ''),
                'akta_perceraian'       => trim($post['akta_perceraian'] ?? ''),
                'tanggalperceraian'     => $this->parseDate($post['tanggalperceraian'] ?? ''),
                'cara_kb_id'            => !empty($post['cara_kb_id']) ? (int)$post['cara_kb_id'] : null,
                'telepon'               => trim($post['telepon'] ?? ''),
                'email'                 => trim($post['email'] ?? ''),
                'ktp_el'                => !empty($post['ktp_el']) ? (int)$post['ktp_el'] : 1,
                'status_rekam'          => !empty($post['status_rekam']) ? (int)$post['status_rekam'] : 1,
                'tempat_cetak_ktp'      => trim($post['tempat_cetak_ktp'] ?? ''),
                'tanggal_cetak_ktp'     => $this->parseDate($post['tanggal_cetak_ktp'] ?? ''),
                'tag_id_card'           => trim($post['tag_id_card'] ?? ''),
                'id_asuransi'           => !empty($post['id_asuransi']) ? (int)$post['id_asuransi'] : null,
                'no_asuransi'           => trim($post['no_asuransi'] ?? ''),
                'bpjs_ketenagakerjaan'  => trim($post['bpjs_ketenagakerjaan'] ?? ''),
                'waktu_lahir'           => trim($post['waktu_lahir'] ?? ''),
                'tempat_dilahirkan'     => !empty($post['tempat_dilahirkan']) ? (int)$post['tempat_dilahirkan'] : null,
                'jenis_kelahiran'       => !empty($post['jenis_kelahiran']) ? (int)$post['jenis_kelahiran'] : null,
                'kelahiran_anak_ke'     => !empty($post['kelahiran_anak_ke']) ? (int)$post['kelahiran_anak_ke'] : null,
                'penolong_kelahiran'    => !empty($post['penolong_kelahiran']) ? (int)$post['penolong_kelahiran'] : null,
                'berat_lahir'           => !empty($post['berat_lahir']) ? (int)$post['berat_lahir'] : null,
                'panjang_lahir'         => trim($post['panjang_lahir'] ?? ''),
                'created_at'            => date('Y-m-d H:i:s'),
            ];

            if (!empty($_FILES['foto']['name'])) {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $newName = 'penduduk_' . time() . '_' . rand(100, 999) . '.' . $ext;
                $target = FCPATH . 'desa/upload/user_pict/' . $newName;
                if (@move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                    $data['foto'] = $newName;
                }
            }

            $this->db_real->insert('tweb_penduduk', $data);
            $_SESSION['flash_success'] = 'Data Penduduk berhasil ditambahkan!';
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Gagal menambahkan data: ' . $e->getMessage();
        }

        redirect(site_url('penduduk'));
    }

    /**
     * Update Existing Penduduk
     */
    public function update($id = null)
    {
        if (!$id || !$this->db_real) {
            redirect(site_url('penduduk'));
            return;
        }

        try {
            $post = $this->input->post();
            $data = [
                'nik'                   => trim($post['nik'] ?? ''),
                'nama'                  => strtoupper(trim($post['nama'] ?? '')),
                'sex'                   => !empty($post['sex']) ? (int)$post['sex'] : null,
                'tempatlahir'           => trim($post['tempatlahir'] ?? ''),
                'tanggallahir'          => $this->parseDate($post['tanggallahir'] ?? ''),
                'agama_id'              => !empty($post['agama_id']) ? (int)$post['agama_id'] : null,
                'pendidikan_kk_id'      => !empty($post['pendidikan_kk_id']) ? (int)$post['pendidikan_kk_id'] : null,
                'pendidikan_sedang_id'  => !empty($post['pendidikan_sedang_id']) ? (int)$post['pendidikan_sedang_id'] : null,
                'pekerjaan_id'          => !empty($post['pekerjaan_id']) ? (int)$post['pekerjaan_id'] : null,
                'status_kawin'          => !empty($post['status_kawin']) ? (int)$post['status_kawin'] : null,
                'warganegara_id'        => !empty($post['warganegara_id']) ? (int)$post['warganegara_id'] : 1,
                'dokumen_pasport'       => trim($post['dokumen_pasport'] ?? ''),
                'dokumen_kitas'         => trim($post['dokumen_kitas'] ?? ''),
                'ayah_nik'              => trim($post['ayah_nik'] ?? ''),
                'nama_ayah'             => trim($post['nama_ayah'] ?? ''),
                'ibu_nik'               => trim($post['ibu_nik'] ?? ''),
                'nama_ibu'              => trim($post['nama_ibu'] ?? ''),
                'golongan_darah_id'     => !empty($post['golongan_darah_id']) ? $post['golongan_darah_id'] : null,
                'id_cluster'            => !empty($post['id_cluster']) ? (int)$post['id_cluster'] : null,
                'status'                => !empty($post['status']) ? (int)$post['status'] : 1,
                'alamat_sebelumnya'     => trim($post['alamat_sebelumnya'] ?? ''),
                'alamat_sekarang'       => trim($post['alamat_sekarang'] ?? ''),
                'cacat_id'              => !empty($post['cacat_id']) ? (int)$post['cacat_id'] : null,
                'sakit_menahun_id'      => !empty($post['sakit_menahun_id']) ? (int)$post['sakit_menahun_id'] : null,
                'akta_lahir'            => trim($post['akta_lahir'] ?? ''),
                'akta_perkawinan'       => trim($post['akta_perkawinan'] ?? ''),
                'tanggalperkawinan'     => $this->parseDate($post['tanggalperkawinan'] ?? ''),
                'akta_perceraian'       => trim($post['akta_perceraian'] ?? ''),
                'tanggalperceraian'     => $this->parseDate($post['tanggalperceraian'] ?? ''),
                'cara_kb_id'            => !empty($post['cara_kb_id']) ? (int)$post['cara_kb_id'] : null,
                'telepon'               => trim($post['telepon'] ?? ''),
                'email'                 => trim($post['email'] ?? ''),
                'ktp_el'                => !empty($post['ktp_el']) ? (int)$post['ktp_el'] : 1,
                'status_rekam'          => !empty($post['status_rekam']) ? (int)$post['status_rekam'] : 1,
                'tempat_cetak_ktp'      => trim($post['tempat_cetak_ktp'] ?? ''),
                'tanggal_cetak_ktp'     => $this->parseDate($post['tanggal_cetak_ktp'] ?? ''),
                'tag_id_card'           => trim($post['tag_id_card'] ?? ''),
                'id_asuransi'           => !empty($post['id_asuransi']) ? (int)$post['id_asuransi'] : null,
                'no_asuransi'           => trim($post['no_asuransi'] ?? ''),
                'bpjs_ketenagakerjaan'  => trim($post['bpjs_ketenagakerjaan'] ?? ''),
                'waktu_lahir'           => trim($post['waktu_lahir'] ?? ''),
                'tempat_dilahirkan'     => !empty($post['tempat_dilahirkan']) ? (int)$post['tempat_dilahirkan'] : null,
                'jenis_kelahiran'       => !empty($post['jenis_kelahiran']) ? (int)$post['jenis_kelahiran'] : null,
                'kelahiran_anak_ke'     => !empty($post['kelahiran_anak_ke']) ? (int)$post['kelahiran_anak_ke'] : null,
                'penolong_kelahiran'    => !empty($post['penolong_kelahiran']) ? (int)$post['penolong_kelahiran'] : null,
                'berat_lahir'           => !empty($post['berat_lahir']) ? (int)$post['berat_lahir'] : null,
                'panjang_lahir'         => trim($post['panjang_lahir'] ?? ''),
                'updated_at'            => date('Y-m-d H:i:s'),
            ];

            if (!empty($_FILES['foto']['name'])) {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $newName = 'penduduk_' . time() . '_' . rand(100, 999) . '.' . $ext;
                $target = FCPATH . 'desa/upload/user_pict/' . $newName;
                if (@move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                    $data['foto'] = $newName;
                }
            }

            $this->db_real->where('id', (int)$id)->update('tweb_penduduk', $data);
            $_SESSION['flash_success'] = 'Data Penduduk berhasil diperbarui!';
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Gagal memperbarui data: ' . $e->getMessage();
        }

        redirect(site_url('penduduk'));
    }

    /**
     * Prepare Form Data for Form Views
     */
    private function prepareFormData($jenis_peristiwa = 1, $id = 0)
    {
        $penduduk = [
            'id'                       => $id,
            'nama'                     => '',
            'nik'                      => '',
            'id_sex'                   => '',
            'sex'                      => '',
            'tempatlahir'              => '',
            'tanggallahir'             => '',
            'waktu_lahir'              => '',
            'tempat_dilahirkan'        => '',
            'jenis_kelahiran'          => '',
            'kelahiran_anak_ke'        => '',
            'penolong_kelahiran'       => '',
            'berat_lahir'              => '',
            'panjang_lahir'            => '',
            'agama_id'                 => '',
            'pendidikan_kk_id'         => '',
            'pendidikan_sedang_id'     => '',
            'pekerjaan_id'             => '',
            'status_kawin'             => '',
            'warganegara_id'           => '1',
            'dokumen_pasport'          => '',
            'tanggal_akhir_paspor'     => '',
            'tanggal_akhir_pasport'    => '',
            'dokumen_kitas'            => '',
            'ayah_nik'                 => '',
            'nama_ayah'                => '',
            'ibu_nik'                  => '',
            'nama_ibu'                 => '',
            'lokasi'                   => '',
            'foto'                     => '',
            'id_cluster'               => '',
            'dusun'                    => '',
            'rw'                       => '',
            'rt'                       => '',
            'alamat_sekarang'          => '',
            'alamat_sebelumnya'        => '',
            'telepon'                  => '',
            'email'                    => '',
            'id_status'                => '1',
            'status'                   => '1',
            'status_dasar_id'          => '1',
            'status_dasar'             => '1',
            'tgl_peristiwa'            => '',
            'tgl_lapor'                => date('Y-m-d'),
            'no_kk_sebelumnya'         => '',
            'kk_level'                 => '',
            'tag_id_card'              => '',
            'tempat_cetak_ktp'         => '',
            'tanggal_cetak_ktp'        => '',
            'wajib_ktp'                => '',
            'ktp_el'                   => '1',
            'status_rekam'             => '1',
            'no_kk'                    => '',
            'id_kk'                    => '',
            'maksud_tujuan_kedatangan' => '',
            'akta_lahir'               => '',
            'akta_perkawinan'          => '',
            'tanggalperkawinan'        => '',
            'akta_perceraian'          => '',
            'tanggalperceraian'        => '',
            'golongan_darah_id'        => '',
            'cacat_id'                 => '',
            'sakit_menahun_id'         => '',
            'cara_kb_id'               => '',
            'status_hamil'             => '',
            'hamil'                    => '',
            'id_asuransi'              => '',
            'no_asuransi'              => '',
            'bpjs_ketenagakerjaan'     => '',
            'bahasa_id'                => '',
            'pekerja_migran_id'        => '',
            'pekerja_migran'           => '',
            'adat'                     => '',
            'suku'                     => '',
            'marga'                    => '',
            'wilayah'                  => ['dusun' => '', 'rw' => '', 'rt' => ''],
        ];

        if ($id && $this->db_real) {
            $q = $this->db_real->where('id', (int)$id)->get('tweb_penduduk');
            if ($q && $q->num_rows() > 0) {
                $row = $q->row_array();
                $penduduk = array_merge($penduduk, $row);
                $penduduk['id_sex'] = $row['sex'] ?? '';
                $penduduk['id_status'] = $row['status'] ?? '1';
                $penduduk['status_dasar_id'] = $row['status_dasar'] ?? '1';

                // Get cluster info
                if (!empty($row['id_cluster'])) {
                    $qc = $this->db_real->where('id', (int)$row['id_cluster'])->get('tweb_wil_clusterdesa');
                    if ($qc && $qc->num_rows() > 0) {
                        $cl = $qc->row();
                        $penduduk['wilayah'] = [
                            'dusun' => $cl->dusun ?? '',
                            'rw'    => $cl->rw ?? '',
                            'rt'    => $cl->rt ?? ''
                        ];
                    }
                }
            }
        }

        // Build wilayah cluster hierarchy
        $wilayah = [];
        if ($this->db_real) {
            $clusters = $this->db_real->order_by('dusun', 'ASC')->order_by('rw', 'ASC')->order_by('rt', 'ASC')->get('tweb_wil_clusterdesa')->result();
            foreach ($clusters as $c) {
                $d = $c->dusun ?: '-';
                $r = $c->rw ?: '-';
                if (!isset($wilayah[$d])) { $wilayah[$d] = []; }
                if (!isset($wilayah[$d][$r])) { $wilayah[$d][$r] = []; }
                $wilayah[$d][$r][] = $c;
            }
        }

        return [
            'module_name'            => 'Penduduk',
            'form_action'            => $id ? site_url("penduduk/update/{$id}") : site_url("penduduk/insert/{$jenis_peristiwa}"),
            'jenis_peristiwa'        => $jenis_peristiwa,
            'penduduk'               => $penduduk,
            'kk_baru'                => false,
            'cek_nik'                => '1',
            'is_historical'          => false,
            'ktp_el'                 => [
                1 => 'BELUM KTP-EL',
                2 => 'KTP-EL',
                3 => 'BELUM REKAM'
            ],
            'status_rekam'           => [
                1 => 'BELUM WAJIB',
                2 => 'BELUM REKAM',
                3 => 'SUDAH REKAM',
                4 => 'CARD ISSUED',
                5 => 'PRINT READY RECORD',
                6 => 'CARD PRINTED',
                7 => 'SENT FOR CARD PRINTING',
                8 => 'DUPLICATE RECORD'
            ],
            'hubungan'               => $this->getRefTable('tweb_penduduk_hubungan'),
            'keluarga'               => ['status_dasar' => '1'],
            'status_penduduk'        => [
                1 => 'TETAP',
                2 => 'TIDAK TETAP',
                3 => 'PENDATANG'
            ],
            'tempat_dilahirkan'      => [
                1 => 'RS/RB',
                2 => 'Puskesmas',
                3 => 'Polindes',
                4 => 'Rumah Bersalin',
                5 => 'Rumah',
                6 => 'Lainnya'
            ],
            'jenis_kelahiran'        => [
                1 => 'Tunggal',
                2 => 'Kembar 2',
                3 => 'Kembar 3',
                4 => 'Kembar 4'
            ],
            'penolong_kelahiran'     => [
                1 => 'Dokter',
                2 => 'Bidan/Perawat',
                3 => 'Dukun',
                4 => 'Lainnya'
            ],
            'pekerja_migran_penduduk'=> [],
            'adat_penduduk'          => [],
            'suku_penduduk'          => [],
            'suku'                   => [],
            'marga_penduduk'         => [],
            'marga'                  => [],
            'wilayah'                => $wilayah,
            'kawin'                  => $this->getRefTable('tweb_penduduk_kawin'),
            'sakit_menahun'          => $this->getRefTable('tweb_sakit_menahun'),
            'kehamilan'              => [1 => 'Tidak Hamil', 2 => 'Hamil'],
            'pilihan_asuransi'       => $this->getRefTable('tweb_penduduk_asuransi'),
            'bahasa'                 => $this->getRefTable('tweb_penduduk_bahasa'),
            'sebab'                  => [
                1 => 'Sakit biasa / tua',
                2 => 'Wabah Penyakit',
                3 => 'Kecelakaan',
                4 => 'Kriminalitas',
                5 => 'Bunuh Diri',
                6 => 'Lainnya'
            ],
            'penolong_mati'          => [
                1 => 'Dokter',
                2 => 'Tenaga Kesehatan',
                3 => 'Kepolisian',
                4 => 'Lainnya'
            ],
            'consts'                 => [],
            'data_ayah'              => [],
            'data_ibu'               => [],
            'id_kk'                  => 0,
            'desa'                   => $this->desa,
        ];
    }

    /**
     * Map Lokasi Tempat Tinggal Penduduk
     */
    public function ajax_penduduk_maps($id = null, $edit = 0)
    {
        if (!$id || !$this->db_real) {
            redirect(site_url('penduduk'));
            return;
        }

        $penduduk = [];
        $q = $this->db_real->where('id', (int)$id)->get('tweb_penduduk');
        if ($q && $q->num_rows() > 0) {
            $penduduk = $q->row_array();
            $penduduk['wilayah'] = (object)[
                'rt'    => '-',
                'rw'    => '-',
                'dusun' => '-'
            ];
            if (!empty($penduduk['id_cluster'])) {
                $qc = $this->db_real->where('id', (int)$penduduk['id_cluster'])->get('tweb_wil_clusterdesa');
                if ($qc && $qc->num_rows() > 0) {
                    $cl = $qc->row();
                    $penduduk['wilayah'] = (object)[
                        'rt'    => $cl->rt ?: '-',
                        'rw'    => $cl->rw ?: '-',
                        'dusun' => $cl->dusun ?: '-'
                    ];
                }
            }
        }

        // Ambil data poligon GIS wilayah dusun, rw, rt
        $dusun_gis = $this->db_real->where('rt', '0')->where('rw', '0')->where("path IS NOT NULL AND path != ''")->get('tweb_wil_clusterdesa')->result_array();
        $rw_gis = $this->db_real->where('rt', '0')->where('rw !=', '0')->where("path IS NOT NULL AND path != ''")->get('tweb_wil_clusterdesa')->result_array();
        $rt_gis = $this->db_real->where('rt !=', '0')->where("path IS NOT NULL AND path != ''")->get('tweb_wil_clusterdesa')->result_array();

        $data = [
            'id'          => (int)$id,
            'edit'        => (string)$edit,
            'penduduk'    => $penduduk,
            'form_action' => site_url("penduduk/update_maps/{$id}/{$edit}"),
            'desa'        => $this->desa,
            'wil_atas'    => $this->desa,
            'dusun_gis'   => $dusun_gis,
            'rw_gis'      => $rw_gis,
            'rt_gis'      => $rt_gis,
        ];

        $this->renderBlade('admin.penduduk.ajax_penduduk_maps', $data);
    }

    /**
     * Update Koordinat Lokasi Tempat Tinggal
     */
    public function update_maps($id = null, $edit = 0)
    {
        if ($id && $this->db_real) {
            $lat = $this->input->post('lat');
            $lng = $this->input->post('lng');
            $this->db_real->where('id', (int)$id)->update('tweb_penduduk', [
                'lat' => $lat,
                'lng' => $lng
            ]);
            $_SESSION['flash_success'] = 'Lokasi tempat tinggal berhasil diperbarui';
        }
        if ($edit == '1') {
            redirect(site_url("penduduk/form/{$id}"));
        } else {
            redirect(site_url("penduduk/ajax_penduduk_maps/{$id}/0"));
        }
    }

    /**
     * Update Status Dasar Action
     */
    public function update_status_dasar($id = null)
    {
        if ($id && $this->db_real) {
            $post = $this->input->post();
            $status_dasar = (int)($post['status_dasar'] ?? 1);
            $tgl_peristiwa = $this->parseDate($post['tgl_peristiwa'] ?? '') ?: date('Y-m-d');
            $catatan = trim($post['catatan'] ?? '');

            $update = [
                'status_dasar' => $status_dasar
            ];
            $this->db_real->where('id', (int)$id)->update('tweb_penduduk', $update);

            try {
                $this->db_real->insert('log_penduduk', [
                    'id_pend'        => (int)$id,
                    'kode_peristiwa' => $status_dasar,
                    'tgl_peristiwa'  => $tgl_peristiwa,
                    'catatan'        => $catatan,
                    'id_user'        => 1,
                    'tgl_lapor'      => date('Y-m-d H:i:s')
                ]);
            } catch (\Throwable $e) {}

            $_SESSION['flash_success'] = 'Status dasar berhasil diperbarui';
        }
        redirect(site_url('penduduk'));
    }

    /**
     * Dokumen Penduduk List
     */
    public function dokumen($id = null)
    {
        if (!$id || !$this->db_real) {
            redirect(site_url('penduduk'));
            return;
        }

        $penduduk = null;
        $q = $this->db_real->where('id', (int)$id)->get('tweb_penduduk');
        if ($q && $q->num_rows() > 0) {
            $penduduk = $q->row();
            $penduduk->wilayah = (object)[
                'rt'    => '-',
                'rw'    => '-',
                'dusun' => '-'
            ];
            if (!empty($penduduk->id_cluster)) {
                $qc = $this->db_real->where('id', (int)$penduduk->id_cluster)->get('tweb_wil_clusterdesa');
                if ($qc && $qc->num_rows() > 0) {
                    $cl = $qc->row();
                    $penduduk->wilayah = (object)[
                        'rt'    => $cl->rt ?: '-',
                        'rw'    => $cl->rw ?: '-',
                        'dusun' => $cl->dusun ?: '-'
                    ];
                }
            }
        }

        $list_dokumen = [];
        try {
            $qd = $this->db_real->select('d.*, s.ref_syarat_nama as nama_syarat')
                ->from('dokumen d')
                ->join('ref_syarat_surat s', 'd.id_syarat = s.ref_syarat_id', 'left')
                ->where('d.id_pend', (int)$id)
                ->where('d.deleted', 0)
                ->order_by('d.id', 'DESC')
                ->get();
            if ($qd && $qd->num_rows() > 0) {
                $list_dokumen = $qd->result_array();
            }
        } catch (\Throwable $e) {}

        $data = [
            'module_name'  => 'Penduduk',
            'penduduk'     => $penduduk,
            'list_dokumen' => $list_dokumen,
            'desa'         => $this->desa,
        ];

        $this->renderBlade('admin.penduduk.dokumen.index', $data);
    }

    /**
     * Dokumen Form Modal
     */
    public function dokumen_form($id = null, $id_dokumen = 0)
    {
        if (!$id || !$this->db_real) {
            echo "Penduduk tidak ditemukan";
            return;
        }

        $penduduk = [];
        $q = $this->db_real->where('id', (int)$id)->get('tweb_penduduk');
        if ($q && $q->num_rows() > 0) {
            $penduduk = $q->row_array();
        }

        $dokumen = [
            'id'        => 0,
            'nama'      => '',
            'id_syarat' => '',
            'dok_warga' => 0,
            'satuan'    => '',
        ];
        if ($id_dokumen) {
            $qd = $this->db_real->where('id', (int)$id_dokumen)->get('dokumen');
            if ($qd && $qd->num_rows() > 0) {
                $dokumen = $qd->row_array();
            }
        }

        $jenis_syarat = [];
        try {
            $qs = $this->db_real->order_by('ref_syarat_nama', 'ASC')->get('ref_syarat_surat');
            if ($qs && $qs->num_rows() > 0) {
                $jenis_syarat = $qs->result_array();
            }
        } catch (\Throwable $e) {}

        $kk = [];
        if (!empty($penduduk['id_kk'])) {
            $qkk = $this->db_real->where('id_kk', (int)$penduduk['id_kk'])->get('tweb_penduduk');
            if ($qkk && $qkk->num_rows() > 0) {
                $kk = $qkk->result_array();
            }
        }

        $data = [
            'form_action'        => $id_dokumen ? site_url("penduduk/dokumen_update/{$id_dokumen}") : site_url("penduduk/dokumen_insert"),
            'penduduk'           => $penduduk,
            'dokumen'            => $dokumen,
            'jenis_syarat_surat' => $jenis_syarat,
            'kk'                 => $kk,
        ];

        $this->renderBlade('admin.penduduk.dokumen.form', $data);
    }

    /**
     * Dokumen Insert Action
     */
    public function dokumen_insert()
    {
        $id_pend = (int)$this->input->post('id_pend');
        if (!$id_pend || !$this->db_real) {
            redirect(site_url('penduduk'));
            return;
        }

        try {
            $nama = trim($this->input->post('nama') ?? '');
            $id_syarat = !empty($this->input->post('id_syarat')) ? (int)$this->input->post('id_syarat') : null;
            $dok_warga = !empty($this->input->post('dok_warga')) ? 1 : 0;
            $satuan = '';

            if (!empty($_FILES['satuan']['name'])) {
                $ext = pathinfo($_FILES['satuan']['name'], PATHINFO_EXTENSION);
                $satuan = 'dok_' . $id_pend . '_' . time() . '.' . $ext;
                $targetDir = FCPATH . 'desa/upload/dokumen/';
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0777, true);
                }
                @move_uploaded_file($_FILES['satuan']['tmp_name'], $targetDir . $satuan);
            }

            $now = date('Y-m-d H:i:s');
            $config_id = !empty($this->desa['id']) ? (int)$this->desa['id'] : 1;
            $data = [
                'config_id'  => $config_id,
                'id_pend'    => $id_pend,
                'nama'       => $nama,
                'id_syarat'  => $id_syarat,
                'dok_warga'  => $dok_warga,
                'satuan'     => $satuan,
                'kategori'   => 1,
                'enabled'    => 1,
                'deleted'    => 0,
                'tgl_upload' => $now,
                'created_at' => $now,
                'attr'       => '{}',
            ];
            $this->db_real->insert('dokumen', $data);

            // Optional multi-apply to KK members
            $anggota_kk = $this->input->post('anggota_kk');
            if (!empty($anggota_kk) && is_array($anggota_kk)) {
                foreach ($anggota_kk as $id_member) {
                    $id_member = (int)$id_member;
                    if ($id_member && $id_member !== $id_pend) {
                        $memberData = $data;
                        $memberData['id_pend'] = $id_member;
                        $this->db_real->insert('dokumen', $memberData);
                    }
                }
            }

            $_SESSION['flash_success'] = 'Dokumen berhasil ditambahkan';
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Gagal menambahkan dokumen: ' . $e->getMessage();
        }

        redirect(site_url("penduduk/dokumen/{$id_pend}"));
    }

    /**
     * Dokumen Update Action
     */
    public function dokumen_update($id = null)
    {
        $id_pend = (int)$this->input->post('id_pend');
        if (!$id || !$this->db_real) {
            redirect(site_url('penduduk'));
            return;
        }

        try {
            $nama = trim($this->input->post('nama') ?? '');
            $id_syarat = !empty($this->input->post('id_syarat')) ? (int)$this->input->post('id_syarat') : null;
            $dok_warga = !empty($this->input->post('dok_warga')) ? 1 : 0;

            $data = [
                'nama'       => $nama,
                'id_syarat'  => $id_syarat,
                'dok_warga'  => $dok_warga,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if (!empty($_FILES['satuan']['name'])) {
                $ext = pathinfo($_FILES['satuan']['name'], PATHINFO_EXTENSION);
                $satuan = 'dok_' . $id_pend . '_' . time() . '.' . $ext;
                $targetDir = FCPATH . 'desa/upload/dokumen/';
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0777, true);
                }
                if (@move_uploaded_file($_FILES['satuan']['tmp_name'], $targetDir . $satuan)) {
                    $data['satuan'] = $satuan;
                }
            }

            $this->db_real->where('id', (int)$id)->update('dokumen', $data);
            $_SESSION['flash_success'] = 'Dokumen berhasil diperbarui';
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Gagal memperbarui dokumen: ' . $e->getMessage();
        }

        redirect(site_url("penduduk/dokumen/{$id_pend}"));
    }

    /**
     * Delete Dokumen Action
     */
    public function delete_dokumen($id_pend = null, $id_dokumen = null)
    {
        if ($this->db_real) {
            $id_cb = $this->input->post('id_cb');
            if (!empty($id_cb) && is_array($id_cb)) {
                $this->db_real->where_in('id', array_map('intval', $id_cb))->update('dokumen', ['deleted' => 1]);
                $_SESSION['flash_success'] = count($id_cb) . ' Dokumen berhasil dihapus';
            } elseif ($id_dokumen) {
                $this->db_real->where('id', (int)$id_dokumen)->update('dokumen', ['deleted' => 1]);
                $_SESSION['flash_success'] = 'Dokumen berhasil dihapus';
            }
        }
        if ($id_pend) {
            redirect(site_url("penduduk/dokumen/{$id_pend}"));
        } else {
            redirect(site_url('penduduk'));
        }
    }

    /**
     * Parse date from d-m-Y to Y-m-d
     */
    private function parseDate($dateStr)
    {
        if (empty($dateStr)) return null;
        $dateStr = trim($dateStr);
        if (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $dateStr, $m)) {
            return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStr)) {
            return $dateStr;
        }
        return null;
    }

    /**
     * Helper to get dropdown array from reference table
     */
    private function getRefTable($tableName)
    {
        $result = [];
        if (!$this->db_real) return $result;
        try {
            $q = $this->db_real->order_by('id', 'ASC')->get($tableName);
            if ($q && $q->num_rows() > 0) {
                foreach ($q->result() as $row) {
                    $result[$row->id] = $row->nama ?? ($row->nama_cacat ?? ($row->cara_kb ?? $row->id));
                }
            }
        } catch (\Throwable $e) {}
        return $result;
    }

    /**
     * Ekspor data penduduk ke file Excel (.xlsx)
     * @param int $huruf  Jika 1, ekspor dalam format huruf (nama kolom + data teks saja)
     */
    public function ekspor($huruf = 0)
    {
        if (!$this->db_real) {
            redirect(site_url('penduduk'));
            return;
        }

        $huruf = (int) $huruf;

        // Query all active penduduk
        $sql = "SELECT p.nik, p.nama, p.no_kk, p.sex, p.tempatlahir, p.tanggallahir,
                    p.agama_id, p.pendidikan_kk_id, p.pekerjaan_id, p.status_kawin,
                    p.kk_level, p.nama_ayah, p.nama_ibu, p.alamat_sekarang, p.status_dasar,
                    p.status, p.golongan_darah_id, p.id_cluster, p.created_at,
                    k.no_kk as no_kk_keluarga, k.alamat as alamat_kk,
                    w.dusun, w.rw, w.rt,
                    pd.nama as pendidikan_nama,
                    pk.nama as pekerjaan_nama,
                    sp.nama as kawin_nama,
                    h.nama as hubungan_nama,
                    ag.nama as agama_nama,
                    gd.nama as golongan_darah_nama,
                    CASE p.status_dasar
                        WHEN 1 THEN 'HIDUP'
                        WHEN 2 THEN 'MATI'
                        WHEN 3 THEN 'PINDAH'
                        WHEN 4 THEN 'HILANG'
                        WHEN 9 THEN 'PERGI'
                        ELSE 'HIDUP'
                    END as status_dasar_nama
                FROM tweb_penduduk p
                LEFT JOIN tweb_wil_clusterdesa w ON p.id_cluster = w.id
                LEFT JOIN tweb_keluarga k ON p.id_kk = k.id
                LEFT JOIN tweb_penduduk_pendidikan_kk pd ON p.pendidikan_kk_id = pd.id
                LEFT JOIN tweb_penduduk_pekerjaan pk ON p.pekerjaan_id = pk.id
                LEFT JOIN tweb_penduduk_kawin sp ON p.status_kawin = sp.id
                LEFT JOIN tweb_penduduk_hubungan h ON p.kk_level = h.id
                LEFT JOIN tweb_penduduk_agama ag ON p.agama_id = ag.id
                LEFT JOIN tweb_golongan_darah gd ON p.golongan_darah_id = gd.id
                WHERE p.status_dasar = 1
                ORDER BY w.dusun ASC, w.rw ASC, w.rt ASC, k.no_kk ASC, p.kk_level ASC";

        $result = $this->db_real->query($sql);
        $rows = ($result && $result->num_rows() > 0) ? $result->result() : [];

        // Use OpenSpout for streaming XLSX
        $writerClass = class_exists('\\OpenSpout\\Writer\\XLSX\\Writer')
            ? '\\OpenSpout\\Writer\\XLSX\\Writer'
            : null;

        if (!$writerClass) {
            // Fallback: CSV-style download
            $this->_ekspor_csv($rows, $huruf);
            return;
        }

        $fileName = 'data_penduduk_' . ($huruf ? 'huruf_' : '') . date('Ymd_His') . '.xlsx';

        // Write to temp file first (more reliable under Apache/CI)
        $tempFile = tempnam(sys_get_temp_dir(), 'ekspor_') . '.xlsx';

        $writer = new $writerClass();
        $writer->openToFile($tempFile);

        // Header row
        $headers = ['No', 'NIK', 'Nama', 'No KK', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir',
                     'Umur', 'Agama', 'Pendidikan', 'Pekerjaan', 'Status Kawin', 'Hub. Keluarga',
                     'Nama Ayah', 'Nama Ibu', 'Alamat', 'Dusun', 'RW', 'RT',
                     'Gol. Darah', 'Status Dasar'];

        $rowClass = '\\OpenSpout\\Common\\Entity\\Row';
        $cellClass = '\\OpenSpout\\Common\\Entity\\Cell';

        $headerCells = array_map(fn($h) => $cellClass::fromValue($h), $headers);
        $writer->addRow(new $rowClass($headerCells));

        // Data rows
        $no = 0;
        foreach ($rows as $row) {
            $no++;
            $umur = '-';
            if ($row->tanggallahir) {
                try {
                    $birth = new \DateTime($row->tanggallahir);
                    $now = new \DateTime();
                    $umur = $now->diff($birth)->y;
                } catch (\Throwable $e) { $umur = '-'; }
            }

            $jk = ($row->sex == 1) ? 'Laki-Laki' : (($row->sex == 2) ? 'Perempuan' : '-');
            $tglLahir = $row->tanggallahir ? date('d/m/Y', strtotime($row->tanggallahir)) : '-';
            $alamat = $row->alamat_kk ?: $row->alamat_sekarang ?: '-';

            $rowData = [
                $no,
                $row->nik ?: '-',
                $row->nama ?: '-',
                $row->no_kk_keluarga ?: $row->no_kk ?: '-',
                $jk,
                $row->tempatlahir ?: '-',
                $tglLahir,
                (string) $umur,
                $row->agama_nama ?: '-',
                $row->pendidikan_nama ?: '-',
                $row->pekerjaan_nama ?: '-',
                $row->kawin_nama ?: '-',
                $row->hubungan_nama ?: '-',
                $row->nama_ayah ?: '-',
                $row->nama_ibu ?: '-',
                $alamat,
                $row->dusun ?: '-',
                $row->rw ?: '-',
                $row->rt ?: '-',
                $row->golongan_darah_nama ?: '-',
                $row->status_dasar_nama ?: 'HIDUP'
            ];

            $cells = array_map(fn($v) => $cellClass::fromValue((string) $v), $rowData);
            $writer->addRow(new $rowClass($cells));
        }

        $writer->close();

        // Clear all output buffers from CI/Laravel framework
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // Stream the file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$fileName}\"");
        header('Content-Length: ' . filesize($tempFile));
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        readfile($tempFile);
        @unlink($tempFile);
        exit;
    }

    /**
     * Fallback CSV export when OpenSpout is not available
     */
    private function _ekspor_csv($rows, $huruf = 0)
    {
        $fileName = 'data_penduduk_' . ($huruf ? 'huruf_' : '') . date('Ymd_His') . '.csv';

        // Clear all output buffers
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename=\"{$fileName}\"");
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM for UTF-8

        fputcsv($output, ['No', 'NIK', 'Nama', 'No KK', 'Jenis Kelamin', 'Tempat Lahir',
                           'Tanggal Lahir', 'Umur', 'Agama', 'Pendidikan', 'Pekerjaan',
                           'Status Kawin', 'Hub. Keluarga', 'Nama Ayah', 'Nama Ibu',
                           'Alamat', 'Dusun', 'RW', 'RT', 'Gol. Darah', 'Status Dasar']);

        $no = 0;
        foreach ($rows as $row) {
            $no++;
            $umur = '-';
            if ($row->tanggallahir) {
                try {
                    $birth = new \DateTime($row->tanggallahir);
                    $umur = (new \DateTime())->diff($birth)->y;
                } catch (\Throwable $e) {}
            }
            $jk = ($row->sex == 1) ? 'Laki-Laki' : (($row->sex == 2) ? 'Perempuan' : '-');

            fputcsv($output, [
                $no, $row->nik, $row->nama, $row->no_kk_keluarga ?? $row->no_kk ?? '-',
                $jk, $row->tempatlahir ?? '-',
                $row->tanggallahir ? date('d/m/Y', strtotime($row->tanggallahir)) : '-',
                $umur, $row->agama_nama ?? '-', $row->pendidikan_nama ?? '-',
                $row->pekerjaan_nama ?? '-', $row->kawin_nama ?? '-',
                $row->hubungan_nama ?? '-', $row->nama_ayah ?? '-', $row->nama_ibu ?? '-',
                $row->alamat_kk ?? $row->alamat_sekarang ?? '-',
                $row->dusun ?? '-', $row->rw ?? '-', $row->rt ?? '-',
                $row->golongan_darah_nama ?? '-', $row->status_dasar_nama ?? 'HIDUP'
            ]);
        }

        fclose($output);
        exit;
    }
}

