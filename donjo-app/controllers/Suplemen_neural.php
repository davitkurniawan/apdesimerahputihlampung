<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * XOSID - Neural Suplemen Controller
 * Bypasses ionCube-encrypted Admin_Controller::render() pipeline.
 */
class Suplemen_neural extends CI_Controller
{
    private $db_real = null;
    private $desa = [];

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
                "[" . date('Y-m-d H:i:s') . "] SUPLEMEN RENDER: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n", FILE_APPEND);
        }
        if ($output) { echo $output; }
        else { echo '<div style="color:red;padding:20px;">Error rendering view: ' . htmlspecialchars($viewName) . '</div>'; }
    }

    public function index()
    {
        $list_sasaran = [
            '1' => 'Penduduk',
            '2' => 'Keluarga / KK',
        ];

        $data = [
            'desa'          => $this->desa,
            'nama_desa'     => $this->desa['nama_desa'] ?? 'Desa',
            'sebutan_desa'  => $this->desa['sebutan_desa'] ?? 'Desa',
            'logo'          => $this->desa['logo'] ?? 'logo_xosid.png',
            'module_name'   => 'Data Suplemen',
            'controller'    => 'suplemen',
            'list_sasaran'  => $list_sasaran,
        ];

        $this->renderBlade('admin.suplemen.index', $data);
    }

    public function datatables()
    {
        if (!$this->db_real) {
            echo json_encode(['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
            return;
        }

        $draw   = $this->input->post('draw') ?: 1;
        $start  = $this->input->post('start') ?: 0;
        $length = $this->input->post('length') ?: 10;
        $search = $this->input->post('search')['value'] ?? '';
        $sasaran = $this->input->post('sasaran') ?: '';

        try {
            $where = [];
            $params = [];

            if ($sasaran) {
                $where[] = "s.sasaran = ?";
                $params[] = $sasaran;
            }
            if ($search) {
                $where[] = "(s.nama LIKE ? OR s.keterangan LIKE ?)";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
            }

            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

            $countSql = "SELECT COUNT(*) as total FROM suplemen s {$whereClause}";
            $countQ = $this->db_real->query($countSql, $params);
            $recordsFiltered = ($countQ) ? $countQ->row()->total : 0;

            $totalQ = $this->db_real->query("SELECT COUNT(*) as total FROM suplemen");
            $recordsTotal = ($totalQ) ? $totalQ->row()->total : 0;

            // Order
            $orderCol = 's.nama';
            $orderDir = 'ASC';
            $order = $this->input->post('order');
            if ($order && isset($order[0])) {
                $dir = strtoupper($order[0]['dir'] ?? 'ASC');
                if (!in_array($dir, ['ASC', 'DESC'])) $dir = 'ASC';
                $orderDir = $dir;
                $colMap = [2 => 's.nama', 4 => 's.sasaran', 5 => 's.keterangan'];
                $colIdx = $order[0]['column'] ?? 2;
                $orderCol = $colMap[$colIdx] ?? 's.nama';
            }

            $sql = "SELECT s.id, s.nama, s.sasaran, s.keterangan,
                        (SELECT COUNT(*) FROM suplemen_terdata st WHERE st.id_suplemen = s.id) as terdata_count
                    FROM suplemen s
                    {$whereClause}
                    ORDER BY {$orderCol} {$orderDir}
                    LIMIT ?, ?";

            $params[] = (int)$start;
            $params[] = (int)$length;

            $result = $this->db_real->query($sql, $params);
            $data = [];

            $sasaranMap = ['1' => 'Penduduk', '2' => 'Keluarga / KK'];

            if ($result && $result->num_rows() > 0) {
                $idx = $start + 1;
                foreach ($result->result() as $row) {
                    $aksi = '<div class="btn-group">'
                        . '<a href="' . site_url("suplemen/rincian/{$row->id}") . '" class="btn btn-info btn-xs" title="Rincian"><i class="fa fa-list"></i></a> '
                        . '<a href="' . site_url("suplemen/form/{$row->id}") . '" class="btn btn-warning btn-xs" title="Ubah"><i class="fa fa-edit"></i></a> '
                        . '<a href="#" data-href="' . site_url("suplemen/delete/{$row->id}") . '" class="btn btn-danger btn-xs" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>'
                        . '</div>';

                    $data[] = [
                        'DT_RowIndex'   => $idx++,
                        'aksi'          => $aksi,
                        'nama'          => $row->nama ?: '-',
                        'terdata_count' => $row->terdata_count ?: 0,
                        'sasaran'       => $sasaranMap[$row->sasaran] ?? '-',
                        'keterangan'    => $row->keterangan ?: '-',
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
                "[" . date('Y-m-d H:i:s') . "] SUPLEMEN DT: " . $e->getMessage() . "\n", FILE_APPEND);
            echo json_encode(['draw' => (int)$draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
        }
    }
}
