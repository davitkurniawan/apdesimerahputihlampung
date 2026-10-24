<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelanggan - XOSID SaaS</title>
    <script src="<?php echo asset('js/tailwind.min.js'); ?>"></script>
    <script src="<?= asset("js/lucide.min.js") ?>"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020617; color: white; }
        .glass-panel {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .glow-bg {
            position: absolute; width: 600px; height: 600px; background: radial-gradient(circle, rgba(139,92,246,0.15) 0%, rgba(0,0,0,0) 70%); top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden selection:bg-purple-500/30">
    <div class="glow-bg"></div>
    
    <div class="w-full max-w-md px-6 relative z-10">
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-gradient-to-tr from-purple-600 to-cyan-500 rounded-2xl mx-auto flex items-center justify-center shadow-[0_0_30px_rgba(168,85,247,0.3)] mb-6 transform rotate-3">
                <i data-lucide="users" class="text-white w-8 h-8"></i>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight">Portal Pelanggan</h1>
            <p class="text-slate-400 mt-2 text-sm">Masuk untuk mengelola langganan XOSID Anda.</p>
        </div>

        <div class="glass-panel rounded-[32px] p-8 md:p-10 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-cyan-500 via-purple-500 to-pink-500"></div>
            
            <div id="error-message" class="hidden mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl text-red-400 text-xs font-medium text-center"></div>

            <form id="login-form" class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Email Pendaftar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-slate-500"></i>
                        </div>
                        <input type="email" id="email" name="email" class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3.5 pl-12 pr-4 text-white placeholder-slate-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all text-sm" placeholder="contoh@desa.id" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Kata Sandi (Nomor WA)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="key" class="w-5 h-5 text-slate-500"></i>
                        </div>
                        <input type="password" id="password" name="password" class="w-full bg-slate-900/50 border border-white/10 rounded-xl py-3.5 pl-12 pr-4 text-white placeholder-slate-500 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all text-sm" placeholder="Masukkan nomor WA pendaftaran" required>
                    </div>
                    <div class="text-right mt-2">
                        <a href="/member/lupa-password" class="text-xs text-purple-400 hover:text-purple-300 transition-colors">Lupa Kata Sandi?</a>
                    </div>
                </div>

                <button type="submit" id="btn-submit" class="w-full py-4 bg-white text-slate-900 hover:bg-slate-200 rounded-xl text-sm font-bold shadow-[0_0_20px_rgba(255,255,255,0.2)] transition-all flex justify-center items-center gap-2 mt-4">
                    <span>Masuk ke Portal</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
            
            <div class="mt-8 text-center">
                <a href="/" class="text-xs text-slate-400 hover:text-white transition-colors flex items-center justify-center gap-1">
                    <i data-lucide="arrow-left" class="w-3 h-3"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        
        document.getElementById('login-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-submit');
            const errorDiv = document.getElementById('error-message');
            const formData = new FormData(e.target);
            
            btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memverifikasi...';
            btn.disabled = true;
            lucide.createIcons();
            errorDiv.classList.add('hidden');

            try {
                const response = await fetch('/api/member/login', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                
                if (result.success) {
                    btn.innerHTML = '<i data-lucide="check" class="w-4 h-4 text-green-500"></i> Berhasil';
                    window.location.href = '/member';
                } else {
                    errorDiv.textContent = result.message;
                    errorDiv.classList.remove('hidden');
                    btn.innerHTML = '<span>Masuk ke Portal</span><i data-lucide="arrow-right" class="w-4 h-4"></i>';
                    btn.disabled = false;
                    lucide.createIcons();
                }
            } catch (err) {
                errorDiv.textContent = "Terjadi kesalahan sistem.";
                errorDiv.classList.remove('hidden');
                btn.innerHTML = '<span>Masuk ke Portal</span><i data-lucide="arrow-right" class="w-4 h-4"></i>';
                btn.disabled = false;
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
