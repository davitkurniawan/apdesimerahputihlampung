<?php
// XOSID Portal2 Interceptor
// Renders the portal2.blade.php view directly

try {
    file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_run.log', "[" . date('Y-m-d H:i:s') . "] STARTING INTERCEPTOR for URI: " . $_SERVER['REQUEST_URI'] . "\n", FILE_APPEND);

    if (!function_exists('resource_path')) {
        function resource_path($path = '') { return FCPATH . 'resources/' . ltrim($path, '/'); }
    }
    if (!function_exists('storage_path')) {
        function storage_path($path = '') { return FCPATH . 'storage/' . ltrim($path, '/'); }
    }
    if (!function_exists('config_path')) {
        function config_path($path = '') { return FCPATH . 'config/' . ltrim($path, '/'); }
    }
    if (!function_exists('app_path')) {
        function app_path($path = '') { return FCPATH . 'app/' . ltrim($path, '/'); }
    }
    if (!function_exists('public_path')) {
        function public_path($path = '') { return FCPATH . 'public/' . ltrim($path, '/'); }
    }
    if (!function_exists('base_path')) {
        function base_path($path = '') { return FCPATH . ltrim($path, '/'); }
    }
    if (!class_exists('XOSIDDummyCI')) {
        class XOSIDDummyCI {
            public $session;
            public $db;
            public $list_setting;
            public $config;
            
            public function __construct() {
                $this->session = new class {
                    public function flashdata($key) { return null; }
                    public function set_flashdata($key, $val) {}
                    public function __call($name, $args) { return null; }
                    public function __get($name) { return null; }
                };
                $this->config = new class {
                    public function item($key) { return null; }
                    public function __call($name, $args) { return null; }
                    public function __get($name) { return null; }
                };
                $this->db = new class {
                    public function get($table = null) { return $this; }
                    public function where($key, $val = null) { return $this; }
                    public function order_by($col, $dir = '') { return $this; }
                    public function limit($limit, $offset = 0) { return $this; }
                    public function select($select) { return $this; }
                    public function join($table, $cond, $type = '') { return $this; }
                    public function result() { return []; }
                    public function result_array() { return []; }
                    public function row() { return new \stdClass(); }
                    public function row_array() { return []; }
                    public function __call($name, $args) { return $this; }
                    public function __get($name) { return null; }
                };
                try {
                    $this->list_setting = (object)\Illuminate\Support\Facades\DB::table('setting_aplikasi')->pluck('value', 'key')->toArray();
                } catch (\Throwable $e) {
                    $this->list_setting = (object)['sebutan_desa' => 'Desa'];
                }
            }
            
            public function __get($name) {
                return new class {
                    public function __call($n, $a) { return null; }
                    public function __get($n) { return null; }
                };
            }
            
            public function __call($name, $args) {
                return null;
            }
        }
    }

    if (!function_exists('ci')) {
        function ci() {
            global $dummy_ci;
            if (!isset($dummy_ci)) {
                $dummy_ci = new XOSIDDummyCI();
            }
            return $dummy_ci;
        }
    }

    if (!function_exists('get_instance')) {
        function get_instance() {
            return ci();
        }
    }
    if (!function_exists('route')) {
        function route($name) { return site_url($name); }
    }
    if (!function_exists('get_config')) {
        function get_config() { return ['base_url' => APP_URL . '/', 'index_page' => 'index.php']; }
    }
    if (!function_exists('session')) {
        function session($key = null, $default = null) {
            if ($key === null) {
                return new class {
                    public function get($k, $d = null) { return $_SESSION[$k] ?? $d; }
                    public function has($k) { return isset($_SESSION[$k]); }
                    public function flash($k, $v) { $_SESSION[$k] = $v; }
                };
            }
            if (is_array($key)) {
                foreach ($key as $k => $v) {
                    $_SESSION[$k] = $v;
                }
                return null;
            }
            return $_SESSION[$key] ?? $default;
        }
    }
    if (!function_exists('setting')) {
        function setting($key, $default = null) {
            $settings = [
                'sebutan_desa' => 'Desa',
                'sebutan_dusun' => 'Dusun',
                'sebutan_rt' => 'RT',
                'sebutan_rw' => 'RW',
            ];
            return $settings[$key] ?? $default;
        }
    }
    // Include all CI helpers required by the blade view
    $ci_helpers = [
        'core_helper.php',
        'database_helper.php',
        'donjolib_helper.php',
        'general_helper.php',
        'illuminate_helper.php',
        'nomor_rtm_helper.php',
        'opensid_helper.php',
        'password_helper.php',
        'pict_helper.php',
        'surat_helper.php',
        'tglindo_helper.php',
        'theme_helper.php',
        'web_helper.php'
    ];
    // Include CI system helpers
    $system_helpers = ['url_helper.php', 'form_helper.php', 'html_helper.php'];
    foreach ($system_helpers as $sys_helper) {
        try {
            @include_once BASEPATH . 'helpers/' . $sys_helper;
        } catch (\Throwable $e) {}
    }

    foreach ($ci_helpers as $helper) {
        try {
            @include_once FCPATH . 'donjo-app/helpers/' . $helper;
        } catch (\Throwable $e) {}
    }

    // Try to read database config and inject for Laravel if not already injected
    if (file_exists(FCPATH . 'desa/config/database.php')) {
        require FCPATH . 'desa/config/database.php';
        if (isset($db['default']) && isset($app)) {
            $ci_db = $db['default'];
            $db_pass = $ci_db['password'] ?? 'password';
            if (is_string($db_pass) && strpos($db_pass, 'eyJpdiI6') === 0) {
                try {
                    if (isset($app['encrypter'])) {
                        $db_pass = $app['encrypter']->decrypt($db_pass);
                    }
                } catch (\Throwable $e) {}
            }
            $laravel_db = [
                'driver'    => 'mysql',
                'host'      => $ci_db['hostname'] ?? 'db',
                'port'      => $ci_db['port'] ?? 3306,
                'database'  => $ci_db['database'] ?? 'xosid',
                'username'  => $ci_db['username'] ?? 'root',
                'password'  => $db_pass,
                'charset'   => 'utf8mb4',
                'collation' => $ci_db['dbcollat'] ?? 'utf8mb4_general_ci',
                'prefix'    => '',
                'strict'    => $ci_db['stricton'] ?? true,
                'engine'    => null,
            ];
            
            $config = $app['config'];
            $connections = $config->get('database.connections') ?: [];
            $connections['default'] = $laravel_db;
            $connections['mysql'] = $laravel_db;
            $config->set('database.connections', $connections);
            $config->set('database.default', 'default');
        }
    }

    $host = $_SERVER['HTTP_HOST'] ?? '';
    $host_no_port = explode(':', $host)[0];
    $is_saas = ($host_no_port === 'localhost' || $host_no_port === 'xosid.id' || $host_no_port === 'www.xosid.id');

    // Routes mapping
    $routes = [
        '/' => 'xosid.kabardesa',
        '' => 'xosid.kabardesa',
        '/index.php' => 'xosid.kabardesa',
        '/index.php/' => 'xosid.kabardesa',
        '/portal2' => 'xosid.portal2',
        '/first' => 'xosid.kabardesa',
        '/opendata' => 'xosid.opendata',
        '/potensi' => 'xosid.potensi',
        '/lapak' => 'xosid.lapak',
        '/lapak-umkm' => 'xosid.lapak_umkm',
        '/layanan-mandiri/masuk' => 'layanan_mandiri.auth.login',
        '/kabar-desa' => 'xosid.kabardesa',
        '/peta' => $is_saas ? 'xosid.peta' : 'xosid.peta_desa',
        '/index.php/peta' => $is_saas ? 'xosid.peta' : 'xosid.peta_desa',
        '/profil' => 'xosid.profil',
        '/index.php/profil' => 'xosid.profil',
        '/layanan' => 'xosid.surat_mandiri',
        '/index.php/layanan' => 'xosid.surat_mandiri',
        '/layanan-administrasi' => 'xosid.surat_mandiri',
        '/index.php/layanan-administrasi' => 'xosid.surat_mandiri',
    ];

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $matched_view = null;
    $article_slug = null;
    foreach ($routes as $route => $view) {
        if ($uri === $route || $uri === '/index.php' . $route) {
            $matched_view = $view;
            break;
        }
    }

    if (!$matched_view) {
        $is_detail = preg_match('#^/(index\.php/)*artikel/(\d{4})/(\d{2})/(\d{2})/([^/]+)$#', $uri, $matches);
        if ($is_detail) {
            $matched_view = 'xosid.detail';
            $article_slug = $matches[5];
        }
    }

    file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_run.log', "[" . date('Y-m-d H:i:s') . "] Matched View: " . ($matched_view ?? 'NONE') . " | URI: $uri\n", FILE_APPEND);

    if ($matched_view) {
        $view_file = FCPATH . 'resources/views/' . str_replace('.', '/', $matched_view) . '.blade.php';
        file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_run.log', "[" . date('Y-m-d H:i:s') . "] View file resolved: $view_file\n", FILE_APPEND);
        $desa = [];
        try {
            $desa = (array)\Illuminate\Support\Facades\DB::table('config')->first();
        } catch (\Throwable $e) {
            file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_run.log', "[" . date('Y-m-d H:i:s') . "] DB Fetch Error: " . $e->getMessage() . "\n", FILE_APPEND);
            $desa = ['nama_desa' => 'Desa', 'nama_kecamatan' => '', 'nama_kabupaten' => '', 'alamat_kantor' => '', 'kode_pos' => ''];
        }

        $menu = collect([]);
        try {
            $parents = \Illuminate\Support\Facades\DB::table('menu')
                ->where('parrent', '=', 0)
                ->where('enabled', '=', 1)
                ->orderBy('urut', 'asc')
                ->get();
            
            $menu_items = [];
            foreach ($parents as $parent) {
                $item = new \stdClass();
                $item->id = $parent->id;
                $item->nama = $parent->nama;
                
                $link = $parent->link;
                if (strpos($link, 'http://') === 0 || strpos($link, 'https://') === 0) {
                    $item->linkUrl = $link;
                } else {
                    $item->linkUrl = site_url($link);
                }
                
                $children_db = \Illuminate\Support\Facades\DB::table('menu')
                    ->where('parrent', '=', $parent->id)
                    ->where('enabled', '=', 1)
                    ->orderBy('urut', 'asc')
                    ->get();
                
                $children = [];
                foreach ($children_db as $child_db) {
                    $child = new \stdClass();
                    $child->id = $child_db->id;
                    $child->nama = $child_db->nama;
                    
                    $clink = $child_db->link;
                    if (strpos($clink, 'http://') === 0 || strpos($clink, 'https://') === 0) {
                        $child->linkUrl = $clink;
                    } else {
                        $child->linkUrl = site_url($clink);
                    }
                    $children[] = $child;
                }
                
                $item->children = collect($children);
                $menu_items[] = $item;
            }
            $menu = collect($menu_items);
        } catch (\Throwable $e) {
            file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_run.log', "[" . date('Y-m-d H:i:s') . "] Menu DB Fetch Error: " . $e->getMessage() . "\n", FILE_APPEND);
        }

        file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_run.log', "[" . date('Y-m-d H:i:s') . "] Is \$app set? " . (isset($app) ? 'YES' : 'NO') . "\n", FILE_APPEND);

        if (isset($app)) {
            try {
                $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
                $kernel->bootstrap();
            } catch (\Throwable $e) {
                file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_run.log', "[" . date('Y-m-d H:i:s') . "] Laravel Bootstrap Error: " . $e->getMessage() . "\n", FILE_APPEND);
            }

            // Share errors globally for the views
            $app->make('view')->share('errors', new \Illuminate\Support\ViewErrorBag);

            $data = ['desa' => $desa, 'menu' => $menu];

            if ($matched_view === 'xosid.kabardesa') {
                $categories = collect([]);
                $articles = collect([]);
                $current_category = $_GET['kategori'] ?? '';
                $current_page = 1;
                $total_pages = 1;
                try {
                    $categories = \Illuminate\Support\Facades\DB::table('kategori')
                        ->where('enabled', '=', 1)
                        ->get();
                    
                    $query = \Illuminate\Support\Facades\DB::table('artikel')
                        ->leftJoin('kategori', 'artikel.id_kategori', '=', 'kategori.id')
                        ->select('artikel.*', 'kategori.kategori as kategori_nama', 'kategori.slug as kategori_slug')
                        ->where('artikel.enabled', '=', 1);
                    
                    if (!empty($current_category)) {
                        $query->where('kategori.slug', '=', $current_category);
                    }
                    
                    $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
                    $per_page = 9;
                    $total_articles = $query->count();
                    $total_pages = ceil($total_articles / $per_page);
                    
                    $articles = $query->orderBy('artikel.tgl_upload', 'desc')
                        ->offset(($current_page - 1) * $per_page)
                        ->limit($per_page)
                        ->get();
                } catch (\Throwable $e) {
                    file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_run.log', "[" . date('Y-m-d H:i:s') . "] KabarDesa DB Fetch Error: " . $e->getMessage() . "\n", FILE_APPEND);
                }
                
                $data['categories'] = $categories;
                $data['articles'] = $articles;
                $data['current_category'] = $current_category;
                $data['current_page'] = $current_page;
                $data['total_pages'] = $total_pages;
            }

            if ($matched_view === 'xosid.detail') {
                $post = null;
                $related = collect([]);
                try {
                    $post_obj = \Illuminate\Support\Facades\DB::table('artikel')
                        ->leftJoin('kategori', 'artikel.id_kategori', '=', 'kategori.id')
                        ->select('artikel.*', 'kategori.kategori as kategori', 'kategori.slug as kategori_slug')
                        ->where('artikel.slug', '=', $article_slug)
                        ->where('artikel.enabled', '=', 1)
                        ->first();
                    
                    if ($post_obj) {
                        // Increment hit counter
                        \Illuminate\Support\Facades\DB::table('artikel')
                            ->where('id', $post_obj->id)
                            ->increment('hit');
                        $post_obj->hit += 1;

                        $post = (array)$post_obj;
                        $post['tgl_upload_local'] = date('d M Y H:i', strtotime($post['tgl_upload']));
                        
                        $user = \Illuminate\Support\Facades\DB::table('user')
                            ->where('id', '=', $post['id_user'])
                            ->first();
                        $post['owner'] = $user ? $user->nama : 'Admin Desa';

                        $related = \Illuminate\Support\Facades\DB::table('artikel')
                            ->where('id_kategori', '=', $post['id_kategori'])
                            ->where('id', '!=', $post['id'])
                            ->where('enabled', '=', 1)
                            ->orderBy('tgl_upload', 'desc')
                            ->limit(3)
                            ->get();
                        
                        foreach ($related as $r) {
                            $r->tgl_upload_local = date('d M Y', strtotime($r->tgl_upload));
                        }
                    }
                } catch (\Throwable $e) {
                    file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_run.log', "[" . date('Y-m-d H:i:s') . "] Detail DB Fetch Error: " . $e->getMessage() . "\n", FILE_APPEND);
                }
                
                if (!$post) {
                    http_response_code(404);
                    echo "<h1>Artikel tidak ditemukan</h1>";
                    exit;
                }

                $data['post'] = $post;
                $data['related'] = $related;
            }

            if ($matched_view === 'layanan_mandiri.auth.login') {
                $data['cek_anjungan'] = ['keyboard' => 0, 'mac_address' => null];
                $data['form_action'] = site_url('layanan-mandiri/masuk');
                $data['errors'] = new class {
                    public function first($key) { return ''; }
                    public function any() { return false; }
                    public function all() { return []; }
                };
            }

            if ($matched_view === 'xosid.opendata') {
                $total_jiwa = 0;
                $total_kk = 0;
                $total_dusun = 0;
                $total_rt = 0;
                $total_produk = 0;
                $gender_data = ['Laki-laki' => 0, 'Perempuan' => 0];
                $education_data = [];
                $occupation_data = [];
                $age_data = [];
                $agama_data = [];
                $kawin_data = [];
                $budget_data = [];
                $belanja_data = [];
                $produk_kategori_data = [];

                try {
                    $total_jiwa = \Illuminate\Support\Facades\DB::table('tweb_penduduk')->where('status_dasar', 1)->count();
                } catch (\Throwable $e) {}
                try {
                    $total_kk = \Illuminate\Support\Facades\DB::table('tweb_keluarga')->count();
                } catch (\Throwable $e) {}
                try {
                    $total_dusun = \Illuminate\Support\Facades\DB::table('tweb_wil_clusterdesa')->where('rt', '0')->where('rw', '0')->count();
                } catch (\Throwable $e) {}
                try {
                    $total_rt = \Illuminate\Support\Facades\DB::table('tweb_wil_clusterdesa')->where('rt', '>', '0')->count();
                } catch (\Throwable $e) {}
                try {
                    $total_produk = \Illuminate\Support\Facades\DB::table('produk')->count();
                } catch (\Throwable $e) {}

                try {
                    $gender_raw = \Illuminate\Support\Facades\DB::table('tweb_penduduk')
                        ->where('status_dasar', 1)
                        ->select('sex', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                        ->groupBy('sex')
                        ->get();
                    foreach ($gender_raw as $g) {
                        if ($g->sex == 1) $gender_data['Laki-laki'] = $g->total;
                        elseif ($g->sex == 2) $gender_data['Perempuan'] = $g->total;
                    }
                } catch (\Throwable $e) {}

                try {
                    $edu_raw = \Illuminate\Support\Facades\DB::table('tweb_penduduk')
                        ->join('tweb_penduduk_pendidikan_kk', 'tweb_penduduk.pendidikan_kk_id', '=', 'tweb_penduduk_pendidikan_kk.id')
                        ->where('tweb_penduduk.status_dasar', 1)
                        ->select('tweb_penduduk_pendidikan_kk.nama', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                        ->groupBy('tweb_penduduk_pendidikan_kk.nama')
                        ->get();
                    foreach ($edu_raw as $item) {
                        $education_data[] = ['nama' => $item->nama, 'total' => $item->total];
                    }
                } catch (\Throwable $e) {}

                try {
                    $occ_raw = \Illuminate\Support\Facades\DB::table('tweb_penduduk')
                        ->join('tweb_penduduk_pekerjaan', 'tweb_penduduk.pekerjaan_id', '=', 'tweb_penduduk_pekerjaan.id')
                        ->where('tweb_penduduk.status_dasar', 1)
                        ->select('tweb_penduduk_pekerjaan.nama', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                        ->groupBy('tweb_penduduk_pekerjaan.nama')
                        ->orderBy('total', 'desc')
                        ->limit(10)
                        ->get();
                    foreach ($occ_raw as $item) {
                        $occupation_data[] = ['nama' => $item->nama, 'total' => $item->total];
                    }
                } catch (\Throwable $e) {}

                try {
                    $ages = \Illuminate\Support\Facades\DB::table('tweb_penduduk')
                        ->where('status_dasar', 1)
                        ->select(\Illuminate\Support\Facades\DB::raw('TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) as age'))
                        ->get();
                    $age_groups = [
                        '0-4' => 0, '5-9' => 0, '10-14' => 0, '15-19' => 0, '20-24' => 0,
                        '25-29' => 0, '30-34' => 0, '35-39' => 0, '40-44' => 0, '45-49' => 0,
                        '50-54' => 0, '55-59' => 0, '60-64' => 0, '65-69' => 0, '70-74' => 0, '75+' => 0
                    ];
                    foreach ($ages as $a) {
                        $age = $a->age;
                        if ($age <= 4) $age_groups['0-4']++;
                        elseif ($age <= 9) $age_groups['5-9']++;
                        elseif ($age <= 14) $age_groups['10-14']++;
                        elseif ($age <= 19) $age_groups['15-19']++;
                        elseif ($age <= 24) $age_groups['20-24']++;
                        elseif ($age <= 29) $age_groups['25-29']++;
                        elseif ($age <= 34) $age_groups['30-34']++;
                        elseif ($age <= 39) $age_groups['35-39']++;
                        elseif ($age <= 44) $age_groups['40-44']++;
                        elseif ($age <= 49) $age_groups['45-49']++;
                        elseif ($age <= 54) $age_groups['50-54']++;
                        elseif ($age <= 59) $age_groups['55-59']++;
                        elseif ($age <= 64) $age_groups['60-64']++;
                        elseif ($age <= 69) $age_groups['65-69']++;
                        elseif ($age <= 74) $age_groups['70-74']++;
                        else $age_groups['75+']++;
                    }
                    foreach ($age_groups as $group => $tot) {
                        $age_data[] = ['kelompok' => $group, 'total' => $tot];
                    }
                } catch (\Throwable $e) {}

                try {
                    $rel_raw = \Illuminate\Support\Facades\DB::table('tweb_penduduk')
                        ->join('tweb_penduduk_agama', 'tweb_penduduk.agama_id', '=', 'tweb_penduduk_agama.id')
                        ->where('tweb_penduduk.status_dasar', 1)
                        ->select('tweb_penduduk_agama.nama', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                        ->groupBy('tweb_penduduk_agama.nama')
                        ->get();
                    foreach ($rel_raw as $item) {
                        $agama_data[] = ['nama' => $item->nama, 'total' => $item->total];
                    }
                } catch (\Throwable $e) {}

                try {
                    $kaw_raw = \Illuminate\Support\Facades\DB::table('tweb_penduduk')
                        ->join('tweb_penduduk_kawin', 'tweb_penduduk.status_kawin', '=', 'tweb_penduduk_kawin.id')
                        ->where('tweb_penduduk.status_dasar', 1)
                        ->select('tweb_penduduk_kawin.nama', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                        ->groupBy('tweb_penduduk_kawin.nama')
                        ->get();
                    foreach ($kaw_raw as $item) {
                        $kawin_data[] = ['nama' => $item->nama, 'total' => $item->total];
                    }
                } catch (\Throwable $e) {}

                try {
                    $latest_year = \Illuminate\Support\Facades\DB::table('keuangan')->max('tahun') ?: date('Y');
                    $budget_raw = \Illuminate\Support\Facades\DB::table('keuangan')
                        ->join('keuangan_template', 'keuangan.template_uuid', '=', 'keuangan_template.uuid')
                        ->select('keuangan.*', 'keuangan_template.uraian', 'keuangan_template.parent_uuid')
                        ->where('keuangan.tahun', '=', $latest_year)
                        ->get();
                    foreach ($budget_raw as $b) {
                        $item = [
                            'uuid' => $b->template_uuid,
                            'uraian' => $b->uraian,
                            'anggaran' => (float)$b->anggaran,
                            'realisasi' => (float)$b->realisasi,
                            'tahun' => $b->tahun,
                            'parent_uuid' => $b->parent_uuid
                        ];
                        if (empty($b->parent_uuid)) {
                            $budget_data[] = $item;
                        }
                        if ($b->parent_uuid === '5' || strpos($b->template_uuid, '5.') === 0) {
                            $belanja_data[] = $item;
                        }
                    }
                } catch (\Throwable $e) {}

                try {
                    $produk_kategori_raw = \Illuminate\Support\Facades\DB::table('produk_kategori')->get();
                    foreach ($produk_kategori_raw as $item) {
                        $produk_kategori_data[] = ['kategori' => $item->kategori];
                    }
                } catch (\Throwable $e) {}

                $data = array_merge($data, [
                    'total_jiwa' => $total_jiwa,
                    'total_kk' => $total_kk,
                    'total_dusun' => $total_dusun,
                    'total_rt' => $total_rt,
                    'total_produk' => $total_produk,
                    'gender_data' => $gender_data,
                    'education_data' => $education_data,
                    'occupation_data' => $occupation_data,
                    'age_data' => $age_data,
                    'agama_data' => $agama_data,
                    'kawin_data' => $kawin_data,
                    'budget_data' => $budget_data,
                    'belanja_data' => $belanja_data,
                    'produk_kategori_data' => $produk_kategori_data
                ]);
            }

            if ($matched_view === 'xosid.potensi') {
                $total_produk = 0;
                $produk_kategori_data = [];
                try {
                    $total_produk = \Illuminate\Support\Facades\DB::table('produk')->count();
                } catch (\Throwable $e) {}
                try {
                    $produk_kategori_raw = \Illuminate\Support\Facades\DB::table('produk_kategori')->get();
                    foreach ($produk_kategori_raw as $item) {
                        $produk_kategori_data[] = ['kategori' => $item->kategori];
                    }
                } catch (\Throwable $e) {}

                $lahan_data = [
                    'total_luas' => 1250000,
                    'sawah' => 450000,
                    'tegalan' => 150000,
                    'perkebunan' => 250000,
                    'hutan_lebat_lindung' => 100000,
                    'hutan_belukar' => 50000,
                    'perumahan' => 180000,
                    'fasilitas_umum' => 40000,
                    'perdagangan_jasa' => 30000
                ];

                $profile_data = [
                    ['key' => 'topografi', 'value' => 'Datar'],
                    ['key' => 'jenis_tanah', 'value' => 'Aluvial / Liat'],
                    ['key' => 'sumber_daya_alam', 'value' => 'Mata Air Gunung & Sungai'],
                    ['key' => 'jenis_jaringan', 'value' => '4G LTE / FTTH Broadband'],
                    ['key' => 'provider_internet', 'value' => 'Indihome / Telkomsel'],
                    ['key' => 'kecepatan_internet', 'value' => '50 Mbps']
                ];

                $data = array_merge($data, [
                    'total_produk' => $total_produk,
                    'produk_kategori_data' => $produk_kategori_data,
                    'lahan_data' => $lahan_data,
                    'profile_data' => $profile_data,
                    'is_lahan_simulation' => true
                ]);
            }

            if ($matched_view === 'xosid.peta_desa') {
                $locations = [];
                $areas = [];
                $lines = [];
                
                try {
                    $locations = \Illuminate\Support\Facades\DB::table('lokasi')
                        ->leftJoin('point', 'lokasi.ref_point', '=', 'point.id')
                        ->select('lokasi.*', 'point.simbol as simbol_icon', 'point.nama as kategori_nama')
                        ->where('lokasi.enabled', 1)
                        ->get()
                        ->toArray();
                } catch (\Throwable $e) {
                    file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_run.log', "[" . date('Y-m-d H:i:s') . "] Map Locations DB Fetch Error: " . $e->getMessage() . "\n", FILE_APPEND);
                }

                try {
                    $areas = \Illuminate\Support\Facades\DB::table('area')
                        ->leftJoin('polygon', 'area.ref_polygon', '=', 'polygon.id')
                        ->select('area.*', 'polygon.color as warna_polygon', 'polygon.nama as kategori_nama')
                        ->where('area.enabled', 1)
                        ->get()
                        ->toArray();
                } catch (\Throwable $e) {
                    file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_run.log', "[" . date('Y-m-d H:i:s') . "] Map Areas DB Fetch Error: " . $e->getMessage() . "\n", FILE_APPEND);
                }

                try {
                    $lines = \Illuminate\Support\Facades\DB::table('garis')
                        ->leftJoin('line', 'garis.ref_line', '=', 'line.id')
                        ->select('garis.*', 'line.color as warna_line', 'line.tebal as tebal_line', 'line.nama as kategori_nama')
                        ->where('garis.enabled', 1)
                        ->get()
                        ->toArray();
                } catch (\Throwable $e) {
                    file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_run.log', "[" . date('Y-m-d H:i:s') . "] Map Lines DB Fetch Error: " . $e->getMessage() . "\n", FILE_APPEND);
                }

                // Fetch Dusun boundaries
                $dusuns = [];
                try {
                    $dusuns = \Illuminate\Support\Facades\DB::table('tweb_wil_clusterdesa')
                        ->where('rt', '=', '0')
                        ->where('rw', '=', '0')
                        ->whereNotNull('path')
                        ->where('path', '!=', '')
                        ->get()
                        ->toArray();
                } catch (\Throwable $e) {}

                // Fetch RW boundaries
                $rws = [];
                try {
                    $rws = \Illuminate\Support\Facades\DB::table('tweb_wil_clusterdesa')
                        ->where('rt', '=', '0')
                        ->where('rw', '!=', '0')
                        ->whereNotNull('path')
                        ->where('path', '!=', '')
                        ->get()
                        ->toArray();
                } catch (\Throwable $e) {}

                // Fetch RT boundaries
                $rts = [];
                try {
                    $rts = \Illuminate\Support\Facades\DB::table('tweb_wil_clusterdesa')
                        ->where('rt', '!=', '0')
                        ->whereNotNull('path')
                        ->where('path', '!=', '')
                        ->get()
                        ->toArray();
                } catch (\Throwable $e) {}

                // Fetch Pembangunan projects
                $pembangunans = [];
                try {
                    $pembangunans = \Illuminate\Support\Facades\DB::table('pembangunan')
                        ->whereNotNull('lat')
                        ->where('lat', '!=', '')
                        ->get()
                        ->toArray();
                } catch (\Throwable $e) {}

                // Fetch Persil polygons
                $persils = [];
                try {
                    $persils = \Illuminate\Support\Facades\DB::table('persil')
                        ->whereNotNull('path')
                        ->where('path', '!=', '')
                        ->get()
                        ->toArray();
                } catch (\Throwable $e) {}

                $data = array_merge($data, [
                    'locations' => $locations,
                    'areas' => $areas,
                    'lines' => $lines,
                    'dusuns' => $dusuns,
                    'rws' => $rws,
                    'rts' => $rts,
                    'pembangunans' => $pembangunans,
                    'persils' => $persils
                ]);
            }

            $profil_desa = [];
            $intel_data = [];

            if ($matched_view === 'xosid.profil') {
                $pamong = [];
                $total_jiwa = 0;
                $total_kk = 0;
                $total_dusun = 0;
                $total_rt = 0;

                try {
                    $pamong = \Illuminate\Support\Facades\DB::table('tweb_desa_pamong')
                        ->leftJoin('ref_jabatan', 'tweb_desa_pamong.jabatan_id', '=', 'ref_jabatan.id')
                        ->select('tweb_desa_pamong.*', 'ref_jabatan.nama as nama_jabatan')
                        ->where('tweb_desa_pamong.pamong_status', '=', 1)
                        ->orderBy('tweb_desa_pamong.urut', 'asc')
                        ->get()
                        ->toArray();
                } catch (\Throwable $e) {}

                try {
                    $total_jiwa = \Illuminate\Support\Facades\DB::table('tweb_penduduk')->where('status_dasar', 1)->count();
                } catch (\Throwable $e) {}
                try {
                    $total_kk = \Illuminate\Support\Facades\DB::table('tweb_keluarga')->count();
                } catch (\Throwable $e) {}
                try {
                    $total_dusun = \Illuminate\Support\Facades\DB::table('tweb_wil_clusterdesa')->where('rt', '0')->where('rw', '0')->count();
                } catch (\Throwable $e) {}
                try {
                    $total_rt = \Illuminate\Support\Facades\DB::table('tweb_wil_clusterdesa')->where('rt', '>', '0')->count();
                } catch (\Throwable $e) {}

                try {
                    $profil_desa = \Illuminate\Support\Facades\DB::table('profil_desa')
                        ->where('config_id', '=', 1)
                        ->pluck('value', 'key')
                        ->toArray();
                } catch (\Throwable $e) {}

                if (function_exists('xosid_get_intelligence_data')) {
                    try {
                        $intel_data = xosid_get_intelligence_data();
                    } catch (\Throwable $e) {}
                }
            }

                // Fetch IDM & SDGs Data
                $idm_data_resolved = [
                    'available' => false,
                    'tahun' => date('Y'),
                    'skor' => 0.7854,
                    'status' => 'MAJU',
                    'iks' => 0.8234,
                    'ike' => 0.7120,
                    'ikl' => 0.8208,
                    'is_fallback' => true
                ];

                $sdgs_data_resolved = [
                    'available' => false,
                    'average' => 58.42,
                    'goals' => [
                        ['label' => 'Desa Tanpa Kemiskinan', 'skor' => 52.3],
                        ['label' => 'Desa Tanpa Kelaparan', 'skor' => 63.0],
                        ['label' => 'Desa Sehat dan Sejahtera', 'skor' => 60.5],
                        ['label' => 'Pendidikan Desa Berkualitas', 'skor' => 55.8],
                        ['label' => 'Keterlibatan Perempuan Desa', 'skor' => 68.0],
                        ['label' => 'Desa Layak Air Bersih dan Sanitasi', 'skor' => 51.2],
                        ['label' => 'Desa Berenergi Bersih dan Terbarukan', 'skor' => 48.7],
                        ['label' => 'Pertumbuhan Ekonomi Desa Merata', 'skor' => 56.3],
                        ['label' => 'Infrastruktur dan Inovasi Desa sesuai Kebutuhan', 'skor' => 59.0],
                        ['label' => 'Desa Tanpa Kesenjangan', 'skor' => 61.4],
                        ['label' => 'Kawasan Pemukiman Desa Aman dan Layak', 'skor' => 64.2],
                        ['label' => 'Konsumsi dan Produksi Desa Sadar Lingkungan', 'skor' => 58.0],
                        ['label' => 'Desa Tanggap Perubahan Iklim', 'skor' => 50.6],
                        ['label' => 'Desa Peduli Lingkungan Laut', 'skor' => 45.0],
                        ['label' => 'Desa Peduli Lingkungan Darat', 'skor' => 62.0],
                        ['label' => 'Desa Damai Berkeadilan', 'skor' => 75.4],
                        ['label' => 'Kemitraan untuk Pembangunan Desa', 'skor' => 66.5],
                        ['label' => 'Kelembagaan Desa Dinamis dan Budaya Desa Adaptif', 'skor' => 63.7],
                    ],
                    'is_fallback' => true
                ];

                try {
                    $kode_desa = \Illuminate\Support\Facades\DB::table('config')->value('kode_desa');
                    $kode_desa_bps = \Illuminate\Support\Facades\DB::table('config')->value('kode_desa_bps');

                    // Read IDM
                    $idm_file = null;
                    if ($kode_desa) {
                        $files = glob(FCPATH . "desa/cache/idm_*_{$kode_desa}.json");
                        if (!empty($files)) {
                            usort($files, function($a, $b) {
                                return filemtime($b) <=> filemtime($a);
                            });
                            $idm_file = $files[0];
                        }
                    }

                    if ($idm_file && file_exists($idm_file)) {
                        $raw = @file_get_contents($idm_file);
                        $data_idm_raw = @unserialize($raw);
                        if (!$data_idm_raw) $data_idm_raw = @json_decode($raw, true);
                        
                        $idmInner = $data_idm_raw['data'] ?? $data_idm_raw;
                        if ($idmInner && (isset($idmInner['skor']) || isset($idmInner['SUMMARIES']['SKOR_SAAT_INI']))) {
                            $skor = (float)($idmInner['skor'] ?? $idmInner['SUMMARIES']['SKOR_SAAT_INI'] ?? 0);
                            $status = $idmInner['status'] ?? $idmInner['SUMMARIES']['STATUS'] ?? 'N/A';
                            
                            if ($skor > 0) {
                                $iks = 0.0; $ike = 0.0; $ikl = 0.0;
                                $indikators = $idmInner['indikator'] ?? $idmInner['ROW'] ?? [];
                                foreach ($indikators as $r) {
                                    $indName = strtoupper(trim($r['INDIKATOR'] ?? ''));
                                    if (strpos($indName, 'IKS') === 0 || strpos($indName, 'SOSIAL') !== false) {
                                        $iks = (float)($r['SKOR'] ?? $iks);
                                    } elseif (strpos($indName, 'IKE') === 0 || strpos($indName, 'EKONOMI') !== false) {
                                        $ike = (float)($r['SKOR'] ?? $ike);
                                    } elseif (strpos($indName, 'IKL') === 0 || strpos($indName, 'LINGKUNGAN') !== false || strpos($indName, 'EKOLOGI') !== false) {
                                        $ikl = (float)($r['SKOR'] ?? $ikl);
                                    }
                                }
                                
                                $tahun = date('Y');
                                if (preg_match('/idm_(\d+)_/', basename($idm_file), $m)) {
                                    $tahun = $m[1];
                                }

                                $idm_data_resolved = [
                                    'available' => true,
                                    'tahun' => $tahun,
                                    'skor' => $skor,
                                    'status' => $status !== 'N/A' ? $status : 'MAJU',
                                    'iks' => $iks ?: $skor * 1.05,
                                    'ike' => $ike ?: $skor * 0.9,
                                    'ikl' => $ikl ?: $skor * 1.05,
                                    'is_fallback' => false
                                ];
                            }
                        }
                    }

                    // Read SDGs
                    $sdgs_file = FCPATH . "desa/cache/sdgs_{$kode_desa_bps}.json";
                    if ($kode_desa_bps && file_exists($sdgs_file)) {
                        $raw = @file_get_contents($sdgs_file);
                        $data_sdgs_raw = @unserialize($raw);
                        if (!$data_sdgs_raw) $data_sdgs_raw = @json_decode($raw, true);
                        
                        $sdgsInner = $data_sdgs_raw['data'] ?? $data_sdgs_raw;
                        if ($sdgsInner && isset($sdgsInner['average']) && (float)$sdgsInner['average'] > 0) {
                            $goals = [];
                            foreach ($sdgsInner['data'] ?? [] as $item) {
                                $goals[] = [
                                    'label' => $item['label'] ?? '',
                                    'skor' => (float)($item['skor'] ?? 0)
                                ];
                            }
                            
                            $sdgs_data_resolved = [
                                'available' => true,
                                'average' => (float)$sdgsInner['average'],
                                'goals' => $goals,
                                'is_fallback' => false
                            ];
                        }
                    }
                } catch (\Throwable $e) {
                    file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_run.log', "[" . date('Y-m-d H:i:s') . "] IDM & SDGs Fetch Error: " . $e->getMessage() . "\n", FILE_APPEND);
                }

                $data = array_merge($data, [
                    'pamong' => $pamong ?? [],
                    'total_jiwa' => $total_jiwa ?? 0,
                    'total_kk' => $total_kk ?? 0,
                    'total_dusun' => $total_dusun ?? 0,
                    'total_rt' => $total_rt ?? 0,
                    'idm_data' => $idm_data_resolved,
                    'sdgs_data' => $sdgs_data_resolved,
                    'profil_desa' => $profil_desa ?? [],
                    'intel_data' => $intel_data ?? []
                ]);

            file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_debug.log', "Matched view: $matched_view, View file: $view_file\n", FILE_APPEND);
            $view_content = $app->make('view')->file($view_file, $data)->render();
            file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_debug.log', "Rendered length: " . strlen($view_content) . "\n", FILE_APPEND);
            echo $view_content;
            exit;
        }
    }
} catch (\Throwable $e) {
    file_put_contents(FCPATH . 'storage/logs/xosid_interceptor_error.log', "[" . date('Y-m-d H:i:s') . "] Interceptor Error: " . $e->getMessage() . " (View: " . $e->getFile() . ":" . $e->getLine() . ")\n" . $e->getTraceAsString() . "\n", FILE_APPEND);
    echo "<h1>Terjadi kesalahan saat memuat XOSID</h1>";
    echo "<pre>" . $e->getMessage() . "\n" . $e->getTraceAsString() . "</pre>";
    exit;
}
