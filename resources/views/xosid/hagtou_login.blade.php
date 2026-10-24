<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

function hagtou_get_db_config() {
    $db_config_file = dirname(__DIR__, 2) . '/desa/config/database.php';
    if (!file_exists($db_config_file)) {
        return [
            'hostname' => 'xosid_neural_db',
            'port' => 3306,
            'username' => 'root',
            'password' => 'password'
        ];
    }
    require $db_config_file;
    $cfg = $db['default'];
    
    $password = $cfg['password'] ?? 'password';
    if (is_string($password) && strpos($password, 'eyJpdiI6') === 0) {
        try {
            global $app;
            if (isset($app) && isset($app['encrypter'])) {
                $password = $app['encrypter']->decrypt($password);
            } else {
                $password = \Illuminate\Support\Facades\Crypt::decrypt($password);
            }
        } catch (\Throwable $e) {
            $password = 'password';
        }
    }
    
    $hostname = $cfg['hostname'] ?? 'db';
    if ($hostname === 'db') {
        $hostname = 'xosid_neural_db';
    }
    
    return [
        'hostname' => $hostname,
        'port' => (int)($cfg['port'] ?? 3306),
        'username' => $cfg['username'] ?? 'root',
        'password' => $password
    ];
}

$db_cfg = hagtou_get_db_config();
$master_conn = new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], 'xosid', $db_cfg['port']);
$tenants = [];
if (!$master_conn->connect_error) {
    $res = $master_conn->query("SELECT id, nama_desa, custom_domain FROM xosid_registrasi_tenant WHERE status = 'approved' ORDER BY nama_desa ASC");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $clean_desa = explode('(', $row['nama_desa'])[0];
            $subdomain = !empty($row['custom_domain']) ? $row['custom_domain'] : strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $clean_desa));
            $row['subdomain'] = $subdomain;
            $row['display_name'] = trim($row['nama_desa']);
            $tenants[] = $row;
        }
    }
    $master_conn->close();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $tenant_type = $_POST['tenant_type'] ?? 'central_admin';

    if ($tenant_type === 'central_admin') {
        if ($username === 'admin' && $password === 'hagtou2026') {
            $_SESSION['hagtou_admin'] = true;
            $_SESSION['hagtou_admin_user'] = 'admin';
            unset($_SESSION['hagtou_bumdes_user']);
            unset($_SESSION['hagtou_bumdes_db']);
            header('Location: /hagtou-dashboard');
            exit;
        } else {
            $error = 'Username atau password admin pusat salah!';
        }
    } else {
        // Authenticate against village tenant DB
        $tenant_db = 'xosid_tenant_' . $tenant_type;
        $tenant_conn = @new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], $tenant_db, $db_cfg['port']);
        if ($tenant_conn->connect_error) {
            $error = 'Gagal menghubungkan ke database desa ' . htmlspecialchars($tenant_type);
        } else {
            $stmt = $tenant_conn->prepare("SELECT id, username, password, nama FROM user WHERE (username = ? OR email = ?) AND deleted_at IS NULL AND active = 1");
            if ($stmt) {
                $stmt->bind_param("ss", $username, $username);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($user = $res->fetch_assoc()) {
                    if (password_verify($password, $user['password'])) {
                        // Success BUMDes login!
                        $_SESSION['hagtou_bumdes_user'] = true;
                        $_SESSION['hagtou_bumdes_user_id'] = $user['id'];
                        $_SESSION['hagtou_bumdes_username'] = $user['username'];
                        $_SESSION['hagtou_bumdes_nama'] = $user['nama'];
                        $_SESSION['hagtou_bumdes_subdomain'] = $tenant_type;
                        $_SESSION['hagtou_bumdes_db'] = $tenant_db;
                        unset($_SESSION['hagtou_admin']);
                        
                        header('Location: /hagtou-dashboard');
                        $stmt->close();
                        $tenant_conn->close();
                        exit;
                    } else {
                        $error = 'Password pengelola BUMDes salah!';
                    }
                } else {
                    $error = 'Akun pengelola BUMDes tidak ditemukan atau tidak aktif!';
                }
                $stmt->close();
            } else {
                $error = 'Gagal memproses otentikasi pengelola!';
            }
            $tenant_conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HagTou Central Admin Login</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc; /* slate-50 */
        }
        h1, h2, h3, .font-title {
            font-family: 'Outfit', sans-serif;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
    </style>
</head>
<body class="text-slate-900 min-h-screen flex items-center justify-center relative overflow-hidden px-4 selection:bg-orange-500/20">

    <!-- Glowing Background Orbs (Option A Style) -->
    <div class="absolute top-[10%] left-[-5%] w-[450px] h-[450px] rounded-full bg-yellow-400/20 blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[10%] right-[-5%] w-[450px] h-[450px] rounded-full bg-orange-400/25 blur-[120px] pointer-events-none"></div>
    <div class="absolute top-[35%] left-[25%] w-[300px] h-[300px] rounded-full bg-yellow-300/30 blur-[90px] pointer-events-none"></div>

    <div class="w-full max-w-md login-card rounded-3xl shadow-[0_20px_50px_rgba(228,107,38,0.08)] p-8 md:p-10 relative z-10">
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 mb-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-orange-500 to-yellow-500 flex items-center justify-center shadow-md shadow-orange-500/10">
                    <i class="fa-solid fa-cubes text-white text-base"></i>
                </div>
                <div>
                    <span class="text-2xl font-bold font-title tracking-wider text-slate-900">hagtou<span class="text-orange-500">.id</span></span>
                </div>
            </div>
            <h2 class="text-2xl font-extrabold text-[#1B3C73] tracking-tight">Sign In to Hagtou</h2>
            <p class="text-slate-500 text-xs mt-1">Welcome back! Please enter your details.</p>
        </div>

        <!-- Error Banner -->
        <?php if (!empty($error)): ?>
            <div class="mb-6 p-4 rounded-xl bg-red-500/5 border border-red-500/20 text-red-600 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-base"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="" method="POST" class="space-y-6">
            <!-- Role / Desa Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Akses / Desa</label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-slate-400 text-sm">
                        <i class="fa-solid fa-hotel"></i>
                    </span>
                    <select name="tenant_type" required class="w-full pl-11 pr-10 py-3.5 rounded-xl bg-slate-100/50 border border-slate-200/80 text-sm focus:outline-none focus:border-[#1B3C73]/50 focus:bg-white transition-all text-slate-800 appearance-none cursor-pointer">
                        <option value="central_admin">Central Admin Hagtou</option>
                        <?php foreach ($tenants as $t): ?>
                            <option value="<?php echo htmlspecialchars($t['subdomain']); ?>"><?php echo htmlspecialchars($t['display_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="absolute right-4 top-4 text-slate-400 pointer-events-none text-xs">
                        <i class="fa-solid fa-chevron-down"></i>
                    </span>
                </div>
            </div>

            <!-- Username -->
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Username / Email</label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-slate-400 text-sm">
                        <i class="fa-regular fa-user"></i>
                    </span>
                    <input type="text" name="username" required placeholder="Enter username / email" class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-slate-100/50 border border-slate-200/80 text-sm focus:outline-none focus:border-[#1B3C73]/50 focus:bg-white transition-all placeholder:text-slate-400 text-slate-800">
                </div>
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Password</label>
                    <a href="#" class="text-xs font-semibold text-[#1B3C73] hover:underline">Forgot Password?</a>
                </div>
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-slate-400 text-sm">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" required placeholder="Enter password" class="w-full pl-11 pr-12 py-3.5 rounded-xl bg-slate-100/50 border border-slate-200/80 text-sm focus:outline-none focus:border-[#1B3C73]/50 focus:bg-white transition-all placeholder:text-slate-400 text-slate-800">
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute right-4 top-3.5 text-slate-400 hover:text-slate-600 transition-colors">
                        <i id="eye-icon" class="fa-regular fa-eye"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 rounded-xl bg-[#1B3C73] text-sm font-bold text-white hover:bg-[#152e5a] shadow-lg shadow-[#1B3C73]/15 hover:shadow-[#1B3C73]/30 transform hover:-translate-y-0.5 transition-all text-center">
                Sign In
            </button>
        </form>

        <!-- Social Sign In Mock -->
        <div class="relative flex py-5 items-center">
            <div class="flex-grow border-t border-slate-200"></div>
            <span class="flex-shrink mx-4 text-slate-400 text-xs font-medium">or continue with</span>
            <div class="flex-grow border-t border-slate-200"></div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <button class="flex items-center justify-center gap-2 py-3 border border-slate-200 hover:bg-slate-50 rounded-xl text-xs font-semibold text-slate-600 transition-colors bg-white">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4" alt="Google"> Google
            </button>
            <button class="flex items-center justify-center gap-2 py-3 border border-slate-200 hover:bg-slate-50 rounded-xl text-xs font-semibold text-slate-600 transition-colors bg-white">
                <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-4 h-4" alt="Facebook"> Facebook
            </button>
        </div>

        <!-- Footer links -->
        <div class="mt-8 text-center border-t border-slate-200/60 pt-6 text-xs text-slate-500">
            Belum Terdaftar di Hagtou? <a href="/daftar-bumdes" class="font-bold text-orange-600 hover:underline">Daftar BUMDes Baru</a>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
