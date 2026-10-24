<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bumdes_keuangan extends CI_Controller
{
    private $db_real = null;
    private $desa = [];

    public function __construct()
    {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE) { @session_start(); }
        $this->load->helper('url');
        
        if (empty($_SESSION['siteman']) || $_SESSION['siteman'] != 1 || empty($_SESSION['sesi']) || $_SESSION['sesi'] !== 'aktif') {
            header('Location: ' . site_url('siteman'));
            exit;
        }
        
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

        try {
            $this->db_real = $this->load->database('default', TRUE);
        } catch (\Throwable $e) {}

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
        $accounts = [];
        $journals = [];
        $summary = [
            'total_aset' => 0,
            'total_pendapatan' => 0,
            'total_beban' => 0,
            'laba_bersih' => 0
        ];
        $anggaran = [];

        if ($this->db_real) {
            try {
                $res_acct = $this->db_real->order_by('kode_akun', 'ASC')->get('bumdes_akun')->result_array();
                
                $balances = [];
                $res_details = $this->db_real
                    ->select('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
                    ->from('bumdes_jurnal_detail')
                    ->group_by('akun_id')
                    ->get()
                    ->result_array();
                foreach ($res_details as $det) {
                    $balances[$det['akun_id']] = [
                        'debit' => (float)$det['total_debit'],
                        'kredit' => (float)$det['total_kredit']
                    ];
                }

                foreach ($res_acct as $acct) {
                    $acct_id = $acct['id'];
                    $deb = $balances[$acct_id]['debit'] ?? 0.0;
                    $kre = $balances[$acct_id]['kredit'] ?? 0.0;
                    
                    if ($acct['saldo_normal'] === 'debit') {
                        $acct['saldo'] = $deb - $kre;
                    } else {
                        $acct['saldo'] = $kre - $deb;
                    }

                    if ($acct['kelompok'] === 'aset') {
                        if (in_array($acct['kode_akun'], ['1.1.1.01', '1.1.2.01'])) {
                            $summary['total_aset'] += $acct['saldo'];
                        }
                    } elseif ($acct['kelompok'] === 'pendapatan') {
                        $summary['total_pendapatan'] += $acct['saldo'];
                    } elseif ($acct['kelompok'] === 'beban') {
                        $summary['total_beban'] += $acct['saldo'];
                    }
                    
                    $accounts[] = $acct;
                }
                $summary['laba_bersih'] = $summary['total_pendapatan'] - $summary['total_beban'];

                $res_journals = $this->db_real->order_by('tanggal', 'DESC')->order_by('id', 'DESC')->get('bumdes_jurnal')->result_array();
                foreach ($res_journals as $jr) {
                    $details = $this->db_real
                        ->select('jd.*, a.nama_akun, a.kode_akun')
                        ->from('bumdes_jurnal_detail jd')
                        ->join('bumdes_akun a', 'jd.akun_id = a.id')
                        ->where('jd.jurnal_id', $jr['id'])
                        ->get()
                        ->result_array();
                    $jr['details'] = $details;
                    $journals[] = $jr;
                }

                $res_budget = $this->db_real
                    ->select('ba.*, a.nama_akun, a.kode_akun, a.saldo_normal, a.kelompok')
                    ->from('bumdes_anggaran ba')
                    ->join('bumdes_akun a', 'ba.akun_id = a.id')
                    ->where('ba.tahun', 2026)
                    ->get()
                    ->result_array();
                foreach ($res_budget as $b) {
                    $real = $this->db_real
                        ->select('SUM(debit) as deb, SUM(kredit) as kre')
                        ->from('bumdes_jurnal_detail jd')
                        ->join('bumdes_jurnal j', 'jd.jurnal_id = j.id')
                        ->where('jd.akun_id', $b['akun_id'])
                        ->where('YEAR(j.tanggal)', 2026)
                        ->get()
                        ->row_array();
                    
                    $deb_val = (float)($real['deb'] ?? 0);
                    $kre_val = (float)($real['kre'] ?? 0);

                    if ($b['saldo_normal'] === 'debit') {
                        $b['realisasi'] = $deb_val - $kre_val;
                    } else {
                        $b['realisasi'] = $kre_val - $deb_val;
                    }
                    $anggaran[] = $b;
                }

            } catch (\Throwable $e) {}
        }

        $data = [
            'desa' => $this->desa,
            'title' => 'Keuangan & Akuntansi BUMDes',
            'accounts' => $accounts,
            'journals' => $journals,
            'summary' => $summary,
            'anggaran' => $anggaran
        ];
        $this->renderBlade('admin.bumdes.keuangan', $data);
    }

    public function save_jurnal()
    {
        if (!$this->db_real) {
            header('Location: ' . site_url('bumdes_keuangan'));
            exit;
        }

        $tanggal = $this->input->post('tanggal') ?: date('Y-m-d');
        $keterangan = $this->input->post('keterangan');
        $referensi = $this->input->post('referensi');
        
        $akun_ids = $this->input->post('akun_id');
        $debits = $this->input->post('debit');
        $kredits = $this->input->post('kredit');

        if (empty($akun_ids) || count($akun_ids) < 2) {
            header('Location: ' . site_url('bumdes_keuangan'));
            exit;
        }

        // Validate double entry balance
        $total_debit = 0;
        $total_kredit = 0;
        $lines = [];

        for ($i = 0; $i < count($akun_ids); $i++) {
            $deb_val = (float)($debits[$i] ?? 0);
            $kre_val = (float)($kredits[$i] ?? 0);
            
            if ($deb_val > 0 || $kre_val > 0) {
                $total_debit += $deb_val;
                $total_kredit += $kre_val;
                
                $lines[] = [
                    'akun_id' => $akun_ids[$i],
                    'debit' => $deb_val,
                    'kredit' => $kre_val
                ];
            }
        }

        // Balance check (allow small precision diffs)
        if (abs($total_debit - $total_kredit) > 0.01 || $total_debit <= 0) {
            // Unbalanced! Show error in log or redirect
            header('Location: ' . site_url('bumdes_keuangan'));
            exit;
        }

        $this->db_real->trans_begin();
        try {
            $nomor_jurnal = 'JV-' . date('Ymd', strtotime($tanggal)) . '-' . strtoupper(substr(uniqid(), -4));

            $header = [
                'nomor_jurnal' => $nomor_jurnal,
                'tanggal' => $tanggal,
                'keterangan' => $keterangan,
                'referensi' => $referensi,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->db_real->insert('bumdes_jurnal', $header);
            $jurnal_id = $this->db_real->insert_id();

            foreach ($lines as $line) {
                $line['jurnal_id'] = $jurnal_id;
                $this->db_real->insert('bumdes_jurnal_detail', $line);
            }

            if ($this->db_real->trans_status() === FALSE) {
                $this->db_real->trans_rollback();
            } else {
                $this->db_real->trans_commit();
            }
        } catch (\Throwable $e) {
            $this->db_real->trans_rollback();
        }

        header('Location: ' . site_url('bumdes_keuangan'));
        exit;
    }

    public function delete_jurnal($id)
    {
        if ($this->db_real && !empty($id)) {
            // Jurnal otomatis POS tidak dapat dihapus manual
            $jr = $this->db_real->where('id', $id)->get('bumdes_jurnal')->row_array();
            if ($jr && strpos($jr['nomor_jurnal'], 'JV-INV-BUMDES-') !== 0) {
                $this->db_real->where('id', $id)->delete('bumdes_jurnal');
            }
        }
        header('Location: ' . site_url('bumdes_keuangan'));
        exit;
    }

    public function save_anggaran()
    {
        if (!$this->db_real) {
            header('Location: ' . site_url('bumdes_keuangan'));
            exit;
        }

        $tahun = (int)$this->input->post('tahun') ?: 2026;
        $akun_id = $this->input->post('akun_id');
        $jumlah_anggaran = (float)$this->input->post('jumlah_anggaran');

        if ($akun_id && $jumlah_anggaran > 0) {
            $data = [
                'tahun' => $tahun,
                'akun_id' => $akun_id,
                'jumlah_anggaran' => $jumlah_anggaran
            ];
            $this->db_real->replace('bumdes_anggaran', $data);
        }

        header('Location: ' . site_url('bumdes_keuangan'));
        exit;
    }
}
