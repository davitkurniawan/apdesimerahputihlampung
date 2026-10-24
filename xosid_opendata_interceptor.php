<?php
// XOSID Portal2 Interceptor
// Renders the portal2.blade.php view directly

try {
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
    if (!function_exists('ci')) {
        function ci() {
            global $dummy_ci;
            if (!isset($dummy_ci)) {
                $dummy_ci = new stdClass();
                try {
                    $dummy_ci->list_setting = (object)\Illuminate\Support\Facades\DB::table('setting_aplikasi')->pluck('value', 'key')->toArray();
                } catch (\Throwable $e) {
                    $dummy_ci->list_setting = (object)['sebutan_desa' => 'Desa'];
                }
            }
            return $dummy_ci;
        }
    }
    if (!function_exists('route')) {
        function route($name) { return site_url($name); }
    }
    if (!function_exists('get_config')) {
        function get_config() { return ['base_url' => APP_URL . '/', 'index_page' => 'index.php']; }
    }
    if (!function_exists('current_url')) {
        function current_url() { return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; }
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
        'web_helper.php',
        'url_helper.php'
    ];
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

    $desa = (array)\Illuminate\Support\Facades\DB::table('config')->first();

    // Specific variables for lapak
    $app_url = APP_URL . '/';
    
    // Fallbacks for maps
    $mapbox_key = '';
    $jenis_peta = '';
    try {
        $setting_mapbox = \Illuminate\Support\Facades\DB::table('setting_aplikasi')->where('key', 'mapbox_key')->first();
        if ($setting_mapbox) $mapbox_key = $setting_mapbox->value;
        
        $setting_peta = \Illuminate\Support\Facades\DB::table('setting_aplikasi')->where('key', 'jenis_peta')->first();
        if ($setting_peta) $jenis_peta = $setting_peta->value;
    } catch (\Throwable $e) {}

    // Setup view variables
    $view_file = FCPATH . 'resources/views/xosid/opendata.blade.php';
    
    if (isset($app)) {
        echo $app->make('view')->file($view_file, [
            'desa' => $desa,
            'app_url' => $app_url,
            'mapbox_key' => $mapbox_key,
            'jenis_peta' => $jenis_peta,
            'menu' => [] // Fallback since menu loading is complex
        ])->render();
    } else {
        echo "Error: Laravel app not found.";
    }
} catch (\Throwable $e) {
    file_put_contents(FCPATH . 'storage/logs/xosid_opendata_error.log', "[" . date('Y-m-d H:i:s') . "] Opendata Interceptor Error: " . $e->getMessage() . " (View: " . $e->getFile() . ")\n", FILE_APPEND);
    echo "<h1>Terjadi kesalahan saat memuat Opendata</h1>";
    if (defined('ENVIRONMENT') && ENVIRONMENT !== 'production') {
        echo "<pre>" . $e->getMessage() . "\n" . $e->getTraceAsString() . "</pre>";
    }
}
