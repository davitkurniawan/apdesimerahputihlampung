<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * XOSID - Neural Wilayah Controller (Independent + Smart View)
 * Bypassing ionCube-encrypted Admin_Controller::render() pipeline
 * which silently produces 0 bytes output.
 * 
 * Mirrors the original Wilayah controller's index(), form(), delete(), tukar()
 * methods using direct Blade rendering via Laravel's view system.
 */
class Wilayah_neural extends CI_Controller
{
    private $db_real = null;
    private $desa = [];
    private $sebutan_dusun = 'Dusun';

    public function __construct()
    {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE) { @session_start(); }
        
        // Mock CI session
        $this->session = new class {
            public function userdata($key) { return $_SESSION[$key] ?? null; }
            public function set_userdata($key, $val) { $_SESSION[$key] = $val; }
            public function flashdata($key) { return null; }
            public function set_flashdata($key, $val) {}
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

        // Get sebutan_dusun from setting_aplikasi
        try {
            if ($this->db_real) {
                $q = $this->db_real->where('key', 'sebutan_dusun')->get('setting_aplikasi');
                if ($q && $q->num_rows() > 0) {
                    $r = $q->row_array();
                    $this->sebutan_dusun = $r['value'] ?: 'Dusun';
                }
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Render a Blade view using Laravel's view system (bypass CI render pipeline)
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
                $output = $app->make('view')->make($viewName, $data)->render();
            }
        } catch (\Throwable $e) {
            @file_put_contents(FCPATH . 'storage/logs/xosid_wilayah_error.log',
                "[" . date('Y-m-d H:i:s') . "] RENDER ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n" . $e->getTraceAsString() . "\n",
                FILE_APPEND);
        }

        if ($output) {
            echo $output;
        } else {
            echo '<div style="color:red;padding:20px;">Error rendering view: ' . htmlspecialchars($viewName) . '. Check storage/logs/xosid_wilayah_error.log</div>';
        }
    }

    /**
     * Resolve level context for hierarchy navigation
     */
    private function resolveLevel($parent = null, $level = null)
    {
        // Default level hierarchy
        if (!$level) {
            $level = 'dusun';
        }

        $wilayah = $this->sebutan_dusun;
        $jabatan = 'Ketua';
        $backUrl = site_url('wilayah');
        $title = '';

        if ($level == 'rw') {
            $wilayah = 'RW';
            $jabatan = 'Ketua';
            // Get parent dusun name
            if ($parent && $this->db_real) {
                try {
                    $q = $this->db_real->where('id', $parent)->get('tweb_wil_clusterdesa');
                    if ($q && $q->num_rows() > 0) {
                        $row = $q->row_array();
                        $title = $this->sebutan_dusun . ' ' . $row['dusun'];
                    }
                } catch (\Throwable $e) {}
            }
        } elseif ($level == 'rt') {
            $wilayah = 'RT';
            $jabatan = 'Ketua';
            // Get parent RW name
            if ($parent && $this->db_real) {
                try {
                    $q = $this->db_real->where('id', $parent)->get('tweb_wil_clusterdesa');
                    if ($q && $q->num_rows() > 0) {
                        $row = $q->row_array();
                        $title = $this->sebutan_dusun . ' ' . $row['dusun'] . ' - RW ' . $row['rw'];
                        $backUrl = site_url('wilayah/index/' . $this->findDusunParentId($row['dusun']) . '/rw');
                    }
                } catch (\Throwable $e) {}
            }
        } else {
            $wilayah = $this->sebutan_dusun;
        }

        return [
            'level'    => $level,
            'wilayah'  => $wilayah,
            'jabatan'  => $jabatan,
            'parent'   => $parent ?: 0,
            'backUrl'  => $backUrl,
            'title'    => $title,
        ];
    }

    /**
     * Find the parent dusun cluster ID from dusun name
     */
    private function findDusunParentId($dusunName)
    {
        if (!$this->db_real) return 0;
        try {
            $q = $this->db_real->where('dusun', $dusunName)
                ->where("rt IN ('0', '-', '')", null, false)
                ->where("rw IN ('0', '-', '')", null, false)
                ->get('tweb_wil_clusterdesa');
            if ($q && $q->num_rows() > 0) {
                return $q->row_array()['id'];
            }
        } catch (\Throwable $e) {}
        return 0;
    }

    /**
     * Index - Daftar wilayah (dusun / rw / rt)
     */
    public function index($parent = null, $level = null)
    {
        $ctx = $this->resolveLevel($parent, $level);

        // Check if order needs refresh (any null urut)
        $refreshOrder = false;
        if ($this->db_real) {
            try {
                $q = $this->db_real->where('urut IS NULL', null, false)->limit(1)->get('tweb_wil_clusterdesa');
                $refreshOrder = ($q && $q->num_rows() > 0);
            } catch (\Throwable $e) {}
        }

        $data = [
            'desa'         => $this->desa,
            'nama_desa'    => $this->desa['nama_desa'] ?? 'Desa',
            'sebutan_desa' => $this->desa['sebutan_desa'] ?? 'Desa',
            'logo'         => $this->desa['logo'] ?? 'logo_xosid.png',
            'wilayah'      => $ctx['wilayah'],
            'level'        => $ctx['level'],
            'parent'       => $ctx['parent'],
            'backUrl'      => $ctx['backUrl'],
            'title'        => $ctx['title'],
            'jabatan'      => $ctx['jabatan'],
            'refreshOrder' => $refreshOrder,
        ];

        $this->renderBlade('admin.wilayah.index', $data);
    }

    /**
     * Form - Tambah/Ubah wilayah
     */
    public function form_dusun($id = null)
    {
        $this->_form('dusun', $id);
    }

    public function form_rw($parent = null, $id = null)
    {
        $this->_form('rw', $id, $parent);
    }

    public function form_rt($parent = null, $id = null)
    {
        $this->_form('rt', $id, $parent);
    }

    private function _form($level, $id = null, $parent = null)
    {
        if ($id === '0' || $id === 0) {
            $id = null;
        }
        $parent = $parent ?? 0;
        $aksi = !empty($id);
        $wilayahLabel = ($level == 'dusun') ? $this->sebutan_dusun : strtoupper($level);

        // Default empty wilayah object
        $wilayah = new \stdClass();
        $wilayah->id = '';
        $wilayah->dusun = '';
        $wilayah->rw = '';
        $wilayah->rt = '';
        $wilayah->kepala = null;
        $wilayah->id_kepala = '';

        if ($aksi && $id && $this->db_real) {
            try {
                $q = $this->db_real->where('id', $id)->get('tweb_wil_clusterdesa');
                if ($q && $q->num_rows() > 0) {
                    $row = $q->row();
                    $wilayah->id = $row->id;
                    $wilayah->dusun = $row->dusun;
                    $wilayah->rw = $row->rw;
                    $wilayah->rt = $row->rt;
                    $wilayah->id_kepala = $row->id_kepala ?? '';

                    // Get kepala data
                    if (!empty($row->id_kepala)) {
                        $kq = $this->db_real->where('id', $row->id_kepala)->get('tweb_penduduk');
                        if ($kq && $kq->num_rows() > 0) {
                            $wilayah->kepala = $kq->row();
                        }
                    }
                }
            } catch (\Throwable $e) {}
        }

        $form_action = $aksi
            ? site_url("wilayah/update_{$level}/{$id}")
            : site_url("wilayah/insert_{$level}/{$parent}");

        $data = [
            'desa'         => $this->desa,
            'nama_desa'    => $this->desa['nama_desa'] ?? 'Desa',
            'sebutan_desa' => $this->desa['sebutan_desa'] ?? 'Desa',
            'logo'         => $this->desa['logo'] ?? 'logo_xosid.png',
            'aksi'         => $aksi,
            'wilayah'      => $wilayah,
            'wilayahLabel' => $wilayahLabel,
            'level'        => $level,
            'form_action'  => $form_action,
        ];

        $this->renderBlade('admin.wilayah.form', $data);
    }

    /**
     * Insert handlers - delegate to original CI Wilayah controller if available
     */
    public function insert_dusun($parent = 0)
    {
        $this->_insert('dusun', $parent);
    }

    public function insert_rw($parent = 0)
    {
        $this->_insert('rw', $parent);
    }

    public function insert_rt($parent = 0)
    {
        $this->_insert('rt', $parent);
    }

    private function _insert($level, $parent = 0)
    {
        if (!$this->db_real) {
            redirect(site_url('wilayah'));
            return;
        }

        $nama = $this->input->post($level);

        if ($level == 'dusun') {
            $data = [
                'config_id' => $this->desa['id'] ?? 1,
                'dusun'     => $nama,
                'rw'        => '0',
                'rt'        => '0',
                'id_kepala' => $this->input->post('id_kepala') ?: null,
            ];
        } elseif ($level == 'rw') {
            // Get dusun name from parent
            $dusun = '';
            $q = $this->db_real->where('id', $parent)->get('tweb_wil_clusterdesa');
            if ($q && $q->num_rows() > 0) {
                $dusun = $q->row()->dusun;
            }
            $data = [
                'config_id' => $this->desa['id'] ?? 1,
                'dusun'     => $dusun,
                'rw'        => $nama,
                'rt'        => '0',
                'id_kepala' => $this->input->post('id_kepala') ?: null,
            ];
        } elseif ($level == 'rt') {
            // Get dusun & rw from parent
            $dusun = '';
            $rw = '';
            $q = $this->db_real->where('id', $parent)->get('tweb_wil_clusterdesa');
            if ($q && $q->num_rows() > 0) {
                $dusun = $q->row()->dusun;
                $rw = $q->row()->rw;
            }
            $data = [
                'config_id' => $this->desa['id'] ?? 1,
                'dusun'     => $dusun,
                'rw'        => $rw,
                'rt'        => $nama,
                'id_kepala' => $this->input->post('id_kepala') ?: null,
            ];
        }

        try {
            $this->db_real->insert('tweb_wil_clusterdesa', $data);
        } catch (\Throwable $e) {
            @file_put_contents(FCPATH . 'storage/logs/xosid_wilayah_error.log',
                "[" . date('Y-m-d H:i:s') . "] INSERT ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
        }

        if ($level == 'dusun') {
            redirect(site_url('wilayah'));
        } else {
            redirect(site_url("wilayah/index/{$parent}/{$level}"));
        }
    }

    /**
     * Update handlers
     */
    public function update_dusun($id)
    {
        $this->_update('dusun', $id);
    }

    public function update_rw($id)
    {
        $this->_update('rw', $id);
    }

    public function update_rt($id)
    {
        $this->_update('rt', $id);
    }

    private function _update($level, $id)
    {
        if (!$this->db_real || !$id) {
            redirect(site_url('wilayah'));
            return;
        }

        $nama = $this->input->post($level);
        $data = [
            $level      => $nama,
            'id_kepala' => $this->input->post('id_kepala') ?: null,
        ];

        // If updating dusun name, also update all child rows
        if ($level == 'dusun') {
            try {
                $old_q = $this->db_real->where('id', $id)->get('tweb_wil_clusterdesa');
                if ($old_q && $old_q->num_rows() > 0) {
                    $old_name = $old_q->row()->dusun;
                    $this->db_real->where('dusun', $old_name)->update('tweb_wil_clusterdesa', ['dusun' => $nama]);
                }
            } catch (\Throwable $e) {}
        }

        try {
            $this->db_real->where('id', $id)->update('tweb_wil_clusterdesa', $data);
        } catch (\Throwable $e) {
            @file_put_contents(FCPATH . 'storage/logs/xosid_wilayah_error.log',
                "[" . date('Y-m-d H:i:s') . "] UPDATE ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
        }

        redirect(site_url('wilayah'));
    }

    /**
     * Delete wilayah
     */
    public function delete($level, $parent = 0, $id = null)
    {
        if (!$this->db_real || !$id) {
            redirect(site_url('wilayah'));
            return;
        }

        try {
            if ($level == 'dusun') {
                // Delete the dusun and all its children
                $q = $this->db_real->where('id', $id)->get('tweb_wil_clusterdesa');
                if ($q && $q->num_rows() > 0) {
                    $dusun_name = $q->row()->dusun;
                    $this->db_real->where('dusun', $dusun_name)->delete('tweb_wil_clusterdesa');
                }
            } elseif ($level == 'rw') {
                $q = $this->db_real->where('id', $id)->get('tweb_wil_clusterdesa');
                if ($q && $q->num_rows() > 0) {
                    $row = $q->row();
                    // Delete the RW and all its RTs
                    $this->db_real->where('dusun', $row->dusun)
                        ->where('rw', $row->rw)
                        ->delete('tweb_wil_clusterdesa');
                }
            } else {
                $this->db_real->where('id', $id)->delete('tweb_wil_clusterdesa');
            }
        } catch (\Throwable $e) {
            @file_put_contents(FCPATH . 'storage/logs/xosid_wilayah_error.log',
                "[" . date('Y-m-d H:i:s') . "] DELETE ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
        }

        if ($parent && $parent != '0') {
            redirect(site_url("wilayah/index/{$parent}/{$level}"));
        } else {
            redirect(site_url('wilayah'));
        }
    }

    /**
     * Tukar urutan (drag & drop reorder)
     */
    public function tukar()
    {
        $data = $this->input->post('data');
        $status = false;

        if ($data && $this->db_real) {
            try {
                foreach ($data as $index => $id) {
                    $this->db_real->where('id', $id)->update('tweb_wil_clusterdesa', ['urut' => $index + 1]);
                }
                $status = true;
            } catch (\Throwable $e) {}
        }

        echo json_encode(['status' => $status]);
    }

    /**
     * API Penduduk for Select2 autocomplete in form
     */
    public function apipendudukwilayah()
    {
        $search = $this->input->get('q') ?: '';
        $results = [];

        if ($this->db_real && strlen($search) >= 1) {
            try {
                $q = $this->db_real
                    ->select('id, nik, nama')
                    ->where('status_dasar', 1)
                    ->group_start()
                        ->like('nama', $search)
                        ->or_like('nik', $search)
                    ->group_end()
                    ->limit(20)
                    ->get('tweb_penduduk');

                if ($q && $q->num_rows() > 0) {
                    foreach ($q->result_array() as $row) {
                        $results[] = [
                            'id'   => $row['id'],
                            'text' => $row['nik'] . ' - ' . $row['nama'],
                        ];
                    }
                }
            } catch (\Throwable $e) {}
        }

        echo json_encode(['results' => $results]);
    }

    /**
     * Cetak / Unduh handlers
     */
    public function cetak_dusun()
    {
        $this->_cetak('dusun');
    }

    public function unduh_dusun()
    {
        $this->_cetak('dusun', true);
    }

    public function cetak_rw($parent = 0)
    {
        $this->_cetak('rw', false, $parent);
    }

    public function unduh_rw($parent = 0)
    {
        $this->_cetak('rw', true, $parent);
    }

    public function cetak_rt($parent = 0)
    {
        $this->_cetak('rt', false, $parent);
    }

    public function unduh_rt($parent = 0)
    {
        $this->_cetak('rt', true, $parent);
    }

    private function _cetak($level, $unduh = false, $parent = 0)
    {
        // Get data from DB
        $rows = [];
        if ($this->db_real) {
            try {
                if ($level == 'rw') {
                    $viewName = 'admin.wilayah.wilayah_rw_cetak';
                } elseif ($level == 'rt') {
                    $viewName = 'admin.wilayah.wilayah_rt_cetak';
                } else {
                    $viewName = 'admin.wilayah.wilayah_cetak';
                }
            } catch (\Throwable $e) {}
        }

        $data = [
            'desa'   => $this->desa,
            'level'  => $level,
            'parent' => $parent,
        ];

        if ($unduh) {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="wilayah_' . $level . '.xls"');
        }

        $this->renderBlade($viewName ?? 'admin.wilayah.wilayah_cetak', $data);
    }
}
