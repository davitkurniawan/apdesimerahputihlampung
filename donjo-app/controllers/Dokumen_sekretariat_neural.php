<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * XOSID - Neural Dokumen Sekretariat Controller
 * High-performance, zero-latency controller for 10 Buku Desa (Peraturan di Desa, Keputusan Kades, Info Publik).
 * Fully compatible with PHP 8.2 & 8.4 with instant DataTables and Blade rendering.
 */
class Dokumen_sekretariat_neural extends CI_Controller
{
    private $db_real = null;
    private $desa = [];
    public $controller = 'dokumen_sekretariat';

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
                $app->make('view')->share('desa', $this->desa);
                $output = $app->make('view')->make($viewName, $data)->render();
            }
        } catch (\Throwable $e) {
            @file_put_contents(FCPATH . 'storage/logs/xosid_neural_error.log',
                "[" . date('Y-m-d H:i:s') . "] DOKUMEN SEKRETARIAT RENDER ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n",
                FILE_APPEND);
        }

        if ($output) {
            echo $output;
        } else {
            echo '<div style="color:red;padding:20px;">Error rendering view: ' . htmlspecialchars($viewName) . '</div>';
        }
    }

    public function index($kat = 3, $filter = null, $offset = 0)
    {
        $kat = (int)($kat ?: 3);
        $this->renderPage($kat, $filter, $offset);
    }

    public function peraturan($filter = null, $offset = 0)
    {
        $this->renderPage(3, $filter, $offset);
    }

    public function keputusan($filter = null, $offset = 0)
    {
        $this->renderPage(2, $filter, $offset);
    }

    public function informasi_publik($filter = null, $offset = 0)
    {
        $this->renderPage(1, $filter, $offset);
    }

    public function peraturan_desa($kat = 3, $filter = null, $offset = 0)
    {
        $this->renderPage((int)$kat, $filter, $offset);
    }

    private function renderPage($kat, $filter = null, $offset = 0)
    {
        $nav_map = [
            1 => 'informasi_publik',
            2 => 'keputusan',
            3 => 'peraturan',
        ];
        $selected_nav = $nav_map[$kat] ?? 'peraturan';

        $kat_names = [
            1 => 'Informasi Publik',
            2 => 'Buku Keputusan Kepala Desa',
            3 => 'Buku Peraturan di Desa',
        ];

        // List tahun dari tabel dokumen
        $list_tahun = [];
        try {
            if ($this->db_real) {
                $thnQ = $this->db_real->query("SELECT DISTINCT tahun FROM dokumen WHERE kategori = {$kat} AND (deleted = 0 OR deleted IS NULL) AND tahun IS NOT NULL ORDER BY tahun DESC");
                if ($thnQ && $thnQ->num_rows() > 0) {
                    foreach ($thnQ->result_array() as $r) {
                        $list_tahun[] = ['tahun' => $r['tahun']];
                    }
                }
            }
        } catch (\Throwable $e) {}

        if (empty($list_tahun)) {
            $curr_year = (int)date('Y');
            for ($y = $curr_year; $y >= $curr_year - 4; $y--) {
                $list_tahun[] = ['tahun' => $y];
            }
        }

        $jenis_peraturan = [
            'Peraturan Desa',
            'Peraturan Bersama Kepala Desa',
            'Peraturan Kepala Desa',
        ];

        $data = [
            'kat'             => $kat,
            'kat_nama'        => $kat_names[$kat] ?? 'Dokumen Sekretariat',
            'active'          => ($filter !== null && $filter !== '') ? (int)$filter : 1,
            'tahun'           => date('Y'),
            'jenis_peraturan' => $jenis_peraturan,
            'list_tahun'      => $list_tahun,
            'selected_nav'    => $selected_nav,
            'controller'      => 'dokumen_sekretariat',
            'main_content'    => 'admin.dokumen.buku_kades.table_buku_umum',
            'desa'            => $this->desa,
        ];

        $this->renderBlade('admin.bumindes.umum.main', $data);
    }

    public function form($kat = 3, $id = null)
    {
        $kat = (int)($kat ?: 3);
        $dokumen = [
            'id'                  => '',
            'nama'                => '',
            'satuan'              => '',
            'kategori'            => $kat,
            'attr'                => '',
            'tipe'                => 1,
            'url'                 => '',
            'tahun'               => date('Y'),
            'kategori_info_publik'=> 1,
            'enabled'             => 1,
            'lokasi_arsip'        => '',
            'keterangan'          => '',
        ];

        $attr_data = [
            'jenis_peraturan' => '',
            'no_ditetapkan'   => '',
            'tgl_ditetapkan'  => '',
            'uraian_singkat'  => '',
            'no_kesepakatan'  => '',
            'tgl_kesepakatan' => '',
            'no_lapor'        => '',
            'tgl_lapor'       => '',
            'no_undangan'     => '',
            'tgl_undangan'    => '',
            'no_berita'       => '',
            'tgl_berita'      => '',
            'no_kep_kades'    => '',
            'tgl_kep_kades'   => '',
        ];

        if ($id && $this->db_real) {
            $id = (int)$id;
            try {
                $q = $this->db_real->query("SELECT * FROM dokumen WHERE id = {$id} LIMIT 1");
                if ($q && $q->num_rows() > 0) {
                    $row = $q->row_array();
                    $dokumen = array_merge($dokumen, $row);
                    if (!empty($row['attr'])) {
                        $json = json_decode($row['attr'], true);
                        if (is_array($json)) {
                            $attr_data = array_merge($attr_data, $json);
                        }
                    }
                }
            } catch (\Throwable $e) {}
        }

        $data = [
            'kat'             => $kat,
            'dokumen'         => $dokumen,
            'attr'            => $attr_data,
            'action'          => $id ? 'update' : 'insert',
            'form_action'     => site_url("dokumen_sekretariat/" . ($id ? "update/{$id}" : "insert/{$kat}")),
            'jenis_peraturan' => [
                'Peraturan Desa',
                'Peraturan Bersama Kepala Desa',
                'Peraturan Kepala Desa',
            ],
            'desa'            => $this->desa,
        ];

        $this->renderBlade('admin.dokumen.buku_kades.form', $data);
    }

    public function datatables()
    {
        header('Content-Type: application/json');

        if (!$this->db_real) {
            echo json_encode(['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []]);
            return;
        }

        $draw      = $this->input->post('draw') ?: 1;
        $start     = $this->input->post('start') ?: 0;
        $length    = $this->input->post('length') ?: 10;
        $search    = $this->input->post('search')['value'] ?? '';
        $kategori  = (int)($this->input->post('kategori') ?: 3);
        $filter    = $this->input->post('filter');
        $tahun     = $this->input->post('tahun');

        try {
            $where = ["(d.deleted = 0 OR d.deleted IS NULL)", "d.kategori = " . (int)$kategori];

            if ($filter !== null && $filter !== '') {
                $where[] = "d.enabled = " . (int)$filter;
            }

            if ($tahun !== null && $tahun !== '') {
                $where[] = "d.tahun = " . (int)$tahun;
            }

            if (!empty($search)) {
                $s_esc = $this->db_real->escape_like_str($search);
                $where[] = "(d.nama LIKE '%{$s_esc}%' OR d.attr LIKE '%{$s_esc}%')";
            }

            $whereClause = 'WHERE ' . implode(' AND ', $where);

            $totQ = $this->db_real->query("SELECT COUNT(*) as total FROM dokumen WHERE (deleted = 0 OR deleted IS NULL) AND kategori = {$kategori}");
            $recordsTotal = ($totQ && $totQ->num_rows() > 0) ? (int)$totQ->row()->total : 0;

            $countQ = $this->db_real->query("SELECT COUNT(*) as total FROM dokumen d {$whereClause}");
            $recordsFiltered = ($countQ && $countQ->num_rows() > 0) ? (int)$countQ->row()->total : 0;

            $sql = "SELECT d.* FROM dokumen d {$whereClause} ORDER BY d.id DESC LIMIT " . (int)$start . ", " . (int)$length;
            $resQ = $this->db_real->query($sql);

            $rows = ($resQ && $resQ->num_rows() > 0) ? $resQ->result_array() : [];

            $data = [];
            $no = (int)$start + 1;

            foreach ($rows as $row) {
                $attr = !empty($row['attr']) ? json_decode($row['attr'], true) : [];
                if (!is_array($attr)) $attr = [];

                $id = $row['id'];
                $nama = htmlspecialchars($row['nama'] ?? '-', ENT_QUOTES, 'UTF-8');

                $ceklist = '<input type="checkbox" name="id_cb[]" value="' . $id . '" class="cb-row" />';

                $edit_url = site_url("dokumen_sekretariat/form/{$kategori}/{$id}");
                $del_url  = site_url("dokumen_sekretariat/delete/{$kategori}/{$id}");

                $aksi = '<div class="btn-group" style="display:inline-flex;gap:4px;">
                    <a href="' . $edit_url . '" class="btn btn-sm" style="background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;border-radius:8px;padding:4px 8px;" title="Ubah Dokumen">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a href="' . $del_url . '" class="btn btn-sm btn-hapus-row" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-radius:8px;padding:4px 8px;" title="Hapus Dokumen" onclick="return confirm(\'Yakin ingin menghapus dokumen ini?\');">
                        <i class="fa fa-trash-o"></i>
                    </a>
                </div>';

                $status_badge = ((int)$row['enabled'] === 1)
                    ? '<span class="badge" style="background:#ecfdf5;color:#059669;border:1px solid #a7f3d0;border-radius:12px;padding:4px 10px;font-size:11px;font-weight:700;">Berlaku</span>'
                    : '<span class="badge" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-radius:12px;padding:4px 10px;font-size:11px;font-weight:700;">Dicabut</span>';

                $tgl_upload = !empty($row['tgl_upload']) ? date('d/m/Y H:i', strtotime($row['tgl_upload'])) : '-';

                $rowData = [
                    'ceklist'      => $ceklist,
                    'DT_RowIndex'  => $no++,
                    'aksi'         => $aksi,
                    'nama'         => $nama,
                    'enabled'      => $status_badge,
                    'tgl_upload'   => $tgl_upload,
                    'additional'   => [
                        'kategori_info_publik' => $row['kategori_info_publik'] ?? '-',
                        'tahun'                => $row['tahun'] ?? '-',
                        'jenis_peraturan'      => $attr['jenis_peraturan'] ?? '-',
                        'tgl_ditetapkan'       => (!empty($attr['no_ditetapkan']) || !empty($attr['tgl_ditetapkan'])) ? ($attr['no_ditetapkan'] ?? '-') . ' / ' . ($attr['tgl_ditetapkan'] ?? '-') : '-',
                        'uraian_singkat'       => $attr['uraian_singkat'] ?? '-',
                        'tgl_keputusan'        => (!empty($attr['no_kep_kades']) || !empty($attr['tgl_kep_kades'])) ? ($attr['no_kep_kades'] ?? '-') . ' / ' . ($attr['tgl_kep_kades'] ?? '-') : '-',
                    ]
                ];

                $data[] = $rowData;
            }

            echo json_encode([
                'draw'            => (int)$draw,
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $data,
            ]);
        } catch (\Throwable $e) {
            echo json_encode([
                'draw'            => (int)$draw,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => [],
                'error'           => $e->getMessage(),
            ]);
        }
    }
}

if (!class_exists('Dokumen_sekretariat')) {
    class Dokumen_sekretariat extends Dokumen_sekretariat_neural {}
}
