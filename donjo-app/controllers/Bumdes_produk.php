<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bumdes_produk extends CI_Controller
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
        $categories = [];
        $products = [];

        if ($this->db_real) {
            try {
                $categories = $this->db_real->order_by('nama_kategori', 'ASC')->get('bumdes_kategori')->result_array();
                
                $products = $this->db_real
                    ->select('p.*, k.nama_kategori')
                    ->from('bumdes_produk p')
                    ->join('bumdes_kategori k', 'p.kategori_id = k.id', 'left')
                    ->order_by('p.id', 'DESC')
                    ->get()
                    ->result_array();
            } catch (\Throwable $e) {}
        }

        $data = [
            'desa' => $this->desa,
            'title' => 'Katalog & Stok Produk BUMDes',
            'categories' => $categories,
            'products' => $products
        ];
        $this->renderBlade('admin.bumdes.produk', $data);
    }

    public function save()
    {
        if (!$this->db_real) {
            header('Location: ' . site_url('bumdes_produk'));
            exit;
        }

        $id = $this->input->post('id');
        $kategori_id = $this->input->post('kategori_id');
        $nama_produk = $this->input->post('nama_produk');
        $sku = $this->input->post('sku') ?: 'SKU-' . strtoupper(uniqid());
        $deskripsi = $this->input->post('deskripsi');
        $harga_beli = (float)$this->input->post('harga_beli');
        $harga_jual = (float)$this->input->post('harga_jual');
        $stok = (int)$this->input->post('stok');
        $satuan = $this->input->post('satuan') ?: 'pcs';
        $is_hagtou_katalog = $this->input->post('is_hagtou_katalog') ? 1 : 0;

        $data = [
            'kategori_id' => $kategori_id,
            'nama_produk' => $nama_produk,
            'sku' => $sku,
            'deskripsi' => $deskripsi,
            'harga_beli' => $harga_beli,
            'harga_jual' => $harga_jual,
            'stok' => $stok,
            'satuan' => $satuan,
            'is_hagtou_katalog' => $is_hagtou_katalog,
        ];

        // Image upload
        if (!empty($_FILES['foto_produk']['name'])) {
            $config['upload_path'] = './uploads/bumdes/produk/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = 'prod_' . time();

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, true);
            }

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('foto_produk')) {
                $upload_data = $this->upload->data();
                $data['foto_produk'] = 'uploads/bumdes/produk/' . $upload_data['file_name'];
            }
        }

        if (!empty($id)) {
            // Get old product to log stock adjustment
            $old_prod = $this->db_real->where('id', $id)->get('bumdes_produk')->row();
            if ($old_prod) {
                $diff = $stok - (int)$old_prod->stok;
                if ($diff !== 0) {
                    $log_data = [
                        'produk_id' => $id,
                        'tipe' => $diff > 0 ? 'in' : 'out',
                        'jumlah' => abs($diff),
                        'keterangan' => 'Penyesuaian stok manual dari katalog'
                    ];
                    $this->db_real->insert('bumdes_stok_log', $log_data);
                }
            }
            $this->db_real->where('id', $id)->update('bumdes_produk', $data);
        } else {
            $this->db_real->insert('bumdes_produk', $data);
            $new_id = $this->db_real->insert_id();
            // Log initial stock
            if ($stok > 0) {
                $log_data = [
                    'produk_id' => $new_id,
                    'tipe' => 'in',
                    'jumlah' => $stok,
                    'keterangan' => 'Stok awal produk'
                ];
                $this->db_real->insert('bumdes_stok_log', $log_data);
            }
        }

        header('Location: ' . site_url('bumdes_produk'));
        exit;
    }

    public function delete($id)
    {
        if ($this->db_real && !empty($id)) {
            $this->db_real->where('id', $id)->delete('bumdes_produk');
        }
        header('Location: ' . site_url('bumdes_produk'));
        exit;
    }

    public function save_kategori()
    {
        if (!$this->db_real) {
            header('Location: ' . site_url('bumdes_produk'));
            exit;
        }

        $id = $this->input->post('id');
        $nama_kategori = $this->input->post('nama_kategori');
        $deskripsi = $this->input->post('deskripsi');
        
        $this->load->helper('url');
        $slug = url_title(strtolower($nama_kategori), '-', TRUE);

        $data = [
            'nama_kategori' => $nama_kategori,
            'deskripsi' => $deskripsi,
            'slug' => $slug
        ];

        if (!empty($id)) {
            $this->db_real->where('id', $id)->update('bumdes_kategori', $data);
        } else {
            $this->db_real->insert('bumdes_kategori', $data);
        }

        header('Location: ' . site_url('bumdes_produk'));
        exit;
    }

    public function delete_kategori($id)
    {
        if ($this->db_real && !empty($id)) {
            $this->db_real->where('id', $id)->delete('bumdes_kategori');
        }
        header('Location: ' . site_url('bumdes_produk'));
        exit;
    }
}
