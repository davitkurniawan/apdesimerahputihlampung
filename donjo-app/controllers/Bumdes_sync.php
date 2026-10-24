<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bumdes_sync extends CI_Controller
{
    private $db_real = null;
    private $desa = [];

    public function __construct()
    {
        parent::__construct();
        if (session_status() === PHP_SESSION_NONE) { @session_start(); }
        $this->load->helper('url');
        
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
                    $real_db = $this->ci->load->database('default', TRUE, TRUE);
                    $query = $real_db->select('*')->from('setting_modul')->where('aktif', 1)->where('hidden', 0)->order_by('urut', 'ASC')->get();
                    if ($query && $query->num_rows() > 0) return $query->result_array();
                } catch (\Exception $e) {}
                return [];
            }
        };

        try {
            $this->db_real = $this->load->database('default', TRUE, TRUE);
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
        if (empty($_SESSION['siteman']) || $_SESSION['siteman'] != 1 || empty($_SESSION['sesi']) || $_SESSION['sesi'] !== 'aktif') {
            header('Location: ' . site_url('siteman'));
            exit;
        }
        $products = [];
        $stats = [
            'total' => 0,
            'synced' => 0,
            'pending' => 0,
            'disabled' => 0
        ];

        if ($this->db_real) {
            try {
                $query = $this->db_real->query("
                    SELECT p.*, c.nama_kategori
                    FROM bumdes_produk p
                    LEFT JOIN bumdes_kategori c ON p.kategori_id = c.id
                    ORDER BY p.id DESC
                ");
                $products = $query ? $query->result_array() : [];

                foreach ($products as $p) {
                    $stats['total']++;
                    if ($p['is_hagtou_katalog'] == 1) {
                        if ($p['sync_status'] === 'synced') {
                            $stats['synced']++;
                        } else {
                            $stats['pending']++;
                        }
                    } else {
                        $stats['disabled']++;
                    }
                }
            } catch (\Throwable $e) {}
        }

        $data = [
            'desa' => $this->desa,
            'title' => 'Koneksi Hagtou BUMDes',
            'products' => $products,
            'stats' => $stats
        ];
        $this->renderBlade('admin.bumdes.sync', $data);
    }

    public function toggle_sync($id)
    {
        if (empty($_SESSION['siteman']) || $_SESSION['siteman'] != 1 || empty($_SESSION['sesi']) || $_SESSION['sesi'] !== 'aktif') {
            header('Location: ' . site_url('siteman'));
            exit;
        }
        if ($this->db_real && !empty($id)) {
            $query = $this->db_real->query("SELECT * FROM bumdes_produk WHERE id = ?", [$id]);
            $prod = $query ? $query->row_array() : null;
            if ($prod) {
                $next_val = ($prod['is_hagtou_katalog'] == 1) ? 0 : 1;
                $status = ($next_val == 1) ? 'pending' : null;
                
                $this->db_real->query("
                    UPDATE bumdes_produk 
                    SET is_hagtou_katalog = ?, sync_status = ? 
                    WHERE id = ?
                ", [$next_val, $status, $id]);
            }
        }
        header('Location: ' . site_url('bumdes_sync'));
        exit;
    }

    public function sync_all()
    {
        @file_put_contents(FCPATH . 'storage/logs/xosid_bumdes_error.log',
            "[" . date('Y-m-d H:i:s') . "] SYNC_ALL CALLED. DB_REAL=" . ($this->db_real ? 'OK' : 'NULL') . "\n",
            FILE_APPEND);
        if (!$this->db_real) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Database connection offline.']);
            return;
        }

        $query = $this->db_real->query("
            SELECT * FROM bumdes_produk 
            WHERE is_hagtou_katalog = 1 
              AND sync_status = 'pending'
        ");
        $pending_products = $query ? $query->result_array() : [];

        if (empty($pending_products)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Tidak ada produk baru untuk disinkronisasi.', 'synced_count' => 0]);
            return;
        }

        // Simulate Central Hagtou API payload
        $payload = [
            'village_id' => $this->desa['id_desa'] ?? '999',
            'village_name' => $this->desa['nama_desa'] ?? 'Desa Demo',
            'products' => []
        ];

        foreach ($pending_products as $p) {
            $payload['products'][] = [
                'tenant_product_id' => $p['id'],
                'sku' => $p['sku'],
                'nama' => $p['nama_produk'],
                'harga' => (float)$p['harga_jual'],
                'stok' => (int)$p['stok'],
                'deskripsi' => $p['deskripsi']
            ];
        }

        // Send real API request to Hagtou
        $ch = curl_init('http://127.0.0.1/api/catalog/sync');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Host: hagtou.localhost'
        ]);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $res_data = json_decode($response, true);
        if ($http_code !== 200 || !$res_data || ($res_data['status'] ?? '') !== 'success') {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal sinkronisasi ke Hagtou: ' . ($res_data['message'] ?? 'Koneksi error/invalid response.'),
                'response_raw' => $response
            ]);
            return;
        }

        // Update DB status upon successful API sync
        $this->db_real->query("START TRANSACTION");
        try {
            foreach ($pending_products as $p) {
                $this->db_real->query("
                    UPDATE bumdes_produk 
                    SET sync_status = 'synced' 
                    WHERE id = ?
                ", [$p['id']]);
            }

            $this->db_real->query("COMMIT");
            
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Berhasil mensinkronisasikan ' . count($pending_products) . ' produk ke platform pusat Hagtou!',
                'synced_count' => count($pending_products),
                'payload_preview' => $payload
            ]);
        } catch (\Throwable $e) {
            $this->db_real->query("ROLLBACK");
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }
}
