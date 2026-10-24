<?php
/**
 * XOSID SAAS LIMITER
 * Intercepts requests to the Village Admin Dashboard and applies package limits.
 */

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// Only intercept if we are on a tenant subdomain, not main or superadmin
$http_host = $_SERVER['HTTP_HOST'] ?? '';
$is_tenant = true;
if (strpos($http_host, 'localhost:8080') === 0 || strpos($http_host, 'superadmin') === 0 || strpos($http_host, 'xosid.id') === 0) {
    $is_tenant = false;
}

if ($is_tenant) {
    // Check if user is logged into Village Admin Dashboard
    // OpenSID uses $_SESSION['sesi'] or $_SESSION['user'] etc. Let's check generally for login
    $is_logged_in_admin = isset($_SESSION['user']) || isset($_SESSION['sesi']) || isset($_SESSION['id_user']);
    
    if ($is_logged_in_admin) {
        // Fetch Tenant Info from master DB
        $db_config_file = __DIR__ . '/desa/config/database.php';
        if (file_exists($db_config_file)) {
            require $db_config_file; // Loads $db array
            $host = $db['default']['hostname'] ?? 'localhost';
            $user = $db['default']['username'] ?? 'root';
            $pass = $db['default']['password'] ?? '';
            
            // Database decoding logic
            if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $pass) && json_decode(base64_decode($pass))) {
                $pass = 'password'; // Mock fallback for Docker env
            }
            
            $master_conn = @new mysqli($host, $user, $pass, 'xosid');
            if (!$master_conn->connect_error) {
                // Find tenant by subdomain or custom_domain
                $host_parts = explode(':', $http_host);
                $domain_only = $host_parts[0];
                $subdomain_only = str_replace('.localhost', '', $domain_only);
                
                $stmt = $master_conn->prepare("SELECT id, paket_langganan FROM xosid_registrasi_tenant WHERE (custom_domain = ? OR nama_desa LIKE ?)");
                $like_sub = $subdomain_only . '%';
                $stmt->bind_param("ss", $domain_only, $like_sub);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows > 0) {
                    $tenant = $res->fetch_assoc();
                    $paket = $tenant['paket_langganan'];
                    
                    if ($paket === 'Tumbuh') {
                        $req_uri = $_SERVER['REQUEST_URI'];
                        $path = parse_url($req_uri, PHP_URL_PATH);
                        
                        // Allowed Modules for Basic:
                        // Dashboard (hom_desa), Identitas Desa, Pengurus (man_user), Penduduk (penduduk, keluarga), Artikel (artikel, web), Surat
                        $allowed_paths = [
                            '/hom_desa', '/identitas_desa', '/man_user', '/pengurus', '/kader', 
                            '/penduduk', '/keluarga', '/artikel', '/web', '/surat',
                            '/siteman', '/xoSID-login', '/index.php/xoSID-login'
                        ];
                        
                        $is_allowed = false;
                        if ($path === '/' || $path === '/index.php' || $path === '/index.php/' || empty(trim($path, '/'))) {
                            $is_allowed = true;
                        } else {
                            foreach ($allowed_paths as $allowed) {
                                if (stripos($path, $allowed) !== false) {
                                    $is_allowed = true;
                                    break;
                                }
                            }
                        }
                        
                        // Limit Surat PDF Check
                        if (stripos($path, '/surat/cetak') !== false || stripos($path, '/surat/doc') !== false || stripos($path, '/cetak_surat') !== false) {
                            $db_name = $db['default']['database'];
                            $tenant_conn = @new mysqli($host, $user, $pass, $db_name);
                            if (!$tenant_conn->connect_error) {
                                $count_res = $tenant_conn->query("SELECT COUNT(*) as total FROM log_surat WHERE MONTH(tanggal) = MONTH(CURRENT_DATE()) AND YEAR(tanggal) = YEAR(CURRENT_DATE())");
                                if ($count_res) {
                                    $row = $count_res->fetch_assoc();
                                    if ($row['total'] >= 3) {
                                        // Blocked due to limit!
                                        render_saas_block("Anda sudah melewati batas pencetakan/pembuatan surat (pdf) bulan ini (Maksimal 3).", $domain_only);
                                        exit;
                                    }
                                }
                            }
                        }

                        if (!$is_allowed && stripos($path, '/assets') === false && stripos($path, '/desa/upload') === false) {
                            // Blocked module!
                            render_saas_block("Modul ini terkunci pada paket Basic (Tumbuh).", $domain_only);
                            exit;
                        }
                    }
                }
                $master_conn->close();
            }
        }
    }
}

function render_saas_block($message, $domain) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $member_url = $protocol . '://localhost:8080/member/login';
    if (strpos($domain, 'localhost') === false) {
        $member_url = $protocol . '://' . $domain . '/member/login';
    }
    
    echo '<!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Layanan Dikunci - XOSID SaaS</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
        <style>body { font-family: "Plus Jakarta Sans", sans-serif; background-color: #020617; color: white; }</style>
    </head>
    <body class="min-h-screen flex items-center justify-center p-6 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-[#020617] to-[#020617]">
        <div class="max-w-md w-full bg-slate-900/50 backdrop-blur-xl border border-white/10 p-8 rounded-3xl text-center shadow-2xl">
            <div class="w-20 h-20 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-red-500/20">
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v-2m0 2h0m0-2h0m0 2v-2m0 0a2 2 0 100 4 2 2 0 000-4zm-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h1 class="text-2xl font-extrabold mb-3">Tingkatkan Layanan Anda</h1>
            <p class="text-slate-400 text-sm mb-8 leading-relaxed">' . htmlspecialchars($message) . '</p>
            <a href="' . $member_url . '" class="block w-full py-3 bg-gradient-to-r from-purple-500 to-cyan-500 hover:from-purple-400 hover:to-cyan-400 rounded-xl font-bold text-white shadow-lg shadow-purple-500/30 transition-all hover:scale-105">Upgrade Sekarang</a>
            <button onclick="window.history.back()" class="mt-4 block w-full py-3 bg-transparent text-slate-400 hover:text-white rounded-xl font-semibold text-sm transition-colors">Kembali</button>
        </div>
    </body>
    </html>';
}
