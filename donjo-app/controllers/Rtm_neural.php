<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * XOSID - Neural Rtm Controller (Rumah Tangga)
 * Bypasses ionCube-encrypted Admin_Controller::render() pipeline.
 */
class Rtm_neural extends CI_Controller
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
                "[" . date('Y-m-d H:i:s') . "] RTM RENDER: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n", FILE_APPEND);
        }
        if ($output) { echo $output; }
        else { echo '<div style="color:red;padding:20px;">Error rendering view: ' . htmlspecialchars($viewName) . '</div>'; }
    }

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
                    if (!isset($wilayah[$d])) $wilayah[$d] = [];
                    if (!empty($rw) && $rw !== '0' && $rw !== '-') {
                        if (!isset($wilayah[$d][$rw])) $wilayah[$d][$rw] = [];
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

    public function index()
    {
        $status = [
            '1' => 'Aktif',
            '2' => 'Tidak Aktif',
        ];

        $jenis_kelamin = [
            '1' => 'LAKI-LAKI',
            '2' => 'PEREMPUAN',
        ];

        $wilayah = $this->buildWilayahHierarchy();

        $data = [
            'desa'              => $this->desa,
            'nama_desa'         => $this->desa['nama_desa'] ?? 'Desa',
            'sebutan_desa'      => $this->desa['sebutan_desa'] ?? 'Desa',
            'logo'              => $this->desa['logo'] ?? 'logo_xosid.png',
            'module_name'       => 'Rumah Tangga',
            'controller'        => 'rtm',
            'status'            => $status,
            'jenis_kelamin'     => $jenis_kelamin,
            'wilayah'           => $wilayah,
            'labelWilayah'      => false,
            'disableFilter'     => false,
            'judul_statistik'   => null,
            'filterColumn'      => [],
        ];

        $this->renderBlade('admin.penduduk.rtm.index', $data);
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
        $status = $this->input->post('status') ?: '';
        $jenis_kelamin = $this->input->post('jenis_kelamin') ?: '';
        $dusun  = $this->input->post('dusun') ?: '';
        $rw     = $this->input->post('rw') ?: '';
        $rt     = $this->input->post('rt') ?: '';

        try {
            $where = [];
            $params = [];

            if ($status) {
                $where[] = "r.terdaftar_dtks = ?";
                $params[] = $status;
            }
            if ($jenis_kelamin) {
                $where[] = "kepala.sex = ?";
                $params[] = $jenis_kelamin;
            }
            if ($dusun) {
                $where[] = "w.dusun = ?";
                $params[] = $dusun;
            }
            if ($rw) {
                $rw_parts = explode('__', $rw);
                if (count($rw_parts) == 2) {
                    $where[] = "w.dusun = ?";
                    $params[] = $rw_parts[0];
                    $where[] = "w.rw = ?";
                    $params[] = $rw_parts[1];
                }
            }
            if ($rt) {
                $where[] = "kepala.id_cluster = ?";
                $params[] = $rt;
            }
            if ($search) {
                $where[] = "(r.no_kk LIKE ? OR kepala.nama LIKE ? OR kepala.nik LIKE ?)";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
            }

            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

            $totalQ = $this->db_real->query("SELECT COUNT(*) as total FROM tweb_rtm");
            $recordsTotal = ($totalQ) ? $totalQ->row()->total : 0;

            $countSql = "SELECT COUNT(*) as total FROM tweb_rtm r
                LEFT JOIN tweb_penduduk kepala ON r.nik_kepala = kepala.id
                LEFT JOIN tweb_keluarga kk ON kepala.id_kk = kk.id
                LEFT JOIN tweb_wil_clusterdesa w ON kepala.id_cluster = w.id
                {$whereClause}";
            $countQ = $this->db_real->query($countSql, $params);
            $recordsFiltered = ($countQ) ? $countQ->row()->total : 0;

            $orderCol = 'r.no_kk';
            $orderDir = 'ASC';
            $order = $this->input->post('order');
            if ($order && isset($order[0])) {
                $dir = strtoupper($order[0]['dir'] ?? 'ASC');
                if (!in_array($dir, ['ASC', 'DESC'])) $dir = 'ASC';
                $orderDir = $dir;
                $colMap = [4 => 'r.no_kk', 5 => 'kepala.nama', 14 => 'r.tgl_daftar'];
                $colIdx = $order[0]['column'] ?? 4;
                $orderCol = $colMap[$colIdx] ?? 'r.no_kk';
            }

            $sql = "SELECT r.id, r.no_kk, r.nik_kepala, r.terdaftar_dtks, r.tgl_daftar,
                        kepala.id as kepala_id, kepala.nama as kepala_nama, kepala.nik as kepala_nik,
                        kepala.foto as kepala_foto, kepala.alamat_sekarang, kepala.id_kk,
                        w.dusun, w.rw, w.rt,
                        kk.alamat as alamat_kk,
                        (SELECT COUNT(*) FROM tweb_penduduk pa WHERE pa.id_rtm = r.id AND pa.status_dasar = 1) as anggota_count,
                        (SELECT COUNT(DISTINCT pa2.id_kk) FROM tweb_penduduk pa2 WHERE pa2.id_rtm = r.id AND pa2.status_dasar = 1) as jumlah_kk
                    FROM tweb_rtm r
                    LEFT JOIN tweb_penduduk kepala ON r.nik_kepala = kepala.id
                    LEFT JOIN tweb_keluarga kk ON kepala.id_kk = kk.id
                    LEFT JOIN tweb_wil_clusterdesa w ON kepala.id_cluster = w.id
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
                    $foto = '';
                    if ($row->kepala_foto && $row->kepala_foto !== 'kuser.png') {
                        $foto = '<img src="' . base_url("desa/upload/user_pict/{$row->kepala_foto}") . '" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">';
                    } else {
                        $foto = '<img src="' . base_url("assets/images/pengguna/kuser.png") . '" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">';
                    }

                    $aksi = '<div class="btn-group">'
                        . '<a href="' . site_url("rtm/anggota/{$row->id}") . '" class="btn btn-info btn-xs" title="Anggota"><i class="fa fa-list"></i></a>'
                        . '</div>';

                    $alamat = trim($row->alamat_sekarang ?: ($row->alamat_kk ?: ''));

                    $data[] = [
                        'ceklist'       => '<input type="checkbox" name="id_cb[]" value="' . $row->id . '">',
                        'DT_RowIndex'   => $idx++,
                        'aksi'          => $aksi,
                        'foto'          => $foto,
                        'no_kk'         => $row->no_kk ?: '-',
                        'kepala_keluarga' => [
                            'nama'  => $row->kepala_nama ?: '-',
                            'nik'   => $row->kepala_nik ?: '-',
                            'alamat_wilayah' => $alamat,
                            'keluarga' => [
                                'wilayah' => [
                                    'dusun' => $row->dusun ?: '-',
                                    'rw'    => $row->rw ?: '-',
                                    'rt'    => $row->rt ?: '-',
                                ],
                            ],
                        ],
                        'terdaftar_dtks' => $row->terdaftar_dtks ? 'Ya' : 'Tidak',
                        'jumlah_kk'     => $row->jumlah_kk ?: 0,
                        'anggota_count' => $row->anggota_count ?: 0,
                        'tgl_daftar'    => $row->tgl_daftar ?: '-',
                        'id'            => $row->id,
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
                "[" . date('Y-m-d H:i:s') . "] RTM DT: " . $e->getMessage() . "\n", FILE_APPEND);
            echo json_encode(['draw' => (int)$draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
        }
    }
}
