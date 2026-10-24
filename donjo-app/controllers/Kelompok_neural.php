<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * XOSID - Neural Kelompok Controller
 * Bypasses ionCube-encrypted Admin_Controller::render() pipeline.
 */
class Kelompok_neural extends CI_Controller
{
    private $db_real = null;
    private $desa = [];
    public $controller = 'kelompok';

    public function __construct()
    {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE) { @session_start(); }

        $this->session = new class {
            public function userdata($key) { return $_SESSION[$key] ?? null; }
            public function set_userdata($key, $val) { $_SESSION[$key] = $val; }
            public function flashdata($key) { return null; }
            public function set_flashdata($key, $val) {}
            public function all_userdata() { return $_SESSION ?? []; }
        };

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
                return [];
            }
        };

        try { $this->db_real = $this->load->database('default', TRUE); } catch (\Throwable $e) {}
        try {
            if ($this->db_real) {
                $q = $this->db_real->get('config');
                $this->desa = ($q && $q->num_rows() > 0) ? $q->row_array() : [];
            }
        } catch (\Throwable $e) {}
    }

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
                $output = $app->make('view')->make($viewName, $data)->render();
            }
        } catch (\Throwable $e) {
            @file_put_contents(FCPATH . 'storage/logs/xosid_neural_error.log',
                "[" . date('Y-m-d H:i:s') . "] KELOMPOK RENDER: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n", FILE_APPEND);
        }
        if ($output) { echo $output; }
        else { echo '<div style="color:red;padding:20px;">Error rendering view: ' . htmlspecialchars($viewName) . '</div>'; }
    }

    public function index()
    {
        // Fetch kelompok_master list
        $list_master = [];
        if ($this->db_real) {
            try {
                $q = $this->db_real->order_by('kelompok', 'ASC')->get('kelompok_master');
                if ($q && $q->num_rows() > 0) {
                    $list_master = $q->result();
                }
            } catch (\Throwable $e) {}
        }

        $data = [
            'desa'                  => $this->desa,
            'nama_desa'             => $this->desa['nama_desa'] ?? 'Desa',
            'sebutan_desa'          => $this->desa['sebutan_desa'] ?? 'Desa',
            'logo'                  => $this->desa['logo'] ?? 'logo_xosid.png',
            'module_name'           => 'Kelompok',
            'controller'            => 'kelompok',
            'list_master'           => $list_master,
            'default_status_dasar'  => 0,
            'default_kelompok'      => null,
        ];

        $this->renderBlade('admin.kelompok.index', $data);
    }

    public function datatables()
    {
        if (!$this->db_real) {
            echo json_encode(['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
            return;
        }

        $draw   = $this->input->post('draw') ?: 1;
        $start  = $this->input->post('start') ?: 0;
        $length = $this->input->post('length') ?: 25;
        $search = $this->input->post('search')['value'] ?? '';
        $status_dasar = $this->input->post('status_dasar') ?: '';
        $filter = $this->input->post('filter') ?: '';

        try {
            $where = [];
            $params = [];

            if ($status_dasar !== '' && $status_dasar !== '0') {
                $where[] = "k.status_dasar = ?";
                $params[] = $status_dasar;
            }
            if ($filter) {
                $where[] = "k.id_master = ?";
                $params[] = $filter;
            }
            if ($search) {
                $where[] = "(k.kode LIKE ? OR k.nama LIKE ? OR p.nama LIKE ?)";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
            }

            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

            $totalQ = $this->db_real->query("SELECT COUNT(*) as total FROM kelompok");
            $recordsTotal = ($totalQ) ? $totalQ->row()->total : 0;

            $countSql = "SELECT COUNT(*) as total FROM kelompok k
                LEFT JOIN tweb_penduduk p ON k.id_ketua = p.id
                LEFT JOIN kelompok_master km ON k.id_master = km.id
                {$whereClause}";
            $countQ = $this->db_real->query($countSql, $params);
            $recordsFiltered = ($countQ) ? $countQ->row()->total : 0;

            $orderCol = 'k.kode';
            $orderDir = 'ASC';
            $order = $this->input->post('order');
            if ($order && isset($order[0])) {
                $dir = strtoupper($order[0]['dir'] ?? 'ASC');
                if (!in_array($dir, ['ASC', 'DESC'])) $dir = 'ASC';
                $orderDir = $dir;
                $colMap = [3 => 'k.kode', 4 => 'k.nama', 5 => 'p.nama', 6 => 'km.kelompok', 7 => 'jml_anggota'];
                $colIdx = $order[0]['column'] ?? 3;
                $orderCol = $colMap[$colIdx] ?? 'k.kode';
            }

            $sql = "SELECT k.id, k.kode, k.nama, k.id_ketua, k.id_master, k.status_dasar,
                        p.nama as ketua_nama,
                        km.kelompok as master_kelompok,
                        (SELECT COUNT(*) FROM kelompok_anggota ka WHERE ka.id_kelompok = k.id) as jml_anggota
                    FROM kelompok k
                    LEFT JOIN tweb_penduduk p ON k.id_ketua = p.id
                    LEFT JOIN kelompok_master km ON k.id_master = km.id
                    {$whereClause}
                    ORDER BY {$orderCol} {$orderDir}
                    LIMIT ?, ?";

            $params[] = (int)$start;
            $params[] = (int)$length;

            $result = $this->db_real->query($sql, $params);
            $data = [];

            if ($result && $result->num_rows() > 0) {
                $idx = $start + 1;
                foreach ($result->result() as $row) {
                    $aksi = '<div class="btn-group">'
                        . '<a href="' . site_url("kelompok/anggota/{$row->id}") . '" class="btn btn-info btn-xs" title="Anggota"><i class="fa fa-list"></i></a> '
                        . '<a href="' . site_url("kelompok/form/{$row->id}") . '" class="btn btn-warning btn-xs" title="Ubah" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-edit"></i></a> '
                        . '<a href="#" data-href="' . site_url("kelompok/delete/{$row->id}") . '" class="btn btn-danger btn-xs" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>'
                        . '</div>';

                    $data[] = [
                        'ceklist'       => '<input type="checkbox" name="id_cb[]" value="' . $row->id . '">',
                        'DT_RowIndex'   => $idx++,
                        'aksi'          => $aksi,
                        'kode'          => $row->kode ?: '-',
                        'nama'          => $row->nama ?: '-',
                        'ketua'         => ['nama' => $row->ketua_nama ?: '-'],
                        'kelompok_master' => ['kelompok' => $row->master_kelompok ?: '-'],
                        'jml_anggota'   => $row->jml_anggota ?: 0,
                    ];
                }
            }

            echo json_encode([
                'draw'            => (int)$draw,
                'recordsTotal'    => (int)$recordsTotal,
                'recordsFiltered' => (int)$recordsFiltered,
                'data'            => $data,
            ]);
        } catch (\Throwable $e) {
            @file_put_contents(FCPATH . 'storage/logs/xosid_neural_error.log',
                "[" . date('Y-m-d H:i:s') . "] KELOMPOK DT: " . $e->getMessage() . "\n", FILE_APPEND);
            echo json_encode(['draw' => (int)$draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
        }
    }
}
