<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bumdes_pos extends CI_Controller
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
        if (empty($_SESSION['siteman']) || $_SESSION['siteman'] != 1 || empty($_SESSION['sesi']) || $_SESSION['sesi'] !== 'aktif') {
            header('Location: ' . site_url('siteman'));
            exit;
        }
        $categories = [];
        $products = [];

        if ($this->db_real) {
            try {
                $categories = $this->db_real->order_by('nama_kategori', 'ASC')->get('bumdes_kategori')->result_array();
                
                $products = $this->db_real
                    ->select('p.*, k.nama_kategori')
                    ->from('bumdes_produk p')
                    ->join('bumdes_kategori k', 'p.kategori_id = k.id', 'left')
                    ->order_by('p.nama_produk', 'ASC')
                    ->get()
                    ->result_array();
            } catch (\Throwable $e) {}
        }

        $data = [
            'desa' => $this->desa,
            'title' => 'Transaksi Kasir (POS) BUMDes',
            'categories' => $categories,
            'products' => $products
        ];
        $this->renderBlade('admin.bumdes.pos', $data);
    }

    public function checkout()
    {
        if (!$this->db_real) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Koneksi database tidak tersedia.']);
            exit;
        }

        $cart_json = $this->input->post('cart_json');
        $cart = json_decode($cart_json, true);

        if (empty($cart)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Keranjang belanja kosong atau format tidak valid.']);
            exit;
        }

        $customer_type = $this->input->post('customer_type') ?: 'umum';
        $customer_id = $this->input->post('customer_id');
        $customer_name_umum = $this->input->post('customer_name_umum') ?: 'Umum';
        $discount = (float)($this->input->post('discount') ?: 0);
        $payment_method = $this->input->post('payment_method') ?: 'tunai';

        $this->db_real->trans_begin();

        try {
            $total_price = 0;
            $items_to_save = [];

            foreach ($cart as $item) {
                $product = $this->db_real->where('id', $item['id'])->get('bumdes_produk')->row_array();
                if (!$product) {
                    throw new \Exception('Produk tidak ditemukan.');
                }

                $qty = (int)$item['qty'];
                if ($qty <= 0) {
                    throw new \Exception('Kuantitas produk tidak valid.');
                }

                if ($product['satuan'] !== 'tiket' && (int)$product['stok'] < $qty) {
                    throw new \Exception('Stok produk ' . $product['nama_produk'] . ' tidak mencukupi (Tersedia: ' . $product['stok'] . ').');
                }

                $subtotal = $product['harga_jual'] * $qty;
                $total_price += $subtotal;

                $items_to_save[] = [
                    'produk_id' => $product['id'],
                    'qty' => $qty,
                    'nama_produk' => $product['nama_produk'],
                    'harga_satuan' => $product['harga_jual'],
                    'harga_beli_satuan' => $product['harga_beli'],
                    'subtotal' => $subtotal
                ];
            }

            $invoice_number = 'INV-BUMDES-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $header_data = [
                'invoice_number' => $invoice_number,
                'penduduk_id' => $customer_type === 'penduduk' ? $customer_id : null,
                'nama_pelanggan_umum' => $customer_type === 'umum' ? $customer_name_umum : null,
                'total_harga' => $total_price,
                'diskon' => $discount,
                'metode_pembayaran' => $payment_method,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->db_real->insert('bumdes_transaksi', $header_data);
            $transaksi_id = $this->db_real->insert_id();

            foreach ($items_to_save as $detail) {
                $detail_data = [
                    'transaksi_id' => $transaksi_id,
                    'produk_id' => $detail['produk_id'],
                    'qty' => $detail['qty'],
                    'harga_satuan' => $detail['harga_satuan'],
                    'harga_beli_satuan' => $detail['harga_beli_satuan'],
                    'subtotal' => $detail['subtotal']
                ];
                $this->db_real->insert('bumdes_transaksi_detail', $detail_data);

                // Deduct stock (except for ticket)
                $prod = $this->db_real->where('id', $detail['produk_id'])->get('bumdes_produk')->row_array();
                if ($prod && $prod['satuan'] !== 'tiket') {
                    $new_stock = (int)$prod['stok'] - $detail['qty'];
                    $this->db_real->where('id', $detail['produk_id'])->update('bumdes_produk', ['stok' => $new_stock]);

                    $log_data = [
                        'produk_id' => $detail['produk_id'],
                        'tipe' => 'sale',
                        'jumlah' => $detail['qty'],
                        'keterangan' => 'Penjualan POS Kasir (' . $invoice_number . ')',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $this->db_real->insert('bumdes_stok_log', $log_data);
                }
            }

            // Post to General Journal (Double-entry Bookkeeping)
            $accounts = [];
            $res_acct = $this->db_real->get('bumdes_akun')->result_array();
            foreach ($res_acct as $acct) {
                $accounts[$acct['kode_akun']] = $acct['id'];
            }

            $asset_account_code = ($payment_method === 'tunai') ? '1.1.1.01' : '1.1.2.01';
            $asset_account_id = $accounts[$asset_account_code] ?? null;
            $sales_account_id = $accounts['4.1.1.01'] ?? null;
            $persediaan_account_id = $accounts['1.1.3.01'] ?? null;
            $hpp_account_id = $accounts['5.1.1.01'] ?? null;

            if ($asset_account_id && $sales_account_id) {
                $net_sale = $total_price - $discount;
                if ($net_sale > 0) {
                    $jurnal_header = [
                        'nomor_jurnal' => 'JV-' . $invoice_number,
                        'tanggal' => date('Y-m-d'),
                        'keterangan' => 'Penjualan POS Kasir (' . $invoice_number . ')',
                        'referensi' => $invoice_number,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    $this->db_real->insert('bumdes_jurnal', $jurnal_header);
                    $jurnal_id = $this->db_real->insert_id();

                    $this->db_real->insert('bumdes_jurnal_detail', [
                        'jurnal_id' => $jurnal_id,
                        'akun_id' => $asset_account_id,
                        'debit' => $net_sale,
                        'kredit' => 0.00
                    ]);

                    $this->db_real->insert('bumdes_jurnal_detail', [
                        'jurnal_id' => $jurnal_id,
                        'akun_id' => $sales_account_id,
                        'debit' => 0.00,
                        'kredit' => $net_sale
                    ]);

                    $total_hpp = 0;
                    foreach ($items_to_save as $detail) {
                        $total_hpp += (float)$detail['harga_beli_satuan'] * (int)$detail['qty'];
                    }

                    if ($total_hpp > 0 && $hpp_account_id && $persediaan_account_id) {
                        $this->db_real->insert('bumdes_jurnal_detail', [
                            'jurnal_id' => $jurnal_id,
                            'akun_id' => $hpp_account_id,
                            'debit' => $total_hpp,
                            'kredit' => 0.00
                        ]);

                        $this->db_real->insert('bumdes_jurnal_detail', [
                            'jurnal_id' => $jurnal_id,
                            'akun_id' => $persediaan_account_id,
                            'debit' => 0.00,
                            'kredit' => $total_hpp
                        ]);
                    }
                }
            }

            if ($this->db_real->trans_status() === FALSE) {
                $this->db_real->trans_rollback();
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Gagal memproses transaksi.']);
                exit;
            }

            $this->db_real->trans_commit();

            $customer_name = 'Umum';
            if ($customer_type === 'penduduk' && !empty($customer_id)) {
                $pend = $this->db_real->where('id', $customer_id)->get('tweb_penduduk')->row_array();
                if ($pend) {
                    $customer_name = $pend['nama'] . ' (NIK: ' . $pend['nik'] . ')';
                }
            } else {
                $customer_name = $customer_name_umum;
            }

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Transaksi berhasil!',
                'invoice' => [
                    'invoice_number' => $invoice_number,
                    'customer_name' => $customer_name,
                    'total_harga' => $total_price,
                    'diskon' => $discount,
                    'net_total' => $total_price - $discount,
                    'metode_pembayaran' => strtoupper($payment_method),
                    'tanggal' => date('d-m-Y H:i:s'),
                    'items' => $items_to_save
                ]
            ]);
            exit;

        } catch (\Throwable $e) {
            $this->db_real->trans_rollback();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
            exit;
        }
    }
}
