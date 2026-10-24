<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Partner & Mitra Kerjasama - XOSID Supradesa</title>
    <script src="<?php echo function_exists('asset') ? asset('js/tailwind.min.js') : '/assets/js/tailwind.min.js'; ?>"></script>
    <script src="<?= asset("js/lucide.min.js") ?>"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020617; color: white; }
        .glass-panel {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.7);
        }
        .glow-bg {
            position: absolute; width: 700px; height: 700px; background: radial-gradient(circle, rgba(6,182,212,0.18) 0%, rgba(99,102,241,0.12) 40%, rgba(0,0,0,0) 70%); top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden selection:bg-cyan-500/30">
    <div class="glow-bg"></div>
    
    <div class="w-full max-w-md px-6 relative z-10 my-12">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-tr from-cyan-500 via-indigo-500 to-purple-600 rounded-2xl mx-auto flex items-center justify-center shadow-[0_0_30px_rgba(6,182,212,0.4)] mb-5 transform -rotate-3 border border-white/20">
                <i data-lucide="handshake" class="text-white w-8 h-8"></i>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-[10px] font-black tracking-widest uppercase mb-3">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> PORTAL MITRA RESMI
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight">Dashboard Partner</h1>
            <p class="text-slate-400 mt-2 text-xs leading-relaxed max-w-sm mx-auto">
                Kelola Kredensial API (<code class="text-cyan-400 font-mono">X-XOSID-API-KEY</code>), Whitelist IP, dan Akses Data Desa Terintegrasi.
            </p>
        </div>

        <div class="glass-panel rounded-[32px] p-8 md:p-10 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-cyan-400 via-indigo-500 to-purple-500"></div>
            
            <div id="error-message" class="hidden mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-400 text-xs font-medium text-center"></div>

            <form id="partner-login-form" class="space-y-5">
                <div>
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-2">Email / ID Partner</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="building-2" class="w-4 h-4 text-slate-500"></i>
                        </div>
                        <input type="email" id="email" name="email" class="w-full bg-slate-900/70 border border-white/10 rounded-xl py-3.5 pl-11 pr-4 text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all text-sm font-medium" placeholder="mitra@perusahaan.co.id" value="mitra@xosid.id" required>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-300 uppercase tracking-wider mb-2">Kata Sandi / Secret Key</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="key" class="w-4 h-4 text-slate-500"></i>
                        </div>
                        <input type="password" id="password" name="password" class="w-full bg-slate-900/70 border border-white/10 rounded-xl py-3.5 pl-11 pr-4 text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all text-sm font-medium" placeholder="••••••••••••" value="partner123" required>
                    </div>
                </div>

                <!-- Demo Credentials Quick Access Callout -->
                <div class="p-3.5 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-xs flex items-center justify-between">
                    <span class="font-medium text-[11px]">🔑 Demo Partner Mode Active</span>
                    <button type="button" onclick="fillDemoCredentials()" class="font-bold text-[11px] underline hover:text-white transition-colors">Ganti Demo</button>
                </div>

                <button type="submit" id="btn-submit" class="w-full py-4 bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white rounded-xl text-sm font-bold shadow-[0_0_25px_rgba(6,182,212,0.4)] transition-all flex justify-center items-center gap-2 mt-2">
                    <span>Masuk ke Dashboard Partner</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
            
            <div class="mt-8 pt-6 border-t border-white/5 flex flex-col gap-2 text-center text-xs text-slate-400">
                <a href="/kerjasama" class="hover:text-cyan-400 transition-colors flex items-center justify-center gap-1.5">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Kembali ke Halaman Kerjasama
                </a>
                <a href="/api-access" class="hover:text-indigo-400 transition-colors text-[11px] mt-1">
                    Petunjuk Integrasi API Gateway →
                </a>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function fillDemoCredentials() {
            document.getElementById('email').value = 'mitra@xosid.id';
            document.getElementById('password').value = 'partner123';
        }
        
        document.getElementById('partner-login-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-submit');
            const errorDiv = document.getElementById('error-message');
            const formData = new FormData(e.target);
            
            btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Autentikasi Partner...';
            btn.disabled = true;
            lucide.createIcons();
            errorDiv.classList.add('hidden');

            try {
                const response = await fetch('/api/partner/login', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                if (result.success) {
                    btn.innerHTML = '<i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Berhasil Log Masuk';
                    setTimeout(() => {
                        window.location.href = result.redirect || '/partner/dashboard';
                    }, 500);
                } else {
                    errorDiv.innerText = result.message || 'Login gagal, periksa kredensial Anda.';
                    errorDiv.classList.remove('hidden');
                    btn.innerHTML = '<span>Masuk ke Dashboard Partner</span> <i data-lucide="arrow-right" class="w-4 h-4"></i>';
                    btn.disabled = false;
                    lucide.createIcons();
                }
            } catch (err) {
                errorDiv.innerText = 'Terjadi kesalahan sistem koneksi.';
                errorDiv.classList.remove('hidden');
                btn.innerHTML = '<span>Masuk ke Dashboard Partner</span> <i data-lucide="arrow-right" class="w-4 h-4"></i>';
                btn.disabled = false;
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
