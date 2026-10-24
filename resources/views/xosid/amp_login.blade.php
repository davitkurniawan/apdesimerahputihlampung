<?php
/**
 * AMP.XOSID - Member Login Page
 * Asosiasi Pemerintah Desa Indonesia Merah Putih Provinsi Lampung
 */
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
if (!empty($_SESSION['amp_user_id'])) {
    header('Location: /dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Member - AMP Merah Putih Lampung</title>
    <!-- Favicon APDesi Merah Putih -->
    <link rel="icon" type="image/png" sizes="32x32" href="/public/images/favicon_amp.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/public/images/favicon_amp.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 antialiased min-h-screen flex flex-col">
<?php $amp_active_menu = "login"; require __DIR__ . "/amp_header_partial.php"; ?>
<div class="flex-grow flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-800 to-red-900 text-white p-8 text-center flex flex-col items-center">
            <div class="w-16 h-20 mb-3 flex items-center justify-center filter drop-shadow-md">
                <img src="/public/images/logo_amp.png" alt="Logo APDesi Merah Putih" class="w-full h-full object-contain">
            </div>
            <h2 class="text-2xl font-extrabold">Login Member AMP</h2>
            <p class="text-red-200 text-xs mt-1">Asosiasi Pemerintah Desa Indonesia Merah Putih</p>
        </div>

        <!-- Form -->
        <form id="form-amp-login" class="p-6 space-y-4">
            <div id="alert-box" class="hidden p-4 rounded-2xl text-xs font-medium"></div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">NIK atau Email *</label>
                <input type="text" name="nik_or_email" required placeholder="NIK 16 digit atau email terdaftar" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none text-sm transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Kata Sandi *</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-600 focus:border-red-600 outline-none text-sm transition">
            </div>

            <button type="submit" id="btn-submit" class="w-full py-3.5 bg-gradient-to-r from-red-700 to-red-800 hover:from-red-800 hover:to-red-900 text-white font-bold text-sm rounded-xl shadow-md shadow-red-200 transition">
                Masuk ke Dashboard →
            </button>

            <div class="text-center pt-2">
                <span class="text-xs text-gray-500">Belum terdaftar?</span>
                <a href="/registrasi" class="text-xs font-bold text-red-700 hover:underline ml-1">Daftar Keanggotaan Baru</a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('form-amp-login').addEventListener('submit', (e) => {
            e.preventDefault();
            const alertBox = document.getElementById('alert-box');
            const btn = document.getElementById('btn-submit');
            btn.disabled = true;

            const formData = new FormData(e.target);
            const payload = {};
            formData.forEach((val, key) => payload[key] = val);

            fetch('/api/amp/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    alertBox.className = 'p-3 rounded-xl text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200 block';
                    alertBox.textContent = res.message;
                    setTimeout(() => window.location.href = res.redirect || '/dashboard', 1000);
                } else {
                    alertBox.className = 'p-3 rounded-xl text-xs font-medium bg-red-100 text-red-800 border border-red-200 block';
                    alertBox.textContent = res.message || 'Login gagal.';
                    btn.disabled = false;
                }
            })
            .catch(() => {
                alertBox.className = 'p-3 rounded-xl text-xs font-medium bg-red-100 text-red-800 border border-red-200 block';
                alertBox.textContent = 'Gagal terhubung ke server.';
                btn.disabled = false;
            });
        });
    </script>
</div>
</body>
</html>
