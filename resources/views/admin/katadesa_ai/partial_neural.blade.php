<!-- KATADESA AI: THE NEURAL CORE PARTIAL -->
<div class="ai-neural-core">
    <style>
        /* TOTAL BLACKOUT - Force global dark theme */
        html, body, .wrapper, .content-wrapper, .content, .main-footer, .main-header {
            background: #060910 !important;
            background-color: #060910 !important;
            border: none !important;
            box-shadow: none !important;
            color: #f1f5f9 !important;
        }

        .ai-neural-core {
            min-height: 100vh;
            color: #f1f5f9;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            background: #060910 !important;
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
            background: radial-gradient(circle, #06b6d4 0%, transparent 70%);
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
            border: 1px solid rgba(6, 182, 212, 0.2);
            border-radius: 50%;
            animation: rotate-ring 10s linear infinite;
        }

        @keyframes rotate-ring {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .ai-title {
            font-family: 'Outfit', sans-serif;
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
            color: #06b6d4;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 40px;
            z-index: 2;
        }

        .command-wrapper {
            width: 100%;
            max-width: 850px;
            position: relative;
            z-index: 2;
            margin-bottom: 50px;
        }

        .command-bar {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(6, 182, 212, 0.2);
            border-radius: 20px;
            padding: 10px 10px 10px 30px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5), 0 0 20px rgba(6, 182, 212, 0.1);
        }

        .command-input {
            background: transparent;
            border: none;
            color: white;
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 500;
            width: 100%;
            outline: none;
        }

        .command-btn {
            background: #06b6d4;
            color: white;
            border: none;
            border-radius: 14px;
            padding: 15px 30px;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 20px rgba(6, 182, 212, 0.3);
        }

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
            border-color: #06b6d4;
            transform: translateY(-10px);
        }

        .terminal-log {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: #06b6d4;
            margin-top: 20px;
            z-index: 2;
            opacity: 0.7;
        }
    
        /* LIGHT THEME SUPPORT FOR KATADESA AI */
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
        
        body.light-theme .ai-title {
            background: linear-gradient(to bottom, #0f172a, #64748b);
            -webkit-background-clip: text;
        }
        
        body.light-theme .command-bar {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(6, 182, 212, 0.4);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05), 0 0 20px rgba(6, 182, 212, 0.1);
        }
        
        body.light-theme .command-input {
            color: #0f172a;
        }
        
        body.light-theme .module-card {
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }
        
        body.light-theme .module-card:hover {
            background: #f8fafc;
            border-color: #06b6d4;
        }
        
        body.light-theme .module-card p {
            color: #64748b !important;
        }
        
        body.light-theme .module-card h4 {
            color: #0f172a !important;
        }
        
        body.light-theme .neural-grid {
            background-image: linear-gradient(rgba(0, 0, 0, 0.05) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(0, 0, 0, 0.05) 1px, transparent 1px);
        }
        
        body.light-theme .brain-core {
            background: radial-gradient(circle, #06b6d4 0%, transparent 70%);
            filter: drop-shadow(0 0 20px rgba(6, 182, 212, 0.3));
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
            <i data-lucide="sparkles" style="color: #06b6d4; width: 24px;"></i>
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
            <i data-lucide="trending-up" style="color: #06b6d4; width: 32px; height: 32px; margin-bottom: 15px;"></i>
            <h4 style="font-family: 'Outfit'; font-weight: 800; font-size: 14px; margin-bottom: 8px;">Ekonomi</h4>
            <p style="font-size: 11px; color: #94a3b8;">Analisis profitabilitas BUMDes.</p>
        </div>
        <div class="module-card">
            <i data-lucide="heart-pulse" style="color: #f43f5e; width: 32px; height: 32px; margin-bottom: 15px;"></i>
            <h4 style="font-family: 'Outfit'; font-weight: 800; font-size: 14px; margin-bottom: 8px;">Kesehatan</h4>
            <p style="font-size: 11px; color: #94a3b8;">Deteksi stunting otomatis.</p>
        </div>
        <div class="module-card">
            <i data-lucide="landmark" style="color: #eab308; width: 32px; height: 32px; margin-bottom: 15px;"></i>
            <h4 style="font-family: 'Outfit'; font-weight: 800; font-size: 14px; margin-bottom: 8px;">Hukum</h4>
            <p style="font-size: 11px; color: #94a3b8;">Otomasi draf Perdes.</p>
        </div>
        <div class="module-card">
            <i data-lucide="users-2" style="color: #a855f7; width: 32px; height: 32px; margin-bottom: 15px;"></i>
            <h4 style="font-family: 'Outfit'; font-weight: 800; font-size: 14px; margin-bottom: 8px;">Sosial</h4>
            <p style="font-size: 11px; color: #94a3b8;">Verifikasi kelayakan bansos.</p>
        </div>
    </div>
</div>
