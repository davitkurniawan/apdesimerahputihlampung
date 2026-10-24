<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Login - XOSID</title>
    
    <script src="<?php echo asset('js/tailwind.min.js'); ?>"></script>
    <script src="<?= asset("js/lucide.min.js") ?>"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-bg: #030712;
            --color-indigo: #6366f1;
            --color-cyan: #06b6d4;
            --color-glass: rgba(15, 23, 42, 0.6);
            --color-border: rgba(255, 255, 255, 0.08);
        }

        body {
            background-color: var(--color-bg);
            color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        .bg-grid {
            position: fixed;
            inset: 0;
            background-image: 
                linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
            pointer-events: none;
        }

        .glass-panel {
            background: var(--color-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--color-border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .glow-circle {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, rgba(6,182,212,0) 70%);
            border-radius: 50%;
            z-index: 0;
            pointer-events: none;
        }
    </style>
</head>
<body class="selection:bg-cyan-500/20 selection:text-cyan-200">
    
    <div class="bg-grid"></div>
    <div class="glow-circle top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></div>

    <div class="relative z-10 w-full max-w-md px-6">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-tr from-indigo-600 to-cyan-500 rounded-2xl mx-auto flex items-center justify-center shadow-[0_0_30px_rgba(6,182,212,0.5)] mb-6 transform -rotate-6">
                <i data-lucide="shield-check" class="text-white w-8 h-8"></i>
            </div>
            <h1 class="text-3xl font-black tracking-tight text-white leading-tight">XOSID <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-400">Pusat</span></h1>
            <p class="text-slate-400 mt-2 font-medium">Otentikasi Superadmin Dasbor</p>
        </div>

        <div class="glass-panel rounded-2xl p-8">
            <div id="error-msg" class="hidden mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
                <span id="error-text">Username atau password salah.</span>
            </div>

            <form id="login-form" onsubmit="handleLogin(event)" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold tracking-wider text-slate-400 uppercase mb-2">Username</label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500"></i>
                        <input type="text" id="username" required
                            class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 pl-12 pr-4 text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition"
                            placeholder="admin pusat">
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-bold tracking-wider text-slate-400 uppercase mb-2">Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500"></i>
                        <input type="password" id="password" required
                            class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 pl-12 pr-4 text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" id="btn-submit" class="w-full mt-6 bg-gradient-to-r from-indigo-600 to-cyan-500 hover:from-indigo-500 hover:to-cyan-400 text-white font-bold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 shadow-[0_0_20px_rgba(99,102,241,0.3)] transition transform hover:-translate-y-0.5">
                    Login Sistem <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <form id="2fa-form" onsubmit="handle2FA(event)" class="space-y-5 hidden">
                <div class="text-center mb-4">
                    <p class="text-slate-300 text-sm">Sistem dilindungi 2FA. Masukkan kode 6 digit dari aplikasi Google Authenticator Anda.</p>
                </div>
                <div>
                    <label class="block text-xs font-bold tracking-wider text-slate-400 uppercase mb-2 text-center">Kode OTP</label>
                    <div class="relative">
                        <input type="text" id="otp_code" required maxlength="6"
                            class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3 px-4 text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition text-center tracking-[0.5em] text-2xl font-mono"
                            placeholder="000000">
                    </div>
                </div>

                <button type="submit" id="btn-submit-2fa" class="w-full mt-6 bg-gradient-to-r from-indigo-600 to-cyan-500 hover:from-indigo-500 hover:to-cyan-400 text-white font-bold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 shadow-[0_0_20px_rgba(99,102,241,0.3)] transition transform hover:-translate-y-0.5">
                    Verifikasi OTP <i data-lucide="shield-check" class="w-4 h-4"></i>
                </button>
                <button type="button" onclick="cancel2FA()" class="w-full mt-2 text-slate-400 hover:text-white text-sm transition">Batal & Kembali</button>
            </form>
        </div>
        
        <p class="text-center text-xs text-slate-500 mt-8">
            &copy; 2026 PT. XOSID Nusantara. Restricted Access.
        </p>
    </div>

    <script>
        lucide.createIcons();

        async function handleLogin(e) {
            e.preventDefault();
            
            const btn = document.getElementById('btn-submit');
            const errorMsg = document.getElementById('error-msg');
            const errorText = document.getElementById('error-text');
            
            btn.innerHTML = '<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i> Memverifikasi...';
            btn.disabled = true;
            lucide.createIcons();
            errorMsg.classList.add('hidden');

            const u = document.getElementById('username').value;
            const p = document.getElementById('password').value;

            try {
                const response = await fetch('/api/superadmin/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: u, password: p })
                });
                
                const result = await response.json();
                
                if (response.ok && result.status === 'success') {
                    // Redirect to dashboard
                    btn.innerHTML = '<i data-lucide="check" class="w-5 h-5"></i> Berhasil';
                    lucide.createIcons();
                    window.location.href = '/superadmin/verifikasi';
                } else if (response.ok && result.status === '2fa_required') {
                    // Tampilkan form 2FA
                    document.getElementById('login-form').classList.add('hidden');
                    document.getElementById('2fa-form').classList.remove('hidden');
                    document.getElementById('otp_code').focus();
                    
                    btn.innerHTML = 'Login Sistem <i data-lucide="arrow-right" class="w-4 h-4"></i>';
                    btn.disabled = false;
                    lucide.createIcons();
                } else {
                    errorText.textContent = result.message || 'Login gagal.';
                    errorMsg.classList.remove('hidden');
                    
                    btn.innerHTML = 'Login Sistem <i data-lucide="arrow-right" class="w-4 h-4"></i>';
                    btn.disabled = false;
                    lucide.createIcons();
                }
            } catch (error) {
                errorText.textContent = 'Terjadi kesalahan jaringan.';
                errorMsg.classList.remove('hidden');
                
                btn.innerHTML = 'Login Sistem <i data-lucide="arrow-right" class="w-4 h-4"></i>';
                btn.disabled = false;
                lucide.createIcons();
            }
        }

        function cancel2FA() {
            document.getElementById('2fa-form').classList.add('hidden');
            document.getElementById('login-form').classList.remove('hidden');
            document.getElementById('otp_code').value = '';
            document.getElementById('error-msg').classList.add('hidden');
        }

        async function handle2FA(e) {
            e.preventDefault();
            
            const btn = document.getElementById('btn-submit-2fa');
            const errorMsg = document.getElementById('error-msg');
            const errorText = document.getElementById('error-text');
            
            btn.innerHTML = '<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i> Memverifikasi...';
            btn.disabled = true;
            lucide.createIcons();
            errorMsg.classList.add('hidden');

            const u = document.getElementById('username').value;
            const p = document.getElementById('password').value;
            const otp = document.getElementById('otp_code').value;

            try {
                const response = await fetch('/api/superadmin/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username: u, password: p, otp: otp })
                });
                
                const result = await response.json();
                
                if (response.ok && result.status === 'success') {
                    btn.innerHTML = '<i data-lucide="check" class="w-5 h-5"></i> Berhasil';
                    lucide.createIcons();
                    window.location.href = '/superadmin/verifikasi';
                } else {
                    errorText.textContent = result.message || 'OTP salah.';
                    errorMsg.classList.remove('hidden');
                    
                    btn.innerHTML = 'Verifikasi OTP <i data-lucide="shield-check" class="w-4 h-4"></i>';
                    btn.disabled = false;
                    lucide.createIcons();
                }
            } catch (error) {
                errorText.textContent = 'Terjadi kesalahan jaringan.';
                errorMsg.classList.remove('hidden');
                
                btn.innerHTML = 'Verifikasi OTP <i data-lucide="shield-check" class="w-4 h-4"></i>';
                btn.disabled = false;
                lucide.createIcons();
            }
        }
    </script>
</body>
</html>
