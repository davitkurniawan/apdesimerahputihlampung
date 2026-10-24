<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    @session_start();
}
$partner = $_SESSION['partner_user'] ?? [
    'name' => 'PT Mitra Teknologi Nusantara',
    'email' => 'mitra@xosid.id',
    'partner_id' => 'MTR-2026-XOSID-88',
    'tier' => 'Enterprise Partner',
    'api_key' => 'xosid_live_pk_88921a4f00e9812b7a',
    'secret_key' => 'xosid_sk_9918237192837192',
    'ip_whitelist' => '103.144.178.10, 180.252.12.89'
];
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Partner & Mitra Kerjasama - XOSID Supradesa</title>
    <script src="<?php echo function_exists('asset') ? asset('js/tailwind.min.js') : '/assets/js/tailwind.min.js'; ?>"></script>
    <script src="<?= asset("js/lucide.min.js") ?>"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020617; color: #f8fafc; }
        .glass-card {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.6);
        }
        .glow-circle {
            position: fixed; border-radius: 50%; filter: blur(120px); opacity: 0.15; pointer-events: none; z-index: 0;
        }
    </style>
</head>
<body class="min-h-screen relative selection:bg-cyan-500/30 pb-20">
    <div class="glow-circle w-[600px] h-[600px] bg-cyan-500 top-[-100px] right-[-100px]"></div>
    <div class="glow-circle w-[500px] h-[500px] bg-indigo-500 bottom-[-100px] left-[-100px]"></div>

    <!-- Header Navigation -->
    <header class="w-full z-50 px-6 py-4 fixed top-0 backdrop-blur-md bg-slate-950/70 border-b border-white/5">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <a href="/partner/dashboard" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-tr from-cyan-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                    <i data-lucide="handshake" class="text-white w-5 h-5"></i>
                </div>
                <div>
                    <h1 class="text-base font-black tracking-tight leading-none text-white">PORTAL <span class="text-cyan-400">PARTNER</span></h1>
                    <p class="text-[9px] font-bold tracking-widest text-slate-400 uppercase mt-0.5">XOSID SUPRADESA</p>
                </div>
            </a>
            <div class="flex items-center gap-3">
                <a href="/partner/login?action=logout" class="px-4 py-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 text-red-400 hover:text-red-300 font-bold text-xs transition-all flex items-center gap-1.5">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i> Keluar
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Stack (Single Column Max-W-4XL per Rule #5) -->
    <main class="relative z-10 pt-28 max-w-4xl mx-auto px-6 space-y-6">

        <!-- Partner Banner Card -->
        <div class="glass-card rounded-[28px] p-8 border-t-2 border-t-cyan-500/40 relative overflow-hidden">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-black tracking-widest uppercase mb-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        VERIFIED ENTERPRISE PARTNER
                    </div>
                    <h2 class="text-2xl md:text-3xl font-black text-white"><?php echo htmlspecialchars($partner['name']); ?></h2>
                    <p class="text-xs text-slate-400 mt-1 font-medium">ID Partner: <code class="text-cyan-400 font-mono font-bold"><?php echo htmlspecialchars($partner['partner_id']); ?></code> • <?php echo htmlspecialchars($partner['email']); ?></p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="/kerjasama" class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold text-xs transition-all flex items-center gap-1.5">
                        <i data-lucide="file-text" class="w-4 h-4 text-cyan-400"></i> MoU Kerjasama
                    </a>
                    <a href="https://localhost:8443" target="_blank" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 hover:from-cyan-400 hover:to-indigo-500 text-white font-bold text-xs transition-all shadow-[0_0_15px_rgba(6,182,212,0.3)] flex items-center gap-1.5">
                        <i data-lucide="video" class="w-4 h-4"></i> DesaMeet Rapat
                    </a>
                </div>
            </div>
        </div>

        <!-- API Key & Credentials Manager -->
        <div class="glass-card rounded-[28px] p-8 space-y-6">
            <div class="flex items-center justify-between border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg font-extrabold text-white flex items-center gap-2">
                        <i data-lucide="key" class="w-5 h-5 text-cyan-400"></i> Kredensial API Gateway
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Gunakan API Key ini pada header HTTP <code class="text-cyan-400 font-mono">X-XOSID-API-KEY</code> di seluruh request integrasi.</p>
                </div>
                <button onclick="regenerateKey()" class="px-3.5 py-2 rounded-xl bg-white/5 hover:bg-cyan-500/10 border border-white/10 hover:border-cyan-500/30 text-slate-300 hover:text-cyan-400 text-xs font-bold transition-all flex items-center gap-1.5">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Regenerate Key
                </button>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Live Production API Key</label>
                    <div class="flex items-center gap-2">
                        <input type="text" id="api-key-input" readonly value="<?php echo htmlspecialchars($partner['api_key']); ?>" class="flex-grow bg-slate-950/80 border border-white/10 rounded-xl py-3 px-4 text-cyan-400 font-mono text-xs font-bold tracking-wider focus:outline-none">
                        <button onclick="copyApiKey()" class="px-4 py-3 bg-cyan-500/10 hover:bg-cyan-500/20 border border-cyan-500/30 text-cyan-300 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                            <i data-lucide="copy" class="w-4 h-4"></i> <span id="copy-btn-text">Salin</span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">IP Whitelist Server Partner</label>
                    <div class="flex items-center gap-2">
                        <input type="text" id="ip-whitelist-input" value="<?php echo htmlspecialchars($partner['ip_whitelist']); ?>" class="flex-grow bg-slate-950/80 border border-white/10 rounded-xl py-3 px-4 text-slate-200 font-mono text-xs font-medium focus:outline-none focus:border-cyan-500">
                        <button onclick="updateIpWhitelist()" class="px-4 py-3 bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                            <i data-lucide="save" class="w-4 h-4"></i> Simpan IP
                        </button>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1.5">Pisahkan IP server produksi dengan koma (,). IP tidak terdaftar akan ditolak oleh API Gateway.</p>
                </div>
            </div>
        </div>

        <!-- API Stats & Usage Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="glass-card rounded-2xl p-5 border-t border-t-cyan-500/30">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400">Kuota Request Harian</span>
                    <i data-lucide="activity" class="w-4 h-4 text-cyan-400"></i>
                </div>
                <div class="text-xl font-black text-white">14,250 <span class="text-xs font-normal text-slate-400">/ 50,000</span></div>
                <div class="w-full bg-slate-900 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div class="bg-cyan-400 h-full w-[28.5%]"></div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-5 border-t border-t-indigo-500/30">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400">API Gateway Uptime</span>
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <div class="text-xl font-black text-white">99.98%</div>
                <p class="text-[10px] text-emerald-400 mt-2 font-medium">SLA Production Active</p>
            </div>

            <div class="glass-card rounded-2xl p-5 border-t border-t-purple-500/30">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-400">Rata-Rata Latensi</span>
                    <i data-lucide="zap" class="w-4 h-4 text-purple-400"></i>
                </div>
                <div class="text-xl font-black text-white">42 ms</div>
                <p class="text-[10px] text-slate-400 mt-2 font-medium">Optimized Neural Cache</p>
            </div>
        </div>

        <!-- Quick Access & Interactive Tools -->
        <div class="glass-card rounded-[28px] p-8 space-y-4">
            <h3 class="text-base font-extrabold text-white flex items-center gap-2">
                <i data-lucide="terminal" class="w-5 h-5 text-indigo-400"></i> Alat Integrasi & Konsol Partner
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="/api-access" class="p-4 rounded-2xl bg-slate-900/60 hover:bg-slate-900 border border-white/5 hover:border-cyan-500/30 transition-all group flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-cyan-500/10 flex items-center justify-center text-cyan-400 group-hover:scale-110 transition-transform">
                        <i data-lucide="code-2" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white group-hover:text-cyan-400 transition-colors">Interactive API Console</h4>
                        <p class="text-xs text-slate-400 mt-1">Uji coba endpoint OpenAPI v1 langsung dari browser Anda.</p>
                    </div>
                </a>

                <a href="https://localhost:8443" target="_blank" class="p-4 rounded-2xl bg-slate-900/60 hover:bg-slate-900 border border-white/5 hover:border-indigo-500/30 transition-all group flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-transform">
                        <i data-lucide="video" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white group-hover:text-indigo-400 transition-colors">DesaMeet Platform</h4>
                        <p class="text-xs text-slate-400 mt-1">Ruang telekonferensi & rapat online desa terintegrasi.</p>
                    </div>
                </a>
            </div>
        </div>

    </main>

    <script>
        lucide.createIcons();

        function copyApiKey() {
            const apiKeyInput = document.getElementById('api-key-input');
            navigator.clipboard.writeText(apiKeyInput.value);
            const copyBtnText = document.getElementById('copy-btn-text');
            copyBtnText.innerText = 'Tersalin!';
            setTimeout(() => { copyBtnText.innerText = 'Salin'; }, 2000);
        }

        async function updateIpWhitelist() {
            const ipInput = document.getElementById('ip-whitelist-input');
            const formData = new FormData();
            formData.append('ip_whitelist', ipInput.value);
            try {
                const res = await fetch('/api/partner/update-ip', { method: 'POST', body: formData });
                const json = await res.json();
                alert(json.message || 'IP Whitelist berhasil diperbarui.');
            } catch(e) {
                alert('Gagal memperbarui IP Whitelist.');
            }
        }

        async function regenerateKey() {
            if (!confirm('Apakah Anda yakin ingin melakukan Regenerate API Key? API Key lama akan tidak berlaku.')) return;
            try {
                const res = await fetch('/api/partner/regenerate-key', { method: 'POST' });
                const json = await res.json();
                if (json.success && json.new_api_key) {
                    document.getElementById('api-key-input').value = json.new_api_key;
                    alert('API Key baru berhasil dihasilkan!');
                }
            } catch(e) {
                alert('Gagal melakukan regenerate API Key.');
            }
        }
    </script>
</body>
</html>
