<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * XOSID - BUMDes Profil Controller (Dashboard & Legalitas)
 * Directly matches the /bumdes_profil route for instant CodeIgniter resolution
 */
class Bumdes_profil extends CI_Controller
{
    private $db_real = null;
    private $desa = [];

    public function __construct()
    {
        @file_put_contents(FCPATH . 'storage/logs/xosid_bumdes_error.log',
            "[" . date('Y-m-d H:i:s') . "] BUMDES PROFIL CONTROLLER CONSTRUCT CALLED\n",
            FILE_APPEND);
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE) { @session_start(); }
        $this->load->helper('url');
        
        if (empty($_SESSION['siteman']) || $_SESSION['siteman'] != 1 || empty($_SESSION['sesi']) || $_SESSION['sesi'] !== 'aktif') {
            header('Location: ' . site_url('siteman'));
            exit;
        }
        
        // Mock CI session
        $this->session = new class {
            public function userdata($key) { return $_SESSION[$key] ?? null; }
            public function set_userdata($key, $val) { $_SESSION[$key] = $val; }
            public function flashdata($key) { return null; }
            public function set_flashdata($key, $val) {}
            public function all_userdata() { return $_SESSION ?? []; }
        };
        
        // Smart DB Bridge for sidebar menu loading
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
                
                // Inject CSRF tokens
                $data['token_name'] = $this->security->get_csrf_token_name();
                $data['token_value'] = $this->security->get_csrf_hash();
                
                $output = $app->make('view')->make($viewName, $data)->render();
            }
        } catch (\Throwable $e) {
            @file_put_contents(FCPATH . 'storage/logs/xosid_bumdes_error.log',
                "[" . date('Y-m-d H:i:s') . "] RENDER ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n" . $e->getTraceAsString() . "\n",
                FILE_APPEND);
        }

        if ($output) {
            echo $output;
        } else {
            echo '<div style="color:red;padding:20px;">Error rendering BUMDes view: ' . htmlspecialchars($viewName) . '. Check storage/logs/xosid_bumdes_error.log</div>';
        }
    }

    public function index()
    {
        if ($this->input->method() === 'post') {
            $this->profil_save();
            return;
        }

        // Fetch BUMDes profil row
        $profil = [];
        if ($this->db_real) {
            $q = $this->db_real->get('bumdes_profil');
            $profil = ($q && $q->num_rows() > 0) ? $q->row_array() : [];
        }

        // Calculate dynamic health rating based on PP No. 11/2021 & Kemendesa standards
        $score_legal = 0;
        $score_organ = 0;
        $score_produk = 0;
        $score_keuangan = 0;

        $has_badan_hukum = !empty($profil['nomor_badan_hukum']);
        $has_sk_pendirian = !empty($profil['sk_pendirian']);
        $has_ad_art = !empty($profil['ad_art_path']);
        $has_sk_pengurus = !empty($profil['sk_pengurus_path']);

        if ($has_badan_hukum) $score_legal += 10;
        if ($has_sk_pendirian) $score_legal += 10;
        if ($has_ad_art) $score_legal += 5;
        if ($has_sk_pengurus) $score_legal += 5;

        $has_lengkap_organ = (!empty($profil['nama_penasihat']) && !empty($profil['nama_direktur']) && !empty($profil['nama_sekretaris']) && !empty($profil['nama_bendahara']));
        $has_unit_usaha = !empty($profil['unit_usaha']);

        if ($has_lengkap_organ) $score_organ += 10;
        if ($has_unit_usaha) $score_organ += 10;

        $product_count = 0;
        $trans_count = 0;
        if ($this->db_real) {
            try {
                $product_count = $this->db_real->count_all_results('bumdes_produk');
                $trans_count = $this->db_real->count_all_results('bumdes_transaksi');
            } catch (\Throwable $e) {}
        }

        if ($product_count >= 5) {
            $score_produk = 25;
        } elseif ($product_count > 0) {
            $score_produk = 15;
        }

        if ($trans_count >= 10) {
            $score_keuangan = 25;
        } elseif ($trans_count > 0) {
            $score_keuangan = 15;
        }

        $total_score = $score_legal + $score_organ + $score_produk + $score_keuangan;

        if ($total_score >= 80) {
            $health_status = 'sehat';
        } elseif ($total_score >= 60) {
            $health_status = 'cukup_sehat';
        } elseif ($total_score >= 40) {
            $health_status = 'kurang_sehat';
        } else {
            $health_status = 'tidak_sehat';
        }

        // Auto-save calculated health status to DB to sync the value
        if ($this->db_real && !empty($profil) && ($profil['status_kesehatan'] ?? '') !== $health_status) {
            try {
                $this->db_real->where('id', $profil['id'])->update('bumdes_profil', ['status_kesehatan' => $health_status]);
                $profil['status_kesehatan'] = $health_status;
            } catch (\Throwable $e) {}
        }

        $data = [
            'desa' => $this->desa,
            'profil' => $profil,
            'title' => 'Dashboard & Legalitas BUMDes',
            'health_score' => $total_score,
            'health_status' => $health_status,
            'health_breakdown' => [
                'has_badan_hukum' => $has_badan_hukum,
                'has_sk_pendirian' => $has_sk_pendirian,
                'has_ad_art' => $has_ad_art,
                'has_sk_pengurus' => $has_sk_pengurus,
                'has_lengkap_organ' => $has_lengkap_organ,
                'has_unit_usaha' => $has_unit_usaha,
                'product_count' => $product_count,
                'trans_count' => $trans_count,
                'score_legal' => $score_legal,
                'score_organ' => $score_organ,
                'score_produk' => $score_produk,
                'score_keuangan' => $score_keuangan,
            ]
        ];

        $this->renderBlade('admin.bumdes.profil', $data);
    }

    private function profil_save()
    {
        if (!$this->db_real) {
            header('Location: ' . site_url('bumdes_profil'));
            exit;
        }

        $nama_bumdes = $this->input->post('nama_bumdes');
        $nomor_badan_hukum = $this->input->post('nomor_badan_hukum');
        $sk_pendirian = $this->input->post('sk_pendirian');
        $tanggal_berdiri = $this->input->post('tanggal_berdiri') ?: null;
        
        $penasihat_val = $this->input->post('nama_penasihat');
        $direktur_val = $this->input->post('nama_direktur');
        $sekretaris_val = $this->input->post('nama_sekretaris');
        $bendahara_val = $this->input->post('nama_bendahara');
        $pengawas_val = $this->input->post('nama_pengawas');
        $unit_usaha = $this->input->post('unit_usaha');

        $penasihat_id = null; $nama_penasihat = $penasihat_val;
        $direktur_id = null; $nama_direktur = $direktur_val;
        $sekretaris_id = null; $nama_sekretaris = $sekretaris_val;
        $bendahara_id = null; $nama_bendahara = $bendahara_val;
        $pengawas_id = null; $nama_pengawas = $pengawas_val;

        // Resolve ID vs Custom Name
        $resolveRole = function($val, &$id, &$name) {
            if (empty($val)) {
                $id = null;
                $name = '';
                return;
            }
            if (is_numeric($val)) {
                $q = $this->db_real->select('nama')->where('id', $val)->get('tweb_penduduk');
                if ($q && $q->num_rows() > 0) {
                    $id = (int)$val;
                    $name = $q->row()->nama;
                    return;
                }
            }
            $id = null;
            $name = $val;
        };

        $resolveRole($penasihat_val, $penasihat_id, $nama_penasihat);
        $resolveRole($direktur_val, $direktur_id, $nama_direktur);
        $resolveRole($sekretaris_val, $sekretaris_id, $nama_sekretaris);
        $resolveRole($bendahara_val, $bendahara_id, $nama_bendahara);
        $resolveRole($pengawas_val, $pengawas_id, $nama_pengawas);

        $data = [
            'nama_bumdes' => $nama_bumdes,
            'nomor_badan_hukum' => $nomor_badan_hukum,
            'sk_pendirian' => $sk_pendirian,
            'tanggal_berdiri' => $tanggal_berdiri,
            'penasihat_id' => $penasihat_id,
            'nama_penasihat' => $nama_penasihat,
            'direktur_id' => $direktur_id,
            'nama_direktur' => $nama_direktur,
            'sekretaris_id' => $sekretaris_id,
            'nama_sekretaris' => $nama_sekretaris,
            'bendahara_id' => $bendahara_id,
            'nama_bendahara' => $nama_bendahara,
            'pengawas_id' => $pengawas_id,
            'nama_pengawas' => $nama_pengawas,
            'unit_usaha' => $unit_usaha,
        ];

        // Handle file upload
        $config['upload_path'] = './uploads/bumdes/';
        $config['allowed_types'] = 'pdf|doc|docx|png|jpg|jpeg';
        $config['max_size'] = 5120; // 5MB

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('ad_art')) {
            $upload_data = $this->upload->data();
            $data['ad_art_path'] = 'uploads/bumdes/' . $upload_data['file_name'];
        }

        if ($this->upload->do_upload('sk_pengurus')) {
            $upload_data = $this->upload->data();
            $data['sk_pengurus_path'] = 'uploads/bumdes/' . $upload_data['file_name'];
        }

        // Check if row exists
        $q = $this->db_real->get('bumdes_profil');
        if ($q && $q->num_rows() > 0) {
            $row = $q->row();
            $this->db_real->where('id', $row->id)->update('bumdes_profil', $data);
        } else {
            $this->db_real->insert('bumdes_profil', $data);
        }

        header('Location: ' . site_url('bumdes_profil'));
        exit;
    }

    /**
     * API Penduduk for Select2 autocomplete in BUMDes Organ form
     */
    public function apipenduduk()
    {
        $search = $this->input->get('q') ?: '';
        $results = [];

        @file_put_contents(FCPATH . 'storage/logs/xosid_bumdes_error.log',
            "[" . date('Y-m-d H:i:s') . "] apipenduduk entry. Search: '" . $search . "', DB: " . (empty($this->db_real) ? 'EMPTY' : 'LOADED') . "\n",
            FILE_APPEND);

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
                @file_put_contents(FCPATH . 'storage/logs/xosid_bumdes_error.log',
                    "[" . date('Y-m-d H:i:s') . "] Query: " . $this->db_real->last_query() . " | Count: " . count($results) . "\n",
                    FILE_APPEND);
            } catch (\Throwable $e) {
                @file_put_contents(FCPATH . 'storage/logs/xosid_bumdes_error.log',
                    "[" . date('Y-m-d H:i:s') . "] apipenduduk exception: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n",
                    FILE_APPEND);
            }
        }

        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }
}
