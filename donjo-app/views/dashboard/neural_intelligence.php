<!-- 
    XOSID SUPRADESA - NEURAL INTELLIGENCE CENTER
    Design: Neural-Dark Glassmorphism v2.0
-->
<div class="content-wrapper" style="background: #0f172a; min-height: 100vh; padding: 20px;">
    <section class="content-header" style="margin-bottom: 30px;">
        <h1 style="color: white; font-weight: 800; font-size: 28px; letter-spacing: -0.5px;">
            Intelligence <span style="color: #06b6d4;">Center</span>
            <small style="color: #64748b; font-size: 14px; display: block; margin-top: 5px;">Selamat datang kembali, Administrator Supradesa.</small>
        </h1>
    </section>

    <section class="content">
        <!-- STATS GRID -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="neural-card" style="background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; padding: 25px; transition: all 0.3s ease; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1;">
                        <i data-lucide="users" style="width: 80px; height: 80px; color: #06b6d4;"></i>
                    </div>
                    <h3 style="color: white; font-size: 32px; font-weight: 800; margin: 0;"><?= number_format($jumlah_penduduk) ?></h3>
                    <p style="color: #94a3b8; font-size: 13px; font-weight: 500; margin-top: 5px;">Total Penduduk</p>
                    <div style="margin-top: 15px; height: 4px; background: rgba(6, 182, 212, 0.1); border-radius: 2px;">
                        <div style="width: 100%; height: 100%; background: #06b6d4; border-radius: 2px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="neural-card" style="background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.05); border-radius: 20px; padding: 25px; transition: all 0.3s ease; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -10px; right: -10px; opacity: 0.1;">
                        <i data-lucide="home" style="width: 80px; height: 80px; color: #8b5cf6;"></i>
                    </div>
                    <h3 style="color: white; font-size: 32px; font-weight: 800; margin: 0;"><?= number_format($jumlah_keluarga) ?></h3>
                    <p style="color: #94a3b8; font-size: 13px; font-weight: 500; margin-top: 5px;">Total Keluarga</p>
                    <div style="margin-top: 15px; height: 4px; background: rgba(139, 92, 246, 0.1); border-radius: 2px;">
                        <div style="width: 100%; height: 100%; background: #8b5cf6; border-radius: 2px;"></div>
                    </div>
                </div>
            </div>
            <!-- Additional Stat Cards can go here -->
        </div>

        <!-- MAIN AI PANEL -->
        <div class="row" style="margin-top: 30px;">
            <div class="col-md-8">
                <div class="neural-glass-panel" style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.9)); border-radius: 25px; border: 1px solid rgba(6, 182, 212, 0.2); padding: 40px; min-height: 400px; display: flex; flex-direction: column; justify-content: center; position: relative; overflow: hidden;">
                    <div style="position: absolute; bottom: -50px; right: -50px; opacity: 0.05;">
                        <i data-lucide="brain-circuit" style="width: 300px; height: 300px; color: #06b6d4;"></i>
                    </div>
                    <div style="display: inline-block; background: rgba(6, 182, 212, 0.1); color: #06b6d4; padding: 5px 15px; border-radius: 50px; font-size: 10px; font-weight: 800; letter-spacing: 1px; margin-bottom: 20px;">
                        KATADESA AI ENGINE ACTIVE
                    </div>
                    <h2 style="color: white; font-size: 42px; font-weight: 900; line-height: 1.1; margin-bottom: 20px;">
                        Wujudkan Desa Mandiri dengan <br><span style="background: linear-gradient(to right, #06b6d4, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Kekuatan Data Presisi.</span>
                    </h2>
                    <p style="color: #94a3b8; font-size: 16px; max-width: 500px; line-height: 1.6; margin-bottom: 30px;">
                        XOSID Supradesa menganalisis data kependudukan secara real-time untuk memberikan rekomendasi kebijakan pembangunan desa yang tepat sasaran.
                    </p>
                    <div>
                        <a href="<?= site_url('katadesa_ai') ?>" class="btn-xosid-glow" style="background: #06b6d4; color: white; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; box-shadow: 0 10px 20px rgba(6, 182, 212, 0.3);">
                            Buka KATADESA AI <i data-lucide="arrow-right" style="width: 18px;"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="neural-card" style="background: rgba(30, 41, 59, 0.7); border-radius: 25px; border: 1px solid rgba(255,255,255,0.05); padding: 30px; height: 100%;">
                    <h4 style="color: white; font-weight: 700; margin-bottom: 20px;">Aktivitas Terkini</h4>
                    <div class="timeline-neural">
                        <!-- Placeholder for activities -->
                        <div style="padding-left: 20px; border-left: 2px solid rgba(6, 182, 212, 0.3); padding-bottom: 20px; position: relative;">
                            <div style="position: absolute; left: -7px; top: 0; width: 12px; height: 12px; background: #06b6d4; border-radius: 50%; box-shadow: 0 0 10px #06b6d4;"></div>
                            <p style="color: white; font-size: 13px; font-weight: 600; margin: 0;">Migrasi Core Premium Berhasil</p>
                            <span style="color: #64748b; font-size: 11px;">Baru saja</span>
                        </div>
                        <div style="padding-left: 20px; border-left: 2px solid rgba(255, 255, 255, 0.05); padding-bottom: 20px; position: relative;">
                            <div style="position: absolute; left: -7px; top: 0; width: 12px; height: 12px; background: #1e293b; border: 2px solid #64748b; border-radius: 50%;"></div>
                            <p style="color: #94a3b8; font-size: 13px; font-weight: 500; margin: 0;">Modernisasi DTSEN Selesai</p>
                            <span style="color: #64748b; font-size: 11px;">10 menit yang lalu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .neural-card:hover {
        transform: translateY(-5px);
        background: rgba(30, 41, 59, 0.9) !important;
        border-color: rgba(6, 182, 212, 0.3) !important;
    }
    .btn-xosid-glow:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 30px rgba(6, 182, 212, 0.5) !important;
    }
</style>

<script>
    // Initialize icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
