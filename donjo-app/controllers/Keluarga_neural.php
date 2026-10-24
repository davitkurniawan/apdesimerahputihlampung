<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * XOSID - Neural Keluarga Controller
 * Bypasses ionCube-encrypted Admin_Controller::render() pipeline.
 */
class Keluarga_neural extends CI_Controller
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
                "[" . date('Y-m-d H:i:s') . "] KELUARGA RENDER: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n", FILE_APPEND);
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
            '1' => 'KK TETAP',
            '2' => 'KK PENDATANG',
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
            'module_name'       => 'Keluarga',
            'controller'        => 'keluarga',
            'status'            => $status,
            'defaultStatus'     => '',
            'jenis_kelamin'     => $jenis_kelamin,
            'wilayah'           => $wilayah,
            'labelWilayah'      => false,
            'disableFilter'     => false,
            'judul_statistik'   => null,
            'statistikFilter'   => [],
            'filterColumn'      => [],
        ];

        $this->renderBlade('admin.penduduk.keluarga.index', $data);
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
                $where[] = "kepala.status = ?";
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
                $where[] = "kk.id_cluster = ?";
                $params[] = $rt;
            }
            if ($search) {
                $where[] = "(kk.no_kk LIKE ? OR kepala.nama LIKE ? OR kepala.nik LIKE ?)";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
            }

            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

            $totalQ = $this->db_real->query("SELECT COUNT(*) as total FROM tweb_keluarga");
            $recordsTotal = ($totalQ) ? $totalQ->row()->total : 0;

            $countSql = "SELECT COUNT(*) as total FROM tweb_keluarga kk
                LEFT JOIN tweb_penduduk kepala ON kk.nik_kepala = kepala.id
                LEFT JOIN tweb_wil_clusterdesa w ON kk.id_cluster = w.id
                {$whereClause}";
            $countQ = $this->db_real->query($countSql, $params);
            $recordsFiltered = ($countQ) ? $countQ->row()->total : 0;

            $orderCol = 'kk.no_kk';
            $orderDir = 'ASC';
            $order = $this->input->post('order');
            if ($order && isset($order[0])) {
                $dir = strtoupper($order[0]['dir'] ?? 'ASC');
                if (!in_array($dir, ['ASC', 'DESC'])) $dir = 'ASC';
                $orderDir = $dir;
                $colMap = [4 => 'kk.no_kk', 5 => 'kepala.nama', 6 => 'kepala.nik', 13 => 'kk.tgl_daftar', 14 => 'kk.tgl_daftar', 15 => 'kk.tgl_cetak_kk'];
                $colIdx = $order[0]['column'] ?? 4;
                $orderCol = $colMap[$colIdx] ?? 'kk.no_kk';
            }

            $start = (int)$start;
            $length = (int)$length;
            if ($length <= 0) $length = 10;

            $sql = "SELECT kk.id, kk.no_kk, kk.nik_kepala, kk.alamat, kk.id_cluster,
                        kk.tgl_daftar, kk.tgl_cetak_kk,
                        kepala.id as kepala_id, kepala.nama as kepala_nama, kepala.nik as kepala_nik,
                        kepala.sex as kepala_sex, kepala.foto as kepala_foto, kepala.tag_id_card,
                        kepala.alamat_sekarang,
                        w.dusun, w.rw, w.rt,
                        (SELECT COUNT(*) FROM tweb_penduduk pa WHERE pa.id_kk = kk.id AND pa.status_dasar = 1) as jml_anggota
                    FROM tweb_keluarga kk
                    LEFT JOIN tweb_penduduk kepala ON kk.nik_kepala = kepala.id
                    LEFT JOIN tweb_wil_clusterdesa w ON kk.id_cluster = w.id
                    {$whereClause}
                    ORDER BY {$orderCol} {$orderDir}
                    LIMIT {$start}, {$length}";

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
                        . '<a href="' . site_url("keluarga/kartu_keluarga/{$row->id}") . '" class="btn btn-info btn-xs" title="Detail"><i class="fa fa-eye"></i></a>'
                        . '</div>';

                    $jk = ($row->kepala_sex == 1) ? 'LAKI-LAKI' : (($row->kepala_sex == 2) ? 'PEREMPUAN' : '-');
                    $alamat = trim($row->alamat_sekarang ?: ($row->alamat ?: ''));

                    $data[] = [
                        'ceklist'           => '<input type="checkbox" name="id_cb[]" value="' . $row->id . '">',
                        'DT_RowIndex'       => $idx++,
                        'aksi'              => $aksi,
                        'foto'              => $foto,
                        'no_kk'             => $row->no_kk ?: '-',
                        'kepala_keluarga'   => $row->kepala_nama ?: '-',
                        'nik'               => $row->kepala_nik ?: '-',
                        'tag_id_card'       => $row->tag_id_card ?: '-',
                        'jumlah_anggota'    => $row->jml_anggota ?: 0,
                        'jenis_kelamin'     => $jk,
                        'alamat'            => $alamat,
                        'wilayah'           => [
                            'dusun' => $row->dusun ?: '-',
                            'rw'    => $row->rw ?: '-',
                            'rt'    => $row->rt ?: '-',
                        ],
                        'tgl_daftar'        => $row->tgl_daftar ?: '-',
                        'tgl_cetak_kk'      => $row->tgl_cetak_kk ?: '-',
                        'id'                => $row->id,
                        'id_kk'             => $row->id,
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
                "[" . date('Y-m-d H:i:s') . "] KELUARGA DT: " . $e->getMessage() . "\n", FILE_APPEND);
            echo json_encode(['draw' => (int)$draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
        }
    }

    public function kartu_keluarga($id = null)
    {
        if ($id) {
            redirect(site_url("keluarga/kartu_keluarga/{$id}"));
        } else {
            redirect(site_url("keluarga"));
        }
    }
}
