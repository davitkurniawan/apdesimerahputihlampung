<?php
/**
 * XOSID Package Gatekeeper & Access Control Guard
 * Enforces strict backend tier separation between Esensial & Enterprise.
 */

if (!function_exists('xosid_enforce_package_gatekeeper')) {
    function xosid_enforce_package_gatekeeper() {
        if (php_sapi_name() === 'cli') {
            return;
        }

        $paket = function_exists('xosid_get_tenant_package') ? xosid_get_tenant_package() : 'esensial';
        if ($paket !== 'esensial') {
            return; // Enterprise/supradesa has full access to all features
        }

        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $path = parse_url($uri, PHP_URL_PATH) ?? '';
        $query = parse_url($uri, PHP_URL_QUERY) ?? '';
        $clean_path = strtolower(rtrim(str_replace('/index.php', '', $path), '/'));

        // Enterprise-exclusive protected routes
        $enterprise_routes = [
            '/katadesa-ai',
            '/portal2',
            '/dashboard_neural',
            '/api/katadesa-ai',
            '/api/katadesa',
            '/katadesa'
        ];

        $is_locked = false;
        $locked_feature = 'Modul Enterprise';

        foreach ($enterprise_routes as $protected) {
            if ($clean_path === $protected || strpos($clean_path, $protected . '/') === 0) {
                $is_locked = true;
                if (strpos($protected, 'katadesa') !== false) {
                    $locked_feature = 'KATADESA AI (Kecerdasan Buatan)';
                } elseif (strpos($protected, 'analisis_cerdas') !== false) {
                    $locked_feature = 'Analisis Prediktif & Cerdas Desa';
                } elseif (strpos($protected, 'portal2') !== false || strpos($protected, 'dashboard_neural') !== false) {
                    $locked_feature = 'Neural Intelligence Center';
                }
                break;
            }
        }

        // Check query string locks (e.g. ?view=katadesa)
        if (!$is_locked && !empty($query)) {
            parse_str($query, $params);
            if (isset($params['view']) && in_array(strtolower($params['view']), ['katadesa', 'neural'])) {
                $is_locked = true;
                $locked_feature = 'KATADESA AI (Kecerdasan Buatan)';
            }
        }

        if ($is_locked) {
            $is_ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
                || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
                || (strpos($clean_path, '/api/') !== false);

            if ($is_ajax) {
                http_response_code(403);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'status' => 'error',
                    'code' => 'TIER_UPGRADE_REQUIRED',
                    'message' => "Akses Ditolak: Fitur '$locked_feature' memerlukan lisensi paket xoSID Enterprise.",
                    'current_plan' => 'xoSID Esensial',
                    'upgrade_url' => 'https://xosid.id/#pricing'
                ]);
                exit;
            }

            // Render Lock Page
            http_response_code(403);
            ?>
            <!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Akses Terkunci | xoSID Esensial</title>
                <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Outfit:wght@600;800&display=swap" rel="stylesheet">
                <script src="https://cdn.tailwindcss.com"></script>
                <script src="https://unpkg.com/lucide@latest"></script>
                <style>
                    body { font-family: 'Inter', sans-serif; background-color: #0f172a; color: #f8fafc; }
                    h1, h2, h3 { font-family: 'Outfit', sans-serif; }
                </style>
            </head>
            <body class="min-h-screen flex items-center justify-center p-6 bg-slate-950">
                <div class="max-w-xl w-full bg-slate-900 border border-slate-800 rounded-3xl p-8 md:p-10 shadow-2xl text-center relative overflow-hidden">
                    <div class="absolute -right-16 -top-16 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -left-16 -bottom-16 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                    <!-- Icon -->
                    <div class="w-20 h-20 bg-blue-500/10 border border-blue-500/30 rounded-2xl mx-auto flex items-center justify-center mb-6 text-[#4895ef] shadow-[0_0_30px_rgba(72,149,239,0.2)]">
                        <i data-lucide="lock" class="w-10 h-10"></i>
                    </div>

                    <!-- Badge -->
                    <span class="inline-block px-3 py-1 bg-blue-500/20 text-[#4895ef] border border-blue-500/30 rounded-full text-xs font-bold tracking-widest uppercase mb-4">
                        FITUR XOSID ENTERPRISE
                    </span>

                    <h1 class="text-2xl md:text-3xl font-extrabold text-white mb-3">
                        <?= htmlspecialchars($locked_feature) ?>
                    </h1>

                    <p class="text-slate-400 text-sm leading-relaxed mb-8">
                        Desa Anda saat ini menggunakan paket <strong class="text-white">xoSID Esensial</strong> yang difokuskan pada administrasi kependudukan dan pelayanan publik warga. Fitur kecerdasan buatan & analisis prediktif hanya tersedia pada paket <strong class="text-[#4895ef]">xoSID Enterprise</strong>.
                    </p>

                    <div class="bg-slate-950/60 border border-slate-800/80 rounded-2xl p-5 mb-8 text-left space-y-3">
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Keunggulan Paket Enterprise:</div>
                        <div class="flex items-center gap-3 text-xs text-slate-300">
                            <i data-lucide="check-circle" class="w-4 h-4 text-[#4895ef] shrink-0"></i>
                            <span>Asisten Pintar KATADESA AI untuk pembuatan regulasi & APBDes</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-300">
                            <i data-lucide="check-circle" class="w-4 h-4 text-[#4895ef] shrink-0"></i>
                            <span>Analisis Prediktif Kemiskinan & Stunting Berkelanjutan</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-300">
                            <i data-lucide="check-circle" class="w-4 h-4 text-[#4895ef] shrink-0"></i>
                            <span>Neural Command Center & Integrasi Supradesa Multi-Desa</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="<?= function_exists('site_url') ? site_url('beranda') : '/index.php/beranda' ?>" class="w-full sm:w-auto px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded-xl transition-all border border-slate-700">
                            Kembali ke Dasbor Desa
                        </a>
                        <a href="https://xosid.id/#pricing" target="_blank" class="w-full sm:w-auto px-6 py-3 bg-[#4895ef] hover:bg-blue-600 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-blue-500/25">
                            Pelajari Paket Enterprise
                        </a>
                    </div>
                </div>
                <script>
                    if (window.lucide) { lucide.createIcons(); }
                </script>
            </body>
            </html>
            <?php
            exit;
        }
    }
}

xosid_enforce_package_gatekeeper();
