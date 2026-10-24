@extends('admin.layouts.index')

@section('content')
<!-- KATADESA AI: THE NEURAL CORE -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

<div class="ai-neural-core">
    <style>
        :root {
            --neural-bg: #060910;
            --neural-cyan: #06b6d4;
            --neural-indigo: #6366f1;
            --neural-card: rgba(15, 23, 42, 0.7);
            --neural-border: rgba(6, 182, 212, 0.2);
            --font-outfit: 'Outfit', sans-serif;
        }

        /* TOTAL BLACKOUT INTEGRATION */
        body, .wrapper, .content-wrapper, .content, .main-footer, .main-header {
            background: var(--neural-bg) !important;
            border: none !important;
            box-shadow: none !important;
        }

        .ai-neural-core {
            min-height: 100vh;
            color: #f1f5f9;
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
        }

        /* ANIMATED NEURAL MESH BACKGROUND */
        .neural-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.08) 0%, transparent 60%),
                radial-gradient(circle at 20% 30%, rgba(6, 182, 212, 0.05) 0%, transparent 40%);
            z-index: 0;
        }

        .neural-grid {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            perspective: 1000px;
            transform: rotateX(60deg) translateY(-200px);
            mask-image: radial-gradient(circle at center, black, transparent 80%);
            z-index: 0;
            opacity: 0.3;
        }

        /* CORE BRAIN ANIMATION */
        .brain-container {
            position: relative;
            z-index: 2;
            margin-bottom: 30px;
        }

        .brain-core {
            width: 140px;
            height: 140px;
            background: radial-gradient(circle, var(--neural-cyan) 0%, transparent 70%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            filter: drop-shadow(0 0 30px rgba(6, 182, 212, 0.5));
            animation: brain-breathe 4s ease-in-out infinite;
        }

        @keyframes brain-breathe {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.1); opacity: 1; }
        }

        .brain-icon {
            width: 70px;
            height: 70px;
            color: white;
            filter: drop-shadow(0 0 10px rgba(255,255,255,0.8));
        }

        .brain-ring {
            position: absolute;
            inset: -20px;
            border: 1px solid var(--neural-border);
            border-radius: 50%;
            animation: rotate-ring 10s linear infinite;
        }

        @keyframes rotate-ring {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* TYPOGRAPHY */
        .ai-title {
            font-family: var(--font-outfit);
            font-weight: 800;
            font-size: 48px;
            letter-spacing: -1px;
            margin: 0;
            z-index: 2;
            background: linear-gradient(to bottom, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .ai-subtitle {
            font-size: 14px;
            font-weight: 600;
            color: var(--neural-cyan);
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 40px;
            z-index: 2;
        }

        /* HOLOGRAPHIC COMMAND BAR */
        .command-wrapper {
            width: 100%;
            max-width: 850px;
            position: relative;
            z-index: 2;
            margin-bottom: 50px;
        }

        .command-bar {
            background: var(--neural-card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--neural-border);
            border-radius: 20px;
            padding: 10px 10px 10px 30px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5), 0 0 20px rgba(6, 182, 212, 0.1);
            transition: all 0.3s ease;
        }

        .command-bar:focus-within {
            border-color: var(--neural-cyan);
            box-shadow: 0 0 30px rgba(6, 182, 212, 0.3);
            transform: scale(1.02);
        }

        .command-input {
            background: transparent;
            border: none;
            color: white;
            font-family: var(--font-outfit);
            font-size: 20px;
            font-weight: 500;
            width: 100%;
            outline: none;
        }

        .command-btn {
            background: var(--neural-cyan);
            color: white;
            border: none;
            border-radius: 14px;
            padding: 15px 30px;
            font-family: var(--font-outfit);
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 20px rgba(6, 182, 212, 0.3);
            transition: 0.3s;
        }

        .command-btn:hover {
            background: #08e1ff;
            transform: translateY(-2px);
        }

        /* CAPABILITY MODULES */
        .module-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            width: 100%;
            max-width: 1100px;
            z-index: 2;
        }

        .module-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 24px;
            padding: 25px;
            transition: all 0.4s ease;
            cursor: pointer;
            text-align: center;
        }

        .module-card:hover {
            background: rgba(6, 182, 212, 0.05);
            border-color: var(--neural-cyan);
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        }

        .module-icon {
            width: 40px;
            height: 40px;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .module-card:hover .module-icon {
            transform: scale(1.2);
            filter: drop-shadow(0 0 10px var(--neural-cyan));
        }

        .module-title {
            font-family: var(--font-outfit);
            font-weight: 800;
            font-size: 15px;
            color: #f1f5f9;
            margin-bottom: 10px;
        }

        .module-desc {
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.6;
        }

        /* LIVE STATUS */
        .status-hub {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 10;
        }

        .status-badge {
            background: var(--neural-card);
            border: 1px solid var(--neural-border);
            padding: 8px 15px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 800;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(10px);
        }

        .terminal-log {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--neural-cyan);
            margin-top: 20px;
            z-index: 2;
            opacity: 0.7;
        }
    
        /* LIGHT THEME SUPPORT FOR KATADESA AI INDEX */
        body.light-theme html, body.light-theme body, body.light-theme .wrapper, 
        body.light-theme .content-wrapper, body.light-theme .content, body.light-theme .main-footer {
            background: #f8fafc !important;
            background-color: #f8fafc !important;
            color: #0f172a !important;
        }

        body.light-theme .ai-neural-core {
            background: #f8fafc !important;
            color: #0f172a !important;
        }

    </style>

    <div class="neural-background"></div>
    <div class="neural-grid"></div>

    <div class="brain-container">
        <div class="brain-ring"></div>
        <div class="brain-ring" style="animation-direction: reverse; inset: -30px; opacity: 0.5;"></div>
        <div class="brain-core">
            <i data-lucide="brain-circuit" class="brain-icon"></i>
        </div>
    </div>

    <h1 class="ai-title">KATADESA AI</h1>
    <p class="ai-subtitle">Universal Village Intelligence</p>

    <div class="command-wrapper">
        <div class="command-bar">
            <i data-lucide="sparkles" style="color: var(--neural-cyan); width: 24px;"></i>
            <input type="text" class="command-input" placeholder="Tanyakan apa saja tentang data desa Anda...">
            <button class="command-btn">
                ANALISIS <i data-lucide="zap" style="width: 18px;"></i>
            </button>
        </div>
        <div class="terminal-log" id="ai-log">
            SYSTEM_IDLE: Awaiting cognitive input...
        </div>
    </div>

    <div class="module-grid">
        <div class="module-card">
            <i data-lucide="trending-up" class="module-icon" style="color: var(--neural-cyan);"></i>
            <h4 class="module-title">Ekonomi & BUMDes</h4>
            <p class="module-desc">Analisis profitabilitas dan proyeksi pasar untuk produk unggulan desa.</p>
        </div>
        <div class="module-card">
            <i data-lucide="heart-pulse" class="module-icon" style="color: #f43f5e;"></i>
            <h4 class="module-title">Kesehatan & Stunting</h4>
            <p class="module-desc">Deteksi dini anomali kesehatan dan pemantauan nutrisi balita.</p>
        </div>
        <div class="module-card">
            <i data-lucide="landmark" class="module-icon" style="color: #eab308;"></i>
            <h4 class="module-title">Tata Kelola Hukum</h4>
            <p class="module-desc">Otomasi draf peraturan desa dan pemeriksaan kepatuhan regulasi.</p>
        </div>
        <div class="module-card">
            <i data-lucide="users-2" class="module-icon" style="color: #a855f7;"></i>
            <h4 class="module-title">Sosial & Bansos</h4>
            <p class="module-desc">Verifikasi kelayakan bantuan sosial berbasis profil ekonomi real-time.</p>
        </div>
    </div>

    <div class="status-hub">
        <div class="status-badge">
            <span style="width: 6px; height: 6px; background: var(--neural-emerald); border-radius: 50%; box-shadow: 0 0 10px var(--neural-emerald);"></span>
            CORE_NEURAL: ONLINE
        </div>
        <div class="status-badge">
            <i data-lucide="database" style="width: 12px;"></i>
            DATA_SYNC: 100%
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const logs = [
            "SCANNING_VILLAGE_DATABASE...",
            "MODEL_READY: ECONOMIC_PROJECTION_V4",
            "ANALYZING_SOCIAL_PATTERNS...",
            "NEURAL_MESH_STABLE",
            "AWAITING_COMMAND..."
        ];
        let i = 0;
        const logEl = document.getElementById('ai-log');
        
        setInterval(() => {
            if(logEl) {
                logEl.style.opacity = 0;
                setTimeout(() => {
                    logEl.innerText = "SYSTEM_LOG: " + logs[i];
                    logEl.style.opacity = 0.7;
                    i = (i + 1) % logs.length;
                }, 500);
            }
        }, 3500);

        // Redirect to Analisis Cerdas on trigger
        const cmdBtn = document.querySelector('.command-btn');
        const cmdInput = document.querySelector('.command-input');
        if (cmdBtn) {
            cmdBtn.addEventListener('click', () => {
                window.open('<?= site_url("status_desa/navigasi/analisis_cerdas") ?>', '_blank');
            });
        }
        if (cmdInput) {
            cmdInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    window.open('<?= site_url("status_desa/navigasi/analisis_cerdas") ?>', '_blank');
                }
            });
        }
    });
</script>
@endsection
