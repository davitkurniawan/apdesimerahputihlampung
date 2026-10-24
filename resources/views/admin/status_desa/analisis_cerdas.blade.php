@extends('admin.layouts.index')

@section('title', 'XO Intelligence Hub - Analisis Cerdas Desa')

@section('content')
<?php
// Load intelligence data from global (already computed in autoload intercept)
$intel = $GLOBALS['xosid_intelligence'] ?? xosid_get_intelligence_data();
$demo  = $intel['demografi'] ?? [];
$idm   = $intel['idm'] ?? ['available' => false];
$sdgs  = $intel['sdgs'] ?? ['available' => false];
$masalah = $intel['masalah'] ?? [];
$peluang = $intel['peluang'] ?? [];
$pendanaan = $intel['pendanaan'] ?? [];
$skor  = $intel['skor_desa'] ?? ['nilai' => 0, 'label' => 'N/A'];
$generatedAt = $intel['generated_at'] ?? date('Y-m-d H:i:s');

// Helper function
function formatRp($amount) {
    if ($amount >= 1000000000) return 'Rp ' . number_format($amount/1000000000, 1, ',', '.') . ' M';
    if ($amount >= 1000000) return 'Rp ' . number_format($amount/1000000, 1, ',', '.') . ' Jt';
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

$severityColor = [
    'Kritis' => ['bg' => 'rgba(239,68,68,0.15)', 'border' => '#ef4444', 'badge' => '#ef4444', 'text' => '#fca5a5', 'icon' => 'alert-octagon'],
    'Tinggi' => ['bg' => 'rgba(245,158,11,0.15)', 'border' => '#f59e0b', 'badge' => '#f59e0b', 'text' => '#fcd34d', 'icon' => 'alert-triangle'],
    'Sedang' => ['bg' => 'rgba(59,130,246,0.15)', 'border' => '#3b82f6', 'badge' => '#3b82f6', 'text' => '#93c5fd', 'icon' => 'info'],
];

$kritisCount = count(array_filter($masalah, fn($m) => $m['severity'] === 'Kritis'));
$tinggiCount = count(array_filter($masalah, fn($m) => $m['severity'] === 'Tinggi'));
$sedangCount = count(array_filter($masalah, fn($m) => $m['severity'] === 'Sedang'));

$fundingColors = [
    'Dana Desa (DD)' => ['#8b5cf6', '#a78bfa'],
    'APBD Kabupaten' => ['#3b82f6', '#60a5fa'],
    'APBD Provinsi'  => ['#06b6d4', '#22d3ee'],
    'APBN'           => ['#10b981', '#34d399'],
    'CSR Perusahaan' => ['#f59e0b', '#fbbf24'],
    'Investasi Swasta' => ['#ec4899', '#f472b6'],
    'Swadaya Masyarakat' => ['#64748b', '#94a3b8'],
];
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');

.intel-hub { font-family: 'Outfit', sans-serif; }

/* ── Hero Header ── */
.intel-hero {
    background: linear-gradient(135deg, #0f0c29 0%, #1a0533 30%, #0d1b2a 70%, #0a0f1e 100%);
    border-radius: 24px;
    padding: 40px;
    margin-bottom: 32px;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(139,92,246,0.2);
}
.intel-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -20%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(139,92,246,0.15) 0%, transparent 60%);
    animation: heroGlow 4s ease-in-out infinite;
}
.intel-hero::after {
    content: '';
    position: absolute;
    bottom: -30%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(236,72,153,0.1) 0%, transparent 60%);
    animation: heroGlow 4s ease-in-out infinite reverse;
}
@keyframes heroGlow {
    0%, 100% { transform: scale(1); opacity: 0.7; }
    50% { transform: scale(1.1); opacity: 1; }
}

/* ── Stat Cards ── */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
}
.stat-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 16px;
    padding: 20px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}
.stat-card:hover {
    background: rgba(255,255,255,0.06);
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.3);
}
.stat-card .stat-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 12px;
    font-size: 22px;
}
.stat-card .stat-value {
    font-size: 28px;
    font-weight: 800;
    color: white;
    line-height: 1;
    margin-bottom: 4px;
}
.stat-card .stat-label {
    font-size: 11px;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 1px;
}
.stat-card .stat-sub {
    font-size: 11px;
    color: #475569;
    margin-top: 6px;
}

/* ── Section Titles ── */
.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}
.section-title h3 {
    color: white;
    font-weight: 800;
    font-size: 18px;
    margin: 0;
    font-family: 'Outfit';
}
.section-title .section-badge {
    background: rgba(139,92,246,0.2);
    border: 1px solid rgba(139,92,246,0.3);
    color: #a78bfa;
    font-size: 10px;
    padding: 3px 10px;
    border-radius: 20px;
    font-weight: 700;
    letter-spacing: 1px;
}

/* ── Issue Cards ── */
.issue-card {
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 16px;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}
.issue-card:hover {
    transform: translateX(4px);
}
.issue-card .issue-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 12px;
    gap: 12px;
}
.issue-card .severity-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1px;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 5px;
}
.issue-card .issue-title {
    font-size: 16px;
    font-weight: 700;
    color: white;
    margin: 0;
}
.issue-card .issue-desc {
    color: #94a3b8;
    font-size: 13px;
    line-height: 1.6;
    margin: 8px 0 16px;
}
.issue-kegiatan {
    list-style: none;
    padding: 0;
    margin: 0 0 16px;
}
.issue-kegiatan li {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    color: #94a3b8;
    font-size: 12px;
    padding: 4px 0;
}
.issue-kegiatan li::before {
    content: '▸';
    color: #8b5cf6;
    flex-shrink: 0;
    margin-top: 1px;
}
.issue-metrics {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid rgba(255,255,255,0.06);
}
.issue-metric-item {
    text-align: center;
}
.issue-metric-item .metric-value {
    font-size: 14px;
    font-weight: 800;
    color: white;
}
.issue-metric-item .metric-label {
    font-size: 10px;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 700;
    margin-top: 2px;
}

/* ── Opportunity Cards ── */
.opportunity-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}
.opp-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 20px;
    padding: 24px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.opp-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #8b5cf6, #ec4899);
    border-radius: 20px 20px 0 0;
}
.opp-card:hover {
    background: rgba(255,255,255,0.06);
    transform: translateY(-4px);
    box-shadow: 0 20px 60px rgba(0,0,0,0.4);
    border-color: rgba(139,92,246,0.2);
}
.opp-card .opp-emoji {
    font-size: 36px;
    margin-bottom: 12px;
    display: block;
}
.opp-card .opp-title {
    font-size: 16px;
    font-weight: 700;
    color: white;
    margin-bottom: 6px;
}
.opp-card .opp-desc {
    font-size: 12px;
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 16px;
}
.opp-card .opp-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.opp-meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.opp-meta-label { font-size: 10px; color: #475569; font-weight: 700; text-transform: uppercase; }
.opp-meta-value { font-size: 12px; color: #a78bfa; font-weight: 700; }
.potensi-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1px;
    margin-bottom: 12px;
}
.opp-list {
    list-style: none;
    padding: 0;
    margin: 12px 0;
}
.opp-list li {
    font-size: 11px;
    color: #64748b;
    padding: 3px 0;
    display: flex;
    align-items: flex-start;
    gap: 6px;
}
.opp-list li::before {
    content: '✓';
    color: #10b981;
    font-weight: 900;
    flex-shrink: 0;
}

/* ── Funding Bar ── */
.funding-bar-container {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 20px;
    padding: 28px;
    margin-bottom: 32px;
}
.funding-bar {
    display: flex;
    border-radius: 12px;
    overflow: hidden;
    height: 20px;
    margin-bottom: 20px;
}
.funding-bar-segment {
    transition: all 0.5s ease;
    cursor: pointer;
    position: relative;
}
.funding-bar-segment:hover {
    filter: brightness(1.3);
}
.funding-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}
.funding-legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
}
.funding-legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 3px;
    flex-shrink: 0;
}
.funding-legend-label {
    font-size: 12px;
    color: #94a3b8;
}
.funding-legend-pct {
    font-size: 12px;
    color: white;
    font-weight: 700;
    margin-left: 4px;
}

/* ── Score Ring ── */
.score-ring-wrapper {
    display: flex;
    align-items: center;
    gap: 20px;
}
.score-ring {
    width: 80px;
    height: 80px;
    flex-shrink: 0;
}
.score-number {
    font-size: 42px;
    font-weight: 900;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
}
.score-label {
    font-size: 14px;
    color: #94a3b8;
    font-weight: 600;
    margin-top: 2px;
}

/* ── Recalculate button ── */
.recalc-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
    color: white;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 13px;
    letter-spacing: 1px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    box-shadow: 0 8px 30px rgba(139,92,246,0.4);
}
.recalc-btn:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(139,92,246,0.6);
    text-decoration: none;
}
.recalc-btn i { width: 18px; }

/* ── Desa Info Banner ── */
.info-banner {
    background: rgba(139,92,246,0.08);
    border: 1px solid rgba(139,92,246,0.2);
    border-radius: 12px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    font-size: 12px;
    color: #94a3b8;
}
.info-banner i { color: #8b5cf6; width: 16px; flex-shrink: 0; }

/* ── Table Rekomendasi ── */
.rekom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
}
.rekom-table th {
    color: #475569;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 8px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.rekom-table td {
    padding: 14px 16px;
    vertical-align: middle;
    background: rgba(255,255,255,0.02);
    border-top: 1px solid rgba(255,255,255,0.03);
    border-bottom: 1px solid rgba(255,255,255,0.03);
    font-size: 13px;
    color: #94a3b8;
}
.rekom-table tr td:first-child {
    border-left: 1px solid rgba(255,255,255,0.03);
    border-radius: 8px 0 0 8px;
}
.rekom-table tr td:last-child {
    border-right: 1px solid rgba(255,255,255,0.03);
    border-radius: 0 8px 8px 0;
}
.rekom-table tr:hover td {
    background: rgba(255,255,255,0.04);
}

/* ── Chart bars ── */
.mini-chart {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.mini-bar-row {
    display: flex;
    align-items: center;
    gap: 10px;
}
.mini-bar-label {
    font-size: 11px;
    color: #64748b;
    width: 100px;
    flex-shrink: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.mini-bar-track {
    flex: 1;
    height: 8px;
    background: rgba(255,255,255,0.06);
    border-radius: 4px;
    overflow: hidden;
}
.mini-bar-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 1s ease;
}
.mini-bar-value {
    font-size: 11px;
    color: white;
    font-weight: 700;
    width: 40px;
    text-align: right;
    flex-shrink: 0;
}

/* ── Demografi grid 2-column ── */
.demo-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 32px;
}
@media (max-width: 768px) {
    .demo-grid-2 { grid-template-columns: 1fr; }
    .stat-grid { grid-template-columns: repeat(2, 1fr); }
    .opportunity-grid { grid-template-columns: 1fr; }
}

/* ── Pulse dot (live indicator) ── */
.live-dot {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: #10b981;
    font-weight: 600;
}
.live-dot-circle {
    width: 8px; height: 8px;
    background: #10b981;
    border-radius: 50%;
    animation: pulseDot 2s ease-in-out infinite;
}
@keyframes pulseDot {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.7); }
    50% { box-shadow: 0 0 0 6px rgba(16,185,129,0); }
}

/* ── Progress Ring SVG ── */
.prog-ring-svg { transform: rotate(-90deg); }

/* Uploader Deck */
.upload-deck {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 32px;
}
.dropzone {
    background: rgba(255, 255, 255, 0.02);
    border: 2px dashed rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 24px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 140px;
}
.dropzone:hover, .dropzone.dragover {
    border-color: #8b5cf6;
    background: rgba(139, 92, 246, 0.05);
}
.dropzone .title { font-size: 14px; font-weight: 700; color: white; }
.dropzone .subtitle { font-size: 11px; color: #64748b; margin-top: 6px; }
.upload-spinner {
    width: 24px;
    height: 24px;
    border: 3px solid rgba(255,255,255,0.1);
    border-top-color: #8b5cf6;
    border-radius: 50%;
    animation: uploadSpin 0.8s linear infinite;
    margin: 0 auto 8px;
}
@keyframes uploadSpin { to { transform: rotate(360deg); } }
.upload-success-icon { font-size: 28px; color: #10b981; margin-bottom: 8px; display: inline-block; }
.file-badge {
    background: rgba(139, 92, 246, 0.15);
    border: 1px solid rgba(139, 92, 246, 0.3);
    color: #a78bfa;
    font-size: 11px;
    padding: 3px 10px;
    border-radius: 6px;
    margin-top: 8px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Dimension Grid */
.dimension-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 32px;
}
@media (max-width: 992px) { .dimension-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 576px) { .dimension-grid { grid-template-columns: 1fr; } }
.dimension-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.07);
    border-radius: 16px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: all 0.3s ease;
}
.dimension-card:hover {
    background: rgba(255, 255, 255, 0.06);
    transform: translateY(-2px);
}
.dimension-name { font-size: 13px; font-weight: 700; color: #94a3b8; }

@media print {
    body, html { background: #ffffff !important; color: #000000 !important; }
    #sidebar-wrapper, .main-header, .sidebar, .nav-tabs, .navigation-cards, .upload-deck, .recalc-btn, .action-bar, .no-print, header, footer { display: none !important; }
    #page-content-wrapper, .content-wrapper, .main-content, .intel-hub { padding: 0 !important; margin: 0 !important; width: 100% !important; background: #ffffff !important; }
    .neural-card, .opp-card, .issue-card, .stat-card, .intel-hero {
        background: #ffffff !important; border: 1px solid #cbd5e1 !important; box-shadow: none !important; color: #0f172a !important; page-break-inside: avoid; border-radius: 12px !important; margin-bottom: 14px !important; padding: 16px !important;
    }
    .opp-card::before { display: none !important; }
    h1, h2, h3, h4, h5, h6, .issue-title, .opp-title, .stat-card .stat-value, .score-number { color: #0f172a !important; }
    p, .issue-desc, .opp-desc, .mini-bar-label, .funding-legend-label { color: #475569 !important; }
    .mini-bar-track, .funding-bar { background: #f1f5f9 !important; border: 1px solid #cbd5e1 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .stat-grid, .dimension-grid { display: grid !important; grid-template-columns: repeat(2, 1fr) !important; gap: 10px !important; }
    .opp-grid { display: grid !important; grid-template-columns: repeat(2, 1fr) !important; gap: 10px !important; }
    .section-title { page-break-after: avoid; page-break-inside: avoid; margin-top: 20px !important; color: #0f172a !important; }
    .section-title h3 { color: #0f172a !important; }
}

    /* LIGHT THEME EXCLUSIVE OVERRIDES FOR ANALISIS CERDAS */
    body.light-theme .intel-hub {
        color: #0f172a;
    }
    
    body.light-theme .intel-hero {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
        border: 1px solid rgba(0,0,0,0.08) !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03) !important;
    }
    body.light-theme .intel-hero h1 { color: #0f172a !important; }
    body.light-theme .intel-hero p, body.light-theme .info-banner { color: #475569 !important; }
    body.light-theme .info-banner { background: #f1f5f9 !important; border-color: rgba(0,0,0,0.05) !important; }
    body.light-theme .intel-hero > div > div > div > span { color: #64748b !important; }
    
    body.light-theme .stat-card,
    body.light-theme .issue-card,
    body.light-theme .opp-card,
    body.light-theme .funding-bar-container,
    body.light-theme .dimension-card {
        background: #ffffff !important;
        border: 1px solid rgba(0,0,0,0.08) !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02) !important;
    }
    
    body.light-theme .stat-card:hover,
    body.light-theme .issue-card:hover,
    body.light-theme .opp-card:hover,
    body.light-theme .dimension-card:hover {
        background: #f8fafc !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important;
        border-color: #8b5cf6 !important;
    }
    
    body.light-theme .stat-card .stat-value,
    body.light-theme .section-title h3,
    body.light-theme .issue-card .issue-title,
    body.light-theme .opp-card .opp-title,
    body.light-theme .dimension-name,
    body.light-theme .score-label,
    body.light-theme .funding-legend-pct,
    body.light-theme .mini-bar-value,
    body.light-theme .issue-metric-item .metric-value,
    body.light-theme .dropzone .title {
        color: #0f172a !important;
    }
    
    body.light-theme .stat-card .stat-label,
    body.light-theme .stat-card .stat-sub,
    body.light-theme .issue-card .issue-desc,
    body.light-theme .opp-card .opp-desc,
    body.light-theme .funding-legend-label,
    body.light-theme .mini-bar-label,
    body.light-theme .issue-metric-item .metric-label,
    body.light-theme .dropzone .subtitle {
        color: #475569 !important;
    }
    
    body.light-theme .issue-kegiatan li,
    body.light-theme .opp-list li {
        color: #64748b !important;
    }
    
    body.light-theme .mini-bar-track,
    body.light-theme .funding-bar {
        background: #f1f5f9 !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    
    body.light-theme .rekom-table td {
        background: #ffffff !important;
        border-color: rgba(0,0,0,0.05) !important;
        color: #475569 !important;
    }
    
    body.light-theme .rekom-table td[style*="color: white"],
    body.light-theme .rekom-table td div[style*="color: white"] {
        color: #0f172a !important;
    }
    body.light-theme .rekom-table td div[style*="color: #475569"],
    body.light-theme .rekom-table td[style*="color: #64748b"] {
        color: #475569 !important;
    }

    body.light-theme .rekom-table tr:hover td {
        background: #f8fafc !important;
    }
    body.light-theme .rekom-table th {
        background: #f1f5f9 !important;
        color: #0f172a !important;
        border-bottom-color: rgba(0,0,0,0.1) !important;
    }
    
    body.light-theme .dropzone {
        background: #ffffff !important;
        border-color: rgba(0,0,0,0.1) !important;
    }
    body.light-theme .dropzone:hover {
        background: #f8fafc !important;
        border-color: #8b5cf6 !important;
    }
    body.light-theme .intel-hero > div:nth-child(1) > div:nth-child(2) {
        background: #ffffff !important;
        border-color: rgba(0,0,0,0.05) !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03) !important;
    }
</style>


<div class="intel-hub">

    {{-- NAVIGATION --}}
    @include('admin.status_desa.navigasi')

    <?php 
    $uploadedId = $idm['uploaded_file'] ?? null;
    $uploadedProdeskel = $intel['prodeskel_file'] ?? null;
    ?>
    {{-- ================================================================ --}}
    {{-- UPLOADER DECK --}}
    {{-- ================================================================ --}}
    <div class="upload-deck no-print">
        <div class="dropzone" id="prodeskel-dropzone">
            <span style="font-size:26px;margin-bottom:6px;">📂</span>
            <div class="title" style="margin-bottom: 4px;">Data Prodeskel</div>
            <input type="file" id="prodeskel-input" accept=".zip,.xls,.xlsx,.pdf" style="display:none;">
            <?php if ($uploadedProdeskel): ?>
                <div style="font-size: 11px; color: #10b981; margin-top: 8px; text-align: left; background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.2); border-radius: 8px; padding: 10px; width: 100%; max-width: 280px; margin-left: auto; margin-right: auto;">
                    <div style="font-weight: 700; color: white; word-break: break-all; margin-bottom: 4px;">📄 <?= htmlspecialchars($uploadedProdeskel['name']) ?></div>
                    <div style="color: #94a3b8; font-size: 10px; display: flex; justify-content: space-between;">
                        <span>Status Upload:</span> <span style="color: #10b981; font-weight: 700;">Berhasil</span>
                    </div>
                    <div style="color: #94a3b8; font-size: 10px; display: flex; justify-content: space-between; margin-top: 2px;">
                        <span>Terakhir Upload:</span> <span><?= date('d/m/Y, H:i:s', strtotime($uploadedProdeskel['uploaded_at'])) ?></span>
                    </div>
                </div>
                <div class="subtitle" style="font-size: 9px; margin-top: 6px;">Seret file baru untuk mengganti</div>
            <?php else: ?>
                <div class="subtitle">Klik atau seret berkas (ZIP, XLS, XLSX, PDF)</div>
            <?php endif; ?>
        </div>
        <div class="dropzone" id="id-dropzone">
            <span style="font-size:26px;margin-bottom:6px;">📈</span>
            <div class="title" style="margin-bottom: 4px;">Data Indeks Desa (ID) 2025</div>
            <input type="file" id="id-input" accept=".zip,.xls,.xlsx,.pdf" style="display:none;">
            <?php if ($uploadedId): ?>
                <div style="font-size: 11px; color: #10b981; margin-top: 8px; text-align: left; background: rgba(16,185,129,0.06); border: 1px solid rgba(16,185,129,0.2); border-radius: 8px; padding: 10px; width: 100%; max-width: 280px; margin-left: auto; margin-right: auto;">
                    <div style="font-weight: 700; color: white; word-break: break-all; margin-bottom: 4px;">📄 <?= htmlspecialchars($uploadedId['name']) ?></div>
                    <div style="color: #94a3b8; font-size: 10px; display: flex; justify-content: space-between;">
                        <span>Status Upload:</span> <span style="color: #10b981; font-weight: 700;">Berhasil</span>
                    </div>
                    <div style="color: #94a3b8; font-size: 10px; display: flex; justify-content: space-between; margin-top: 2px;">
                        <span>Terakhir Upload:</span> <span><?= date('d/m/Y, H:i:s', strtotime($uploadedId['uploaded_at'])) ?></span>
                    </div>
                </div>
                <div class="subtitle" style="font-size: 9px; margin-top: 6px;">Seret file baru untuk mengganti</div>
            <?php else: ?>
                <div class="subtitle">Klik atau seret berkas (ZIP, XLS, XLSX, PDF)</div>
            <?php endif; ?>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- HERO HEADER --}}
    {{-- ================================================================ --}}
    <div class="intel-hero">
        <div style="position: relative; z-index: 2;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <div style="width: 42px; height: 42px; background: linear-gradient(135deg, #8b5cf6, #ec4899); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="brain" style="width: 22px; color: white;"></i>
                        </div>
                        <div>
                            <div style="font-size: 11px; color: #8b5cf6; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;">XO Intelligence Hub</div>
                            <h1 style="color: white; font-family: 'Outfit'; font-weight: 900; font-size: 26px; margin: 0; line-height: 1;">Pusat Analisis Cerdas Desa</h1>
                        </div>
                    </div>
                    <p style="color: #64748b; font-size: 13px; margin: 0; max-width: 600px;">
                        Sintesis data demografis, IDM, dan SDGs menjadi wawasan strategis berbasis AI untuk pengambilan keputusan pemerintah desa yang tepat sasaran.
                    </p>
                    <div style="display: flex; align-items: center; gap: 16px; margin-top: 16px; flex-wrap: wrap;">
                        <div class="live-dot">
                            <div class="live-dot-circle"></div>
                            Data Real-time
                        </div>
                        <span style="color: #334155; font-size: 12px;">|</span>
                        <span style="color: #475569; font-size: 12px;">
                            <i data-lucide="clock" style="width: 12px; display: inline-block; vertical-align: middle;"></i>
                            Diperbarui: {{ date('d M Y H:i', strtotime($generatedAt)) }}
                        </span>
                        <span style="color: #334155; font-size: 12px;">|</span>
                        <span style="color: #475569; font-size: 12px;">
                            <i data-lucide="database" style="width: 12px; display: inline-block; vertical-align: middle;"></i>
                            {{ number_format($demo['total_penduduk'] ?? 0) }} Jiwa Teranalisis
                        </span>
                    </div>
                </div>

                {{-- Village Score --}}
                <div style="text-align: center; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 20px 28px;">
                    <div style="font-size: 10px; color: #64748b; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;">SKOR DESA XOSID</div>
                    <?php
                    $skorVal  = $skor['nilai'] ?? 0;
                    $skorLabel = $skor['label'] ?? 'N/A';
                    $circumference = 2 * M_PI * 38;
                    $offset = $circumference - ($skorVal / 100) * $circumference;
                    $scoreBg = '#8b5cf6';
                    if ($skorVal >= 75) $scoreBg = '#10b981';
                    if ($skorVal < 50) $scoreBg = '#f59e0b';
                    if ($skorVal < 35) $scoreBg = '#ef4444';
                    ?>
                    <div class="score-ring-wrapper" style="justify-content: center;">
                        <div>
                            <div class="score-number"><?= number_format($skorVal, 0) ?></div>
                            <div class="score-label"><?= htmlspecialchars($skorLabel) ?></div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 12px; margin-top: 12px; justify-content: center;">
                        <?php if (($skor['isu_kritis'] ?? 0) > 0): ?>
                        <span style="background: rgba(239,68,68,0.2); border: 1px solid #ef4444; color: #fca5a5; font-size: 10px; padding: 2px 8px; border-radius: 20px; font-weight: 700;">
                            <?= $skor['isu_kritis'] ?> Kritis
                        </span>
                        <?php endif; ?>
                        <?php if (($skor['isu_tinggi'] ?? 0) > 0): ?>
                        <span style="background: rgba(245,158,11,0.2); border: 1px solid #f59e0b; color: #fcd34d; font-size: 10px; padding: 2px 8px; border-radius: 20px; font-weight: 700;">
                            <?= $skor['isu_tinggi'] ?> Tinggi
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            {{-- Action Bar --}}
            <div style="margin-top: 24px; display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <a href="#" id="btn-recalculate-trigger" class="recalc-btn">
                    <i data-lucide="refresh-cw" style="width:16px;"></i>
                    RE-CALCULATE ANALISIS
                </a>
                <button onclick="window.print();" class="recalc-btn" style="background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%); border: none;">
                    <i data-lucide="printer" style="width:16px;"></i>
                    CETAK LAPORAN PDF
                </button>
                <span style="font-size: 12px; color: #475569;" class="no-print">
                    Klik cetak untuk menyimpan laporan eksekutif PDF
                </span>
            </div>
        </div>
    </div>

    {{-- PROGRESS BAR OVERLAY --}}
    <div id="progress-bar-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15, 12, 41, 0.85); backdrop-filter:blur(8px); z-index:9999; align-items:center; justify-content:center; flex-direction:column;">
        <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:24px; padding:40px; width:90%; max-width:500px; text-align:center; box-shadow:0 20px 50px rgba(0,0,0,0.5);">
            <div style="font-size:40px; margin-bottom:20px; animation: spin 2s linear infinite; display:inline-block;" id="progress-spinner">🔄</div>
            <h4 style="color:white; font-family:'Outfit'; font-weight:800; font-size:18px; margin-bottom:10px; margin-top: 0;">Mengalkulasi Analisis Cerdas</h4>
            <p id="progress-message" style="color:#94a3b8; font-size:13px; margin-bottom:24px; min-height:40px;">Menghubungi server...</p>
            
            <div style="width:100%; background:rgba(255,255,255,0.05); height:12px; border-radius:6px; overflow:hidden; margin-bottom:12px; border:1px solid rgba(255,255,255,0.05);">
                <div id="progress-fill" style="width:0%; height:100%; background:linear-gradient(90deg, #8b5cf6 0%, #06b6d4 100%); transition:width 0.4s cubic-bezier(0.4, 0, 0.2, 1);"></div>
            </div>
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:11px; color:#475569; font-weight:700; text-transform:uppercase; letter-spacing:1px;">Progress Kalkulasi</span>
                <span id="progress-percent" style="font-size:14px; color:#06b6d4; font-weight:900;">0%</span>
            </div>
        </div>
    </div>

    {{-- Add custom inline keyframe spin style --}}
    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    
    /* LIGHT THEME EXCLUSIVE OVERRIDES FOR ANALISIS CERDAS */
    body.light-theme .intel-hub {
        color: #0f172a;
    }
    
    body.light-theme .intel-hero {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
        border: 1px solid rgba(0,0,0,0.08) !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03) !important;
    }
    body.light-theme .intel-hero h1 { color: #0f172a !important; }
    body.light-theme .intel-hero p, body.light-theme .info-banner { color: #475569 !important; }
    body.light-theme .info-banner { background: #f1f5f9 !important; border-color: rgba(0,0,0,0.05) !important; }
    body.light-theme .intel-hero > div > div > div > span { color: #64748b !important; }
    
    body.light-theme .stat-card,
    body.light-theme .issue-card,
    body.light-theme .opp-card,
    body.light-theme .funding-bar-container,
    body.light-theme .dimension-card {
        background: #ffffff !important;
        border: 1px solid rgba(0,0,0,0.08) !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02) !important;
    }
    
    body.light-theme .stat-card:hover,
    body.light-theme .issue-card:hover,
    body.light-theme .opp-card:hover,
    body.light-theme .dimension-card:hover {
        background: #f8fafc !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important;
        border-color: #8b5cf6 !important;
    }
    
    body.light-theme .stat-card .stat-value,
    body.light-theme .section-title h3,
    body.light-theme .issue-card .issue-title,
    body.light-theme .opp-card .opp-title,
    body.light-theme .dimension-name,
    body.light-theme .score-label,
    body.light-theme .funding-legend-pct,
    body.light-theme .mini-bar-value,
    body.light-theme .issue-metric-item .metric-value,
    body.light-theme .dropzone .title {
        color: #0f172a !important;
    }
    
    body.light-theme .stat-card .stat-label,
    body.light-theme .stat-card .stat-sub,
    body.light-theme .issue-card .issue-desc,
    body.light-theme .opp-card .opp-desc,
    body.light-theme .funding-legend-label,
    body.light-theme .mini-bar-label,
    body.light-theme .issue-metric-item .metric-label,
    body.light-theme .dropzone .subtitle {
        color: #475569 !important;
    }
    
    body.light-theme .issue-kegiatan li,
    body.light-theme .opp-list li {
        color: #64748b !important;
    }
    
    body.light-theme .mini-bar-track,
    body.light-theme .funding-bar {
        background: #f1f5f9 !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    
    body.light-theme .rekom-table td {
        background: #ffffff !important;
        border-color: rgba(0,0,0,0.05) !important;
        color: #475569 !important;
    }
    
    body.light-theme .rekom-table td[style*="color: white"],
    body.light-theme .rekom-table td div[style*="color: white"] {
        color: #0f172a !important;
    }
    body.light-theme .rekom-table td div[style*="color: #475569"],
    body.light-theme .rekom-table td[style*="color: #64748b"] {
        color: #475569 !important;
    }

    body.light-theme .rekom-table tr:hover td {
        background: #f8fafc !important;
    }
    body.light-theme .rekom-table th {
        background: #f1f5f9 !important;
        color: #0f172a !important;
        border-bottom-color: rgba(0,0,0,0.1) !important;
    }
    
    body.light-theme .dropzone {
        background: #ffffff !important;
        border-color: rgba(0,0,0,0.1) !important;
    }
    body.light-theme .dropzone:hover {
        background: #f8fafc !important;
        border-color: #8b5cf6 !important;
    }
    body.light-theme .intel-hero > div:nth-child(1) > div:nth-child(2) {
        background: #ffffff !important;
        border-color: rgba(0,0,0,0.05) !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03) !important;
    }
</style>


    <?php
    $history_files = [];
    $history_dir = defined('FCPATH') ? FCPATH . 'desa/upload/analisis_cerdas/history/' : '/var/www/html/desa/upload/analisis_cerdas/history/';
    if (is_dir($history_dir)) {
        $files = glob($history_dir . 'analisis_desa_*.pdf');
        if (!empty($files)) {
            usort($files, function($a, $b) {
                return filemtime($b) <=> filemtime($a);
            });
            foreach ($files as $f) {
                $fn = basename($f);
                if (preg_match('/^analisis_desa_(\d{4})(\d{2})(\d{2})_(\d{2})(\d{2})(\d{2})\.pdf$/', $fn, $m)) {
                    $history_files[] = [
                        'filename' => $fn,
                        'date' => "{$m[3]}/{$m[2]}/{$m[1]}",
                        'time' => "{$m[4]}:{$m[5]}:{$m[6]}",
                        'timestamp' => filemtime($f)
                    ];
                }
            }
        }
    }
    ?>

    {{-- PDF Statement History Panel --}}
    <div class="neural-card no-print" style="padding: 24px; margin-bottom: 32px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 16px; margin-top: 32px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; background: rgba(139,92,246,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #a78bfa; border: 1px solid rgba(139,92,246,0.2);">
                    <i data-lucide="folder-heart" style="width: 18px;"></i>
                </div>
                <div>
                    <h4 style="color: white; font-weight: 800; font-size: 15px; margin: 0; font-family: 'Outfit';">Histori Statement Laporan Cerdas</h4>
                    <p style="color: #64748b; font-size: 11px; margin: 0; margin-top: 2px;">Statement PDF terarsip otomatis dari setiap kali rekalkulasi analisis</p>
                </div>
            </div>
            <span style="font-size: 10px; background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.2); color: #34d399; padding: 2px 8px; border-radius: 20px; font-weight: 700; letter-spacing: 1px;">
                <?= count($history_files) ?> DOKUMEN
            </span>
        </div>

        <?php if (!empty($history_files)): ?>
            <div style="max-height: 250px; overflow-y: auto; padding-right: 4px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <th style="text-align: left; padding: 10px 8px; font-size: 11px; color: #475569; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 30%;">Tanggal & Waktu</th>
                            <th style="text-align: left; padding: 10px 8px; font-size: 11px; color: #475569; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 50%;">Nama Dokumen</th>
                            <th style="text-align: right; padding: 10px 8px; font-size: 11px; color: #475569; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($history_files, 0, 10) as $hist): ?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.03); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.01)'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 12px 8px; font-size: 13px; color: #e2e8f0;">
                                    <span style="font-weight: 600;"><?= $hist['date'] ?></span>
                                    <span style="color: #64748b; font-size: 11px; margin-left: 6px;"><?= $hist['time'] ?> WIB</span>
                                </td>
                                <td style="padding: 12px 8px; font-size: 12px; color: #94a3b8; font-family: monospace;">
                                    <?= htmlspecialchars($hist['filename']) ?>
                                </td>
                                <td style="padding: 12px 8px; text-align: right;">
                                    <a href="/index.php/status_desa/download_pdf_history?file=<?= urlencode($hist['filename']) ?>" class="btn-xosid" style="padding: 4px 12px; border-radius: 8px; border: 1px solid #10b981; background: rgba(16,185,129,0.05); color: #34d399; font-size: 11px; text-decoration: none; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;" onmouseover="this.style.background='#10b981'; this.style.color='white'" onmouseout="this.style.background='rgba(16,185,129,0.05)'; this.style.color='#34d399'">
                                        <i data-lucide="download" style="width: 12px;"></i> Unduh PDF
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if (count($history_files) > 10): ?>
                    <div style="text-align: center; margin-top: 12px; font-size: 11px; color: #475569;">
                        Menampilkan 10 dokumen terbaru. Total arsip: <?= count($history_files) ?> dokumen.
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 30px; background: rgba(255,255,255,0.01); border-radius: 12px; border: 1px dashed rgba(255,255,255,0.05);">
                <span style="font-size: 24px; display: block; margin-bottom: 8px;">📁</span>
                <span style="font-size: 12px; color: #475569; font-weight: 600;">Belum ada dokumen statement PDF terarsip.</span>
                <p style="font-size: 11px; color: #334155; margin: 4px 0 0 0;">Lakukan rekalkulasi analisis di atas untuk secara otomatis mengarsipkan statement laporan pertamamu.</p>
            </div>
        <?php endif; ?>
    </div>

    {{-- ================================================================ --}}
    {{-- DEMOGRAPHIC SNAPSHOT CARDS --}}
    {{-- ================================================================ --}}
    <div class="section-title">
        <div style="width: 36px; height: 36px; background: rgba(6,182,212,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #06b6d4; border: 1px solid rgba(6,182,212,0.2);">
            <i data-lucide="users" style="width: 18px;"></i>
        </div>
        <h3>Demografi Penduduk</h3>
        <span class="section-badge">REAL-TIME DATABASE</span>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">👥</div>
            <div class="stat-value"><?= number_format($demo['total_penduduk'] ?? 0) ?></div>
            <div class="stat-label">Total Penduduk</div>
            <div class="stat-sub">
                ♂ <?= number_format($demo['laki_laki'] ?? 0) ?> &nbsp;
                ♀ <?= number_format($demo['perempuan'] ?? 0) ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">🏠</div>
            <div class="stat-value"><?= number_format($demo['total_kk'] ?? 0) ?></div>
            <div class="stat-label">Total KK</div>
            <div class="stat-sub">Rata-rata <?= $demo['total_kk'] > 0 ? number_format(($demo['total_penduduk'] ?? 0) / $demo['total_kk'], 1) : 0 ?> jiwa/KK</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(6,182,212,0.1); color: #06b6d4;">💼</div>
            <div class="stat-value"><?= number_format($demo['usia_produktif'] ?? 0) ?></div>
            <div class="stat-label">Usia Produktif</div>
            <div class="stat-sub">Usia 15-64 tahun</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">⚠️</div>
            <div class="stat-value"><?= number_format($demo['pengangguran'] ?? 0) ?></div>
            <div class="stat-label">Pengangguran</div>
            <div class="stat-sub" style="color: <?= ($demo['tingkat_pengangguran'] ?? 0) > 10 ? '#f87171' : '#64748b'; ?>">
                <?= $demo['tingkat_pengangguran'] ?? 0 ?>% angkatan kerja
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(236,72,153,0.1); color: #ec4899;">👶</div>
            <div class="stat-value"><?= number_format($demo['balita'] ?? 0) ?></div>
            <div class="stat-label">Balita (0-4 th)</div>
            <div class="stat-sub">Risiko stunting perlu dipantau</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(99,102,241,0.1); color: #6366f1;">👴</div>
            <div class="stat-value"><?= number_format($demo['lansia'] ?? 0) ?></div>
            <div class="stat-label">Lansia (65+ th)</div>
            <div class="stat-sub">Perlu perlindungan sosial</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">♿</div>
            <div class="stat-value"><?= number_format($demo['disabilitas'] ?? 0) ?></div>
            <div class="stat-label">Disabilitas</div>
            <div class="stat-sub">Kelompok rentan prioritas</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(20,184,166,0.1); color: #14b8a6;">🏥</div>
            <div class="stat-value"><?= number_format($demo['sakit_menahun'] ?? 0) ?></div>
            <div class="stat-label">Penyakit Menahun</div>
            <div class="stat-sub">Perlu pendampingan kesehatan</div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- 6 DIMENSIONS GRID --}}
    {{-- ================================================================ --}}
    <div class="intel-section-title" style="margin-top: 32px; display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
        <span style="font-size: 20px;">🎯</span>
        <h3 style="color: white; font-weight: 800; font-size: 16px; margin: 0; font-family: 'Outfit';">6 Dimensi Indeks Desa (ID) 2025</h3>
        <span class="intel-section-badge" style="background: rgba(6,182,212,0.15); border-color: rgba(6,182,212,0.3); color: #22d3ee; font-size: 10px; padding: 2px 8px; border-radius: 20px; font-weight: 700; letter-spacing: 1px;">
            <?= !empty($idm['is_id_2025']) ? 'METODE BARU ID 2025' : 'ESTIMASI MODEL CACHED' ?>
        </span>
    </div>
    <div class="dimension-grid">
        <?php
        $dimensi = $idm['dimensi'] ?? [];
        $dimNames = [
            'layanan_dasar' => 'Layanan Dasar',
            'sosial' => 'Sosial',
            'ekonomi' => 'Ekonomi',
            'lingkungan' => 'Lingkungan',
            'aksesibilitas' => 'Aksesibilitas',
            'tata_kelola' => 'Tata Kelola Pemerintahan Desa'
        ];
        $dimIcons = [
            'layanan_dasar' => '🏥',
            'sosial' => '🤝',
            'ekonomi' => '💼',
            'lingkungan' => '🌱',
            'aksesibilitas' => '🚗',
            'tata_kelola' => '🏛️'
        ];
        $dimColors = [
            'layanan_dasar' => '#06b6d4',
            'sosial' => '#10b981',
            'ekonomi' => '#f59e0b',
            'lingkungan' => '#ec4899',
            'aksesibilitas' => '#6366f1',
            'tata_kelola' => '#8b5cf6'
        ];
        foreach ($dimNames as $k => $name):
            $val = $dimensi[$k] ?? 0.0;
            $icon = $dimIcons[$k] ?? '🔹';
            $color = $dimColors[$k] ?? '#64748b';
            $valPct = round($val * 100);
        ?>
        <div class="dimension-card">
            <div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <span style="font-size:20px;"><?= $icon ?></span>
                    <span style="font-size:16px;font-weight:900;color:<?= $color ?>;"><?= number_format($val, 4) ?></span>
                </div>
                <div class="dimension-name"><?= $name ?></div>
            </div>
            <div style="margin-top:10px;">
                <div style="display:flex;justify-content:space-between;font-size:10px;color:#64748b;font-weight:700;margin-bottom:4px;">
                    <span>PEMENUHAN</span>
                    <span><?= $valPct ?>%</span>
                </div>
                <div style="background: rgba(255,255,255,0.05); border-radius: 4px; height: 5px; overflow: hidden;">
                    <div style="width: <?= $valPct ?>%; height: 100%; background: <?= $color ?>; border-radius: 4px;"></div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    {{-- ================================================================ --}}
    {{-- IDM & SDGs Status Row --}}
    {{-- ================================================================ --}}
    <div class="row" style="margin-bottom: 32px;">
        <div class="col-md-6">
            <div class="neural-card" style="padding: 24px; height: 100%;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h4 style="color: white; font-weight: 700; margin: 0; font-family: 'Outfit';">Status Indeks Desa</h4>
                    <?php if ($idm['available']): ?>
                    <span style="background: rgba(6,182,212,0.15); border: 1px solid #06b6d4; color: #06b6d4; font-size: 10px; padding: 3px 10px; border-radius: 20px; font-weight: 700;">
                        TAHUN <?= $idm['tahun'] ?>
                    </span>
                    <?php else: ?>
                    <span style="background: rgba(100,116,139,0.15); border: 1px solid #64748b; color: #64748b; font-size: 10px; padding: 3px 10px; border-radius: 20px; font-weight: 700;">
                        BELUM ADA DATA
                    </span>
                    <?php endif; ?>
                </div>
                <?php if ($idm['available']): 
                    $idmPct = min(100, $idm['skor'] * 100);
                    $idmColor = $idm['skor'] >= 0.8 ? '#10b981' : ($idm['skor'] >= 0.6 ? '#f59e0b' : '#ef4444');
                ?>
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="font-size: 48px; font-weight: 900; color: <?= $idmColor ?>; line-height: 1;">
                        <?= number_format($idm['skor'], 4) ?>
                    </div>
                    <div>
                        <div style="font-size: 20px; font-weight: 800; color: white;"><?= htmlspecialchars($idm['status']) ?></div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 4px;">Status Indeks Desa</div>
                    </div>
                </div>
                <div style="margin-top: 12px; background: rgba(255,255,255,0.05); border-radius: 8px; height: 8px; overflow: hidden;">
                    <div style="width: <?= $idmPct ?>%; height: 100%; background: <?= $idmColor ?>; border-radius: 8px; transition: width 1s ease;"></div>
                </div>
                <?php else: ?>
                <div style="text-align: center; padding: 20px; color: #475569;">
                    <i data-lucide="cloud-off" style="width: 32px; color: #334155; margin-bottom: 8px;"></i>
                    <p style="margin: 0; font-size: 13px;">Data Indeks Desa belum tersedia.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="neural-card" style="padding: 24px; height: 100%;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h4 style="color: white; font-weight: 700; margin: 0; font-family: 'Outfit';">Status SDGs</h4>
                    <?php if ($sdgs['available']): ?>
                    <span style="background: rgba(16,185,129,0.15); border: 1px solid #10b981; color: #10b981; font-size: 10px; padding: 3px 10px; border-radius: 20px; font-weight: 700;">
                        DATA TERSEDIA
                    </span>
                    <?php else: ?>
                    <span style="background: rgba(100,116,139,0.15); border: 1px solid #64748b; color: #64748b; font-size: 10px; padding: 3px 10px; border-radius: 20px; font-weight: 700;">
                        BELUM ADA DATA
                    </span>
                    <?php endif; ?>
                </div>
                <?php if ($sdgs['available']): 
                    $sdgsAvg = $sdgs['average'];
                    $sdgsColor = $sdgsAvg >= 60 ? '#10b981' : ($sdgsAvg >= 40 ? '#f59e0b' : '#ef4444');
                ?>
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="font-size: 48px; font-weight: 900; color: <?= $sdgsColor ?>; line-height: 1;">
                        <?= number_format($sdgsAvg, 1) ?>
                    </div>
                    <div>
                        <div style="font-size: 16px; font-weight: 800; color: white;">Rata-rata SDGs</div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 4px;"><?= count($sdgs['goals']) ?> Goal Terukur</div>
                    </div>
                </div>
                <div style="margin-top: 12px; background: rgba(255,255,255,0.05); border-radius: 8px; height: 8px; overflow: hidden;">
                    <div style="width: <?= min(100, $sdgsAvg) ?>%; height: 100%; background: <?= $sdgsColor ?>; border-radius: 8px;"></div>
                </div>
                <?php else: ?>
                <div style="text-align: center; padding: 20px; color: #475569;">
                    <i data-lucide="cloud-off" style="width: 32px; color: #334155; margin-bottom: 8px;"></i>
                    <p style="margin: 0; font-size: 13px;">Data SDGs belum tersedia.<br>Klik <a href="{{ ci_route('status_desa.perbarui_sdgs') }}" style="color: #8b5cf6;">Perbarui SDGs</a> untuk mengambil dari Kemendesa.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- MASALAH & ISU --}}
    {{-- ================================================================ --}}
    <?php if (!empty($masalah)): ?>
    <div class="section-title">
        <div style="width: 36px; height: 36px; background: rgba(239,68,68,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #ef4444; border: 1px solid rgba(239,68,68,0.2);">
            <i data-lucide="shield-alert" style="width: 18px;"></i>
        </div>
        <h3>Masalah & Isu Desa</h3>
        <span class="section-badge" style="background: rgba(239,68,68,0.15); border-color: rgba(239,68,68,0.3); color: #fca5a5;">
            <?= count($masalah) ?> ISU TERIDENTIFIKASI
        </span>
        <?php if ($kritisCount > 0): ?>
        <span style="background: rgba(239,68,68,0.2); border: 1px solid #ef4444; color: #fca5a5; font-size: 10px; padding: 3px 10px; border-radius: 20px; font-weight: 700; margin-left: 4px;">
            ⚠ <?= $kritisCount ?> KRITIS
        </span>
        <?php endif; ?>
    </div>

    <?php foreach ($masalah as $isu):
        $sc = $severityColor[$isu['severity']] ?? $severityColor['Sedang'];
    ?>
    <div class="issue-card" style="background: <?= $sc['bg'] ?>; border: 1px solid <?= $sc['border'] ?>33; border-left: 4px solid <?= $sc['border'] ?>;">
        <div class="issue-header">
            <div style="flex: 1;">
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 6px;">
                    <span style="font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 1px; background: rgba(255,255,255,0.05); padding: 2px 8px; border-radius: 20px;">
                        <?= htmlspecialchars($isu['kategori']) ?>
                    </span>
                    <?php foreach ($isu['sdgs_goals'] ?? [] as $g): ?>
                    <span style="font-size: 10px; color: #10b981; font-weight: 700; background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); padding: 2px 6px; border-radius: 4px;">
                        SDGs #<?= $g ?>
                    </span>
                    <?php endforeach; ?>
                </div>
                <h5 class="issue-title"><?= htmlspecialchars($isu['judul']) ?></h5>
            </div>
            <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px; flex-shrink: 0;">
                <span class="severity-badge" style="background: <?= $sc['badge'] ?>22; border: 1px solid <?= $sc['badge'] ?>55; color: <?= $sc['text'] ?>;">
                    <i data-lucide="<?= $sc['icon'] ?>" style="width: 12px;"></i>
                    <?= $isu['severity'] ?>
                </span>
            </div>
        </div>
        
        <p class="issue-desc"><?= htmlspecialchars($isu['deskripsi']) ?></p>
        
        <div style="margin-bottom: 12px;">
            <div style="font-size: 11px; color: #475569; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">📋 Kegiatan yang Direkomendasikan</div>
            <ul class="issue-kegiatan">
                <?php foreach (array_slice($isu['kegiatan'] ?? [], 0, 4) as $k): ?>
                <li><?= htmlspecialchars($k) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php if (!empty($isu['dampak_positif'])): ?>
        <div style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.15); border-radius: 8px; padding: 10px 14px; margin-bottom: 12px; font-size: 12px; color: #6ee7b7;">
            ✨ <strong>Dampak Positif:</strong> <?= htmlspecialchars($isu['dampak_positif']) ?>
        </div>
        <?php endif; ?>

        <div class="issue-metrics">
            <div class="issue-metric-item">
                <div class="metric-value" style="color: <?= $sc['text'] ?>;">
                    <?= number_format($isu['dampak'] ?? 0) ?>
                </div>
                <div class="metric-label"><?= htmlspecialchars($isu['satuan_dampak'] ?? 'Terdampak') ?></div>
            </div>
            <div class="issue-metric-item">
                <div class="metric-value" style="color: #a78bfa;">
                    <?= formatRp($isu['estimasi_biaya'] ?? 0) ?>
                </div>
                <div class="metric-label">Estimasi Biaya</div>
            </div>
            <div class="issue-metric-item">
                <div class="metric-value" style="color: #34d399;">
                    <?= htmlspecialchars($isu['estimasi_waktu'] ?? '-') ?>
                </div>
                <div class="metric-label">Estimasi Waktu</div>
            </div>
        </div>

        <?php if (!empty($isu['sumber_dana'])): ?>
        <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <span style="font-size: 10px; color: #475569; font-weight: 700; text-transform: uppercase;">Sumber Dana:</span>
            <?php foreach ($isu['sumber_dana'] as $sd): ?>
            <span style="background: rgba(139,92,246,0.12); border: 1px solid rgba(139,92,246,0.2); color: #c4b5fd; font-size: 10px; padding: 2px 8px; border-radius: 20px; font-weight: 600;">
                <?= htmlspecialchars($sd) ?>
            </span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="info-banner">
        <i data-lucide="check-circle"></i>
        Tidak ada isu kritis terdeteksi dari data saat ini. Klik "RE-CALCULATE ANALISIS" untuk memperbarui.
    </div>
    <?php endif; ?>

    {{-- ================================================================ --}}
    {{-- TABEL RINGKASAN REKOMENDASI --}}
    {{-- ================================================================ --}}
    <?php if (!empty($masalah)): ?>
    <div class="neural-card" style="padding: 28px; margin-top: 24px; margin-bottom: 32px;">
        <div class="section-title" style="margin-bottom: 16px;">
            <div style="width: 36px; height: 36px; background: rgba(139,92,246,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #8b5cf6; border: 1px solid rgba(139,92,246,0.2);">
                <i data-lucide="list-checks" style="width: 18px;"></i>
            </div>
            <h3>Ringkasan Rekomendasi Kegiatan</h3>
        </div>
        <div style="overflow-x: auto;">
        <table class="rekom-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Isu / Program</th>
                    <th style="text-align: center;">Prioritas</th>
                    <th style="text-align: right;">Penerima Manfaat</th>
                    <th style="text-align: right;">Estimasi Biaya</th>
                    <th style="text-align: center;">Waktu</th>
                    <th style="text-align: left;">Sumber Dana Utama</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($masalah as $isu): 
                    $sc2 = $severityColor[$isu['severity']] ?? $severityColor['Sedang'];
                ?>
                <tr>
                    <td>
                        <div style="font-weight: 700; color: white; font-size: 13px;"><?= htmlspecialchars($isu['judul']) ?></div>
                        <div style="font-size: 11px; color: #475569; margin-top: 2px;"><?= htmlspecialchars($isu['kategori']) ?></div>
                    </td>
                    <td style="text-align: center;">
                        <span class="severity-badge" style="background: <?= $sc2['badge'] ?>22; border: 1px solid <?= $sc2['badge'] ?>55; color: <?= $sc2['text'] ?>; display: inline-flex;">
                            <?= $isu['severity'] ?>
                        </span>
                    </td>
                    <td style="text-align: right; color: white; font-weight: 700;">
                        <?= number_format($isu['dampak'] ?? 0) ?>
                        <div style="font-size: 10px; color: #475569; font-weight: 400;"><?= htmlspecialchars($isu['satuan_dampak'] ?? '') ?></div>
                    </td>
                    <td style="text-align: right; color: #a78bfa; font-weight: 700;">
                        <?= formatRp($isu['estimasi_biaya'] ?? 0) ?>
                    </td>
                    <td style="text-align: center; color: #34d399; font-weight: 600; font-size: 12px;">
                        <?= htmlspecialchars($isu['estimasi_waktu'] ?? '-') ?>
                    </td>
                    <td style="font-size: 11px; color: #64748b;">
                        <?= htmlspecialchars(implode(', ', array_slice($isu['sumber_dana'] ?? [], 0, 2))) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                {{-- Total Row --}}
                <tr>
                    <td colspan="2" style="text-align: right; color: #64748b; font-size: 12px; font-weight: 700;">TOTAL KEBUTUHAN ANGGARAN</td>
                    <td style="text-align: right; color: white; font-weight: 700;">
                        <?= number_format(array_sum(array_column($masalah, 'dampak'))) ?>
                        <div style="font-size: 10px; color: #475569; font-weight: 400;">total penerima manfaat</div>
                    </td>
                    <td style="text-align: right; font-size: 18px; font-weight: 900; color: white;">
                        <?= formatRp(array_sum(array_column($masalah, 'estimasi_biaya'))) ?>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>
    <?php endif; ?>

    {{-- ================================================================ --}}
    {{-- DEMOGRAFI BREAKDOWN --}}
    {{-- ================================================================ --}}
    <div class="section-title">
        <div style="width: 36px; height: 36px; background: rgba(6,182,212,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #06b6d4; border: 1px solid rgba(6,182,212,0.2);">
            <i data-lucide="pie-chart" style="width: 18px;"></i>
        </div>
        <h3>Analisis Komposisi Penduduk</h3>
    </div>

    <div class="demo-grid-2">
        {{-- Pekerjaan --}}
        <div class="neural-card" style="padding: 24px;">
            <h5 style="color: white; font-weight: 700; margin-bottom: 16px; font-family: 'Outfit';">Top Pekerjaan Warga</h5>
            <?php
            $pekerjaanTop = $demo['pekerjaan_top'] ?? [];
            $maxPek = !empty($pekerjaanTop) ? max(array_column($pekerjaanTop, 'jml')) : 1;
            $pekColors = ['#8b5cf6','#06b6d4','#10b981','#f59e0b','#ec4899','#6366f1','#14b8a6','#f43f5e'];
            ?>
            <div class="mini-chart">
            <?php foreach ($pekerjaanTop as $i => $pek): ?>
                <div class="mini-bar-row">
                    <div class="mini-bar-label"><?= htmlspecialchars(mb_substr($pek['nama'] ?? 'Lainnya', 0, 20)) ?></div>
                    <div class="mini-bar-track">
                        <div class="mini-bar-fill" style="width: <?= $maxPek > 0 ? ($pek['jml']/$maxPek*100) : 0 ?>%; background: <?= $pekColors[$i % count($pekColors)] ?>;"></div>
                    </div>
                    <div class="mini-bar-value"><?= number_format($pek['jml'] ?? 0) ?></div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>

        {{-- Pendidikan --}}
        <div class="neural-card" style="padding: 24px;">
            <h5 style="color: white; font-weight: 700; margin-bottom: 16px; font-family: 'Outfit';">Tingkat Pendidikan</h5>
            <?php
            $pendidikan = $demo['pendidikan'] ?? [];
            $maxPend = !empty($pendidikan) ? max(array_column($pendidikan, 'jml')) : 1;
            $pendColors = ['#ef4444','#f97316','#f59e0b','#84cc16','#10b981','#06b6d4','#3b82f6','#8b5cf6','#ec4899','#64748b'];
            ?>
            <div class="mini-chart">
            <?php foreach ($pendidikan as $i => $pend): 
                if (($pend['jml'] ?? 0) == 0) continue;
            ?>
                <div class="mini-bar-row">
                    <div class="mini-bar-label"><?= htmlspecialchars(mb_substr($pend['nama'] ?? 'N/A', 0, 22)) ?></div>
                    <div class="mini-bar-track">
                        <div class="mini-bar-fill" style="width: <?= $maxPend > 0 ? ($pend['jml']/$maxPend*100) : 0 ?>%; background: <?= $pendColors[$i % count($pendColors)] ?>;"></div>
                    </div>
                    <div class="mini-bar-value"><?= number_format($pend['jml'] ?? 0) ?></div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- PELUANG & INVESTASI --}}
    {{-- ================================================================ --}}
    <?php if (!empty($peluang)): ?>
    <div class="section-title">
        <div style="width: 36px; height: 36px; background: rgba(16,185,129,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #10b981; border: 1px solid rgba(16,185,129,0.2);">
            <i data-lucide="trending-up" style="width: 18px;"></i>
        </div>
        <h3>Peluang & Investasi Desa</h3>
        <span class="section-badge" style="background: rgba(16,185,129,0.15); border-color: rgba(16,185,129,0.3); color: #6ee7b7;">
            <?= count($peluang) ?> PELUANG TERIDENTIFIKASI
        </span>
    </div>

    <div class="opportunity-grid">
    <?php foreach ($peluang as $opp): 
        $potensiHigh = ($opp['potensi'] ?? '') === 'Tinggi';
    ?>
        <div class="opp-card">
            <span class="opp-emoji"><?= htmlspecialchars($opp['ikon'] ?? '🌟') ?></span>
            <span class="potensi-badge" style="<?= $potensiHigh ? 'background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7;' : 'background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.3); color: #fcd34d;' ?>">
                <?= $potensiHigh ? '🔥' : '📈' ?> POTENSI <?= htmlspecialchars($opp['potensi'] ?? '') ?>
            </span>
            <div class="opp-title"><?= htmlspecialchars($opp['judul'] ?? '') ?></div>
            <div class="opp-desc"><?= htmlspecialchars($opp['deskripsi'] ?? '') ?></div>
            <ul class="opp-list">
                <?php foreach (array_slice($opp['peluang'] ?? [], 0, 4) as $p): ?>
                <li><?= htmlspecialchars($p) ?></li>
                <?php endforeach; ?>
            </ul>
            <div class="opp-meta">
                <div class="opp-meta-row">
                    <span class="opp-meta-label">Modal Awal</span>
                    <span class="opp-meta-value"><?= htmlspecialchars($opp['modal_awal'] ?? '-') ?></span>
                </div>
                <div class="opp-meta-row">
                    <span class="opp-meta-label">Potensi ROI</span>
                    <span class="opp-meta-value" style="color: #34d399;"><?= htmlspecialchars($opp['potensi_roi'] ?? '-') ?></span>
                </div>
                <div class="opp-meta-row">
                    <span class="opp-meta-label">Balik Modal</span>
                    <span class="opp-meta-value"><?= htmlspecialchars($opp['waktu_balik_modal'] ?? '-') ?></span>
                </div>
                <?php if (!empty($opp['mitra'])): ?>
                <div style="margin-top: 8px; display: flex; gap: 6px; flex-wrap: wrap;">
                    <?php foreach ($opp['mitra'] as $m): ?>
                    <span style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: #64748b; font-size: 10px; padding: 2px 8px; border-radius: 20px;">
                        <?= htmlspecialchars($m) ?>
                    </span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>

    {{-- ================================================================ --}}
    {{-- PETA PENDANAAN --}}
    {{-- ================================================================ --}}
    <?php if (!empty($pendanaan['alokasi'])): ?>
    <div class="funding-bar-container">
        <div class="section-title" style="margin-bottom: 20px;">
            <div style="width: 36px; height: 36px; background: rgba(245,158,11,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #f59e0b; border: 1px solid rgba(245,158,11,0.2);">
                <i data-lucide="landmark" style="width: 18px;"></i>
            </div>
            <h3>Peta Alokasi Pendanaan Program</h3>
            <div style="margin-left: auto; text-align: right;">
                <div style="font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Total Kebutuhan</div>
                <div style="font-size: 22px; font-weight: 900; color: white;">
                    <?= formatRp($pendanaan['total_kebutuhan'] ?? 0) ?>
                </div>
            </div>
        </div>

        {{-- Color bar --}}
        <div class="funding-bar">
        <?php foreach ($pendanaan['alokasi'] as $alok): 
            $src  = $alok['sumber'];
            $pct  = $alok['persentase'];
            $colors = $fundingColors[$src] ?? ['#64748b', '#94a3b8'];
        ?>
            <div class="funding-bar-segment" 
                 style="width: <?= $pct ?>%; background: linear-gradient(135deg, <?= $colors[0] ?>, <?= $colors[1] ?>);"
                 title="<?= htmlspecialchars($src) ?>: <?= $pct ?>%">
            </div>
        <?php endforeach; ?>
        </div>

        {{-- Legend --}}
        <div class="funding-legend">
        <?php foreach ($pendanaan['alokasi'] as $alok): 
            $src  = $alok['sumber'];
            $colors = $fundingColors[$src] ?? ['#64748b', '#94a3b8'];
        ?>
            <div class="funding-legend-item">
                <div class="funding-legend-dot" style="background: <?= $colors[0] ?>;"></div>
                <span class="funding-legend-label"><?= htmlspecialchars($src) ?></span>
                <span class="funding-legend-pct"><?= $alok['persentase'] ?>%</span>
                <span style="font-size: 11px; color: #475569; margin-left: 2px;">(<?= formatRp($alok['nominal']) ?>)</span>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    {{-- ================================================================ --}}
    {{-- FOOTER / DISCLAIMER --}}
    {{-- ================================================================ --}}
    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; padding: 20px; margin-bottom: 32px;">
        <div style="display: flex; align-items: flex-start; gap: 12px;">
            <i data-lucide="info" style="width: 18px; color: #8b5cf6; flex-shrink: 0; margin-top: 2px;"></i>
            <div>
                <div style="font-size: 13px; color: #94a3b8; font-weight: 600; margin-bottom: 6px;">Tentang XO Intelligence Hub</div>
                <p style="font-size: 12px; color: #475569; margin: 0; line-height: 1.7;">
                    Analisis ini dihasilkan secara otomatis dari data nyata yang tersimpan di database xoSID. 
                    Data demografi bersumber dari <strong style="color: #94a3b8;"><?= number_format($demo['total_penduduk'] ?? 0) ?> jiwa penduduk terdaftar</strong> <?= ucwords(setting('sebutan_desa')) ?> <?= htmlspecialchars(identitas('nama_desa')) ?>. 
                    IDM dan SDGs diambil dari <strong style="color: #94a3b8;">API resmi Kemendesa</strong> dan dicache secara lokal. 
                    Estimasi biaya dan rekomendasi program bersifat indikatif berbasis standar nasional dan kondisi nyata desa. 
                    Keputusan akhir tetap merupakan kewenangan pemerintah desa.
                </p>
                <div style="margin-top: 10px; font-size: 11px; color: #334155;">
                    <i data-lucide="clock" style="width: 12px; display: inline-block; vertical-align: middle;"></i>
                    Data diperbarui: <strong style="color: #475569;">{{ date('d F Y, H:i', strtotime($generatedAt)) }} WIB</strong>
                    &nbsp;·&nbsp;
                    <a href="{{ ci_route('status_desa.reklasifikasi_analisis_cerdas') }}" style="color: #8b5cf6; font-weight: 700;">Refresh sekarang →</a>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// Animate bars on load
document.addEventListener('DOMContentLoaded', function() {
    const bars = document.querySelectorAll('.mini-bar-fill');
    bars.forEach(bar => {
        const targetWidth = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => { bar.style.width = targetWidth; }, 300);
    });

    // Reinit Lucide icons
    if (typeof lucide !== 'undefined') lucide.createIcons();

    // Init Uploaders
    initUploader('prodeskel-dropzone', 'prodeskel-input', 'prodeskel_file');
    initUploader('id-dropzone', 'id-input', 'indeks_desa_file');

    // Progressive Recalculate Trigger
    const recalcTrigger = document.getElementById('btn-recalculate-trigger');
    if (recalcTrigger) {
        recalcTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            startProgressiveRecalculate();
        });
    }
});

function startProgressiveRecalculate() {
    const overlay = document.getElementById('progress-bar-overlay');
    const msgEl = document.getElementById('progress-message');
    const fillEl = document.getElementById('progress-fill');
    const pctEl = document.getElementById('progress-percent');
    const spinner = document.getElementById('progress-spinner');
    
    if (!overlay || !msgEl || !fillEl || !pctEl || !spinner) return;
    
    overlay.style.display = 'flex';
    msgEl.innerText = 'Menghubungi server & mempersiapkan kalkulasi...';
    fillEl.style.width = '0%';
    pctEl.innerText = '0%';
    spinner.innerText = '🔄';
    spinner.style.animation = 'spin 2s linear infinite';
    
    // Step 1: Membaca kependudukan & zip (40%)
    runRecalculateStep(1, function(err1, res1) {
        if (err1) {
            handleRecalculateError(err1);
            return;
        }
        
        msgEl.innerText = res1.message;
        fillEl.style.width = '40%';
        pctEl.innerText = '40%';
        
        // Step 2: Analisis 9 Titik Strategis (75%)
        setTimeout(function() {
            runRecalculateStep(2, function(err2, res2) {
                if (err2) {
                    handleRecalculateError(err2);
                    return;
                }
                
                msgEl.innerText = res2.message;
                fillEl.style.width = '75%';
                pctEl.innerText = '75%';
                
                // Step 3: Membuat & Arsip PDF (100%)
                setTimeout(function() {
                    runRecalculateStep(3, function(err3, res3) {
                        if (err3) {
                            handleRecalculateError(err3);
                            return;
                        }
                        
                        msgEl.innerText = res3.message;
                        fillEl.style.width = '100%';
                        pctEl.innerText = '100%';
                        spinner.innerText = '✨';
                        spinner.style.animation = 'none';
                        
                        // Reload to reflect fresh data and the new PDF archive
                        setTimeout(function() {
                            overlay.style.display = 'none';
                            window.location.reload();
                        }, 1500);
                    });
                }, 1000);
            });
        }, 1000);
    });
}

function runRecalculateStep(step, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '/index.php/status_desa/recalculate_progress?step=' + step, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const res = JSON.parse(xhr.responseText);
                if (res.status) {
                    callback(null, res);
                } else {
                    callback(res.message || 'Terjadi kesalahan sistem.');
                }
            } catch (e) {
                callback('Gagal membaca respon server.');
            }
        } else {
            callback('Gagal memproses. Server mengembalikan kode error: ' + xhr.status);
        }
    };
    xhr.onerror = function() {
        callback('Kesalahan jaringan. Pastikan server web aktif.');
    };
    xhr.send();
}

function handleRecalculateError(err) {
    const overlay = document.getElementById('progress-bar-overlay');
    if (overlay) overlay.style.display = 'none';
    alert('Kalkulasi Gagal: ' + err);
}

function initUploader(dropzoneId, inputId, endpointKey) {
    const dropzone = document.getElementById(dropzoneId);
    const input = document.getElementById(inputId);
    
    if (!dropzone || !input) return;
    
    dropzone.addEventListener('click', () => input.click());
    
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });
    
    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('dragover');
    });
    
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        if (e.dataTransfer.files.length) {
            handleUpload(e.dataTransfer.files[0], endpointKey, dropzone);
        }
    });
    
    input.addEventListener('change', () => {
        if (input.files.length) {
            handleUpload(input.files[0], endpointKey, dropzone);
        }
    });
}

function handleUpload(file, key, dropzone) {
    const formData = new FormData();
    formData.append(key, file);
    
    const originalContent = dropzone.innerHTML;
    
    dropzone.innerHTML = `
        <div class="upload-spinner"></div>
        <div id="${dropzone.id}-progress" style="font-size:12px;color:#a78bfa;font-weight:700;margin-top:8px;">Mengunggah ${file.name}... (0%)</div>
        <div style="width:100%;background:rgba(255,255,255,0.05);height:4px;border-radius:2px;margin-top:8px;overflow:hidden;max-width:200px;margin-left:auto;margin-right:auto;">
            <div id="${dropzone.id}-progress-bar" style="width:0%;height:100%;background:#8b5cf6;transition:width 0.1s ease;"></div>
        </div>
    `;
    
    const progressText = document.getElementById(`${dropzone.id}-progress`);
    const progressBar = document.getElementById(`${dropzone.id}-progress-bar`);
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/index.php/status_desa/upload_analisis_cerdas', true);
    
    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            const percentComplete = Math.round((e.loaded / e.total) * 100);
            if (percentComplete < 100) {
                progressText.innerText = `Mengunggah ${file.name}... (${percentComplete}%)`;
                progressBar.style.width = percentComplete + '%';
            } else {
                progressText.innerText = `Memproses & Mengekstrak data...`;
                progressBar.style.width = '100%';
                progressBar.style.background = '#06b6d4';
            }
        }
    };
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.status) {
                    dropzone.innerHTML = `
                        <div class="upload-success-icon">✓</div>
                        <div style="font-size:12px;color:#10b981;font-weight:700;">Berhasil diunggah & diproses!</div>
                    `;
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    dropzone.innerHTML = originalContent;
                    alert('Gagal memproses file: ' + (data.messages ? data.messages.join('\n') : 'Terjadi kesalahan'));
                    rebindUploader(dropzone, key);
                }
            } catch (e) {
                dropzone.innerHTML = originalContent;
                alert('Gagal membaca respon dari server.');
                rebindUploader(dropzone, key);
            }
        } else {
            dropzone.innerHTML = originalContent;
            alert('Gagal mengunggah file. Kode error HTTP: ' + xhr.status);
            rebindUploader(dropzone, key);
        }
    };
    
    xhr.onerror = function() {
        dropzone.innerHTML = originalContent;
        alert('Terjadi kesalahan jaringan.');
        rebindUploader(dropzone, key);
    };
    
    xhr.send(formData);
}

function rebindUploader(dropzone, key) {
    const inputId = dropzone.id === 'prodeskel-dropzone' ? 'prodeskel-input' : 'id-input';
    initUploader(dropzone.id, inputId, key);
}
</script>

@endsection
