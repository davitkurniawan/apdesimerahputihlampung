<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * XOSID - Neural Penduduk_log Controller
 * Bypasses ionCube-encrypted Admin_Controller::render() pipeline.
 */
class Penduduk_log_neural extends CI_Controller
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
                "[" . date('Y-m-d H:i:s') . "] PENDUDUK_LOG RENDER: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n", FILE_APPEND);
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
        $list_jenis_peristiwa = [
            '1' => 'Lahir',
            '2' => 'Mati',
            '3' => 'Pindah Keluar',
            '4' => 'Hilang',
            '5' => 'Pindah Masuk',
            '6' => 'Pergi',
        ];

        $list_sex = [
            '1' => 'LAKI-LAKI',
            '2' => 'PEREMPUAN',
        ];

        // Get agama list from DB
        $list_agama = [];
        if ($this->db_real) {
            try {
                $q = $this->db_real->order_by('id', 'ASC')->get('tweb_penduduk_agama');
                if ($q && $q->num_rows() > 0) {
                    foreach ($q->result() as $row) {
                        $list_agama[$row->id] = $row->nama;
                    }
                }
            } catch (\Throwable $e) {}
        }
        if (empty($list_agama)) {
            $list_agama = [1 => 'Islam', 2 => 'Kristen', 3 => 'Katholik', 4 => 'Hindu', 5 => 'Budha', 6 => 'Konghucu', 7 => 'Lainnya'];
        }

        // Get earliest log year
        $tahun_log_pertama = 2020;
        if ($this->db_real) {
            try {
                $q = $this->db_real->query("SELECT MIN(YEAR(tgl_lapor)) as min_year FROM log_penduduk WHERE tgl_lapor IS NOT NULL");
                if ($q && $q->row() && $q->row()->min_year) {
                    $tahun_log_pertama = (int)$q->row()->min_year;
                }
            } catch (\Throwable $e) {}
        }

        $wilayah = $this->buildWilayahHierarchy();

        $data = [
            'desa'                  => $this->desa,
            'nama_desa'             => $this->desa['nama_desa'] ?? 'Desa',
            'sebutan_desa'          => $this->desa['sebutan_desa'] ?? 'Desa',
            'logo'                  => $this->desa['logo'] ?? 'logo_xosid.png',
            'module_name'           => 'Log Penduduk',
            'controller'            => 'penduduk_log',
            'list_jenis_peristiwa'  => $list_jenis_peristiwa,
            'defaultFilter'         => ['kode_peristiwa' => '', 'tahun' => date('Y'), 'bulan' => '', 'sex' => ''],
            'tahun_log_pertama'     => $tahun_log_pertama,
            'list_sex'              => $list_sex,
            'list_agama'            => $list_agama,
            'wilayah'               => $wilayah,
            'labelWilayah'          => false,
            'disableFilter'         => false,
            'judul_statistik'       => null,
            'statistikFilter'       => [],
            'filterColumn'          => [],
            'pertanyaan'            => 'Apakah Anda yakin ingin mengembalikan status penduduk ini?',
        ];

        $this->renderBlade('admin.penduduk_log.index', $data);
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
        $kode_peristiwa = $this->input->post('kode_peristiwa') ?: '';
        $tahun   = $this->input->post('tahun') ?: '';
        $bulan   = $this->input->post('bulan') ?: '';
        $jk      = $this->input->post('jenis_kelamin') ?: '';
        $agama   = $this->input->post('agama') ?: '';
        $dusun   = $this->input->post('dusun') ?: '';
        $rw      = $this->input->post('rw') ?: '';
        $rt      = $this->input->post('rt') ?: '';

        try {
            $where = [];
            $params = [];

            if ($kode_peristiwa) {
                $where[] = "lp.kode_peristiwa = ?";
                $params[] = $kode_peristiwa;
            }
            if ($tahun) {
                $where[] = "YEAR(lp.tgl_lapor) = ?";
                $params[] = $tahun;
            }
            if ($bulan) {
                $where[] = "MONTH(lp.tgl_lapor) = ?";
                $params[] = $bulan;
            }
            if ($jk) {
                $where[] = "p.sex = ?";
                $params[] = $jk;
            }
            if ($agama) {
                $where[] = "p.agama_id = ?";
                $params[] = $agama;
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
                $where[] = "p.id_cluster = ?";
                $params[] = $rt;
            }
            if ($search) {
                $where[] = "(p.nik LIKE ? OR p.nama LIKE ?)";
                $params[] = "%{$search}%";
                $params[] = "%{$search}%";
            }

            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

            $totalQ = $this->db_real->query("SELECT COUNT(*) as total FROM log_penduduk");
            $recordsTotal = ($totalQ) ? $totalQ->row()->total : 0;

            $countSql = "SELECT COUNT(*) as total FROM log_penduduk lp
                LEFT JOIN tweb_penduduk p ON lp.id_pend = p.id
                LEFT JOIN tweb_wil_clusterdesa w ON p.id_cluster = w.id
                {$whereClause}";
            $countQ = $this->db_real->query($countSql, $params);
            $recordsFiltered = ($countQ) ? $countQ->row()->total : 0;

            $orderCol = 'lp.tgl_peristiwa';
            $orderDir = 'DESC';
            $order = $this->input->post('order');
            if ($order && isset($order[0])) {
                $dir = strtoupper($order[0]['dir'] ?? 'DESC');
                if (!in_array($dir, ['ASC', 'DESC'])) $dir = 'DESC';
                $orderDir = $dir;
                $colMap = [4 => 'p.nik', 5 => 'p.nama', 11 => 'p.tanggallahir', 13 => 'lp.tgl_peristiwa', 14 => 'lp.tgl_lapor'];
                $colIdx = $order[0]['column'] ?? 13;
                $orderCol = $colMap[$colIdx] ?? 'lp.tgl_peristiwa';
            }

            $statusDasarMap = [1 => 'HIDUP', 2 => 'MATI', 3 => 'PINDAH', 4 => 'HILANG', 9 => 'PERGI'];
            $peristiwaMap = [1 => 'Lahir', 2 => 'Mati', 3 => 'Pindah Keluar', 4 => 'Hilang', 5 => 'Pindah Masuk', 6 => 'Pergi'];

            $sql = "SELECT lp.id, lp.id_pend, lp.kode_peristiwa, lp.meninggal_di, lp.tgl_peristiwa,
                        lp.tgl_lapor, lp.catatan, lp.ref_pindah,
                        p.id as penduduk_id, p.nik, p.nama, p.sex, p.tanggallahir,
                        p.foto, p.status_dasar, p.id_kk, p.alamat_sekarang,
                        w.dusun, w.rw, w.rt,
                        kk.no_kk, kk.alamat as alamat_kk,
                        (SELECT pp.nama FROM tweb_penduduk pp WHERE pp.id = kk.nik_kepala LIMIT 1) as kepala_kk_nama
                    FROM log_penduduk lp
                    LEFT JOIN tweb_penduduk p ON lp.id_pend = p.id
                    LEFT JOIN tweb_wil_clusterdesa w ON p.id_cluster = w.id
                    LEFT JOIN tweb_keluarga kk ON p.id_kk = kk.id
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
                    $umur = '-';
                    if ($row->tanggallahir) {
                        try {
                            $birth = new \DateTime($row->tanggallahir);
                            $now = new \DateTime();
                            $umur = $now->diff($birth)->y;
                        } catch (\Throwable $e) {}
                    }

                    $jenis_kelamin = ($row->sex == 1) ? 'LAKI-LAKI' : (($row->sex == 2) ? 'PEREMPUAN' : '-');

                    $foto = '';
                    if ($row->foto && $row->foto !== 'kuser.png') {
                        $foto = '<img src="' . base_url("desa/upload/user_pict/{$row->foto}") . '" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">';
                    } else {
                        $foto = '<img src="' . base_url("assets/images/pengguna/kuser.png") . '" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">';
                    }

                    $aksi = '<div class="btn-group">'
                        . '<a href="' . site_url("penduduk_log/edit/{$row->id}") . '" class="btn btn-warning btn-xs" title="Ubah" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-edit"></i></a>'
                        . '</div>';

                    $status_menjadi = $peristiwaMap[$row->kode_peristiwa] ?? ($statusDasarMap[$row->kode_peristiwa] ?? '-');

                    $data[] = [
                        'ceklist'       => '<input type="checkbox" name="id_cb[]" value="' . $row->id . '">',
                        'DT_RowIndex'   => $idx++,
                        'aksi'          => $aksi,
                        'foto'          => $foto,
                        'penduduk'      => [
                            'id'    => $row->penduduk_id ?: 0,
                            'nik'   => $row->nik ?: '-',
                            'nama'  => $row->nama ?: '-',
                            'jenis_kelamin' => $jenis_kelamin,
                            'id_kk' => $row->id_kk,
                            'wilayah' => [
                                'dusun' => $row->dusun ?: '-',
                                'rw'    => $row->rw ?: '-',
                                'rt'    => $row->rt ?: '-',
                            ],
                        ],
                        'keluarga'      => ['no_kk' => $row->no_kk ?: ''],
                        'kepala_keluarga' => $row->kepala_kk_nama ?: '-',
                        'umur'          => $umur,
                        'status_menjadi' => $status_menjadi,
                        'tgl_peristiwa' => $row->tgl_peristiwa ?: '-',
                        'tgl_lapor'     => $row->tgl_lapor ?: '-',
                        'catatan'       => $row->catatan ?: '-',
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
                "[" . date('Y-m-d H:i:s') . "] PENDUDUK_LOG DT: " . $e->getMessage() . "\n", FILE_APPEND);
            echo json_encode(['draw' => (int)$draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
        }
    }
}
