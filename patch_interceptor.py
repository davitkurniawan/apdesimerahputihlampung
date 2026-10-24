import os

f = '/Users/kampungjagoan/Downloads/XOSID/xosid_emergency_autoload.php'

light_css = '''
    /* LIGHT THEME EXCLUSIVE OVERRIDES FOR XOSID EMERGENCY AUTOLOAD */
    body.light-theme .intel-hub {
        color: #0f172a;
    }
    
    body.light-theme .intel-hero {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
        border: 1px solid rgba(0,0,0,0.08) !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03) !important;
    }
    body.light-theme .intel-hero h2 { color: #0f172a !important; }
    body.light-theme .intel-hero p, body.light-theme .info-banner { color: #475569 !important; }
    body.light-theme .info-banner { background: #f1f5f9 !important; border-color: rgba(0,0,0,0.05) !important; }
    body.light-theme .intel-hero span { color: #64748b !important; }
    
    body.light-theme .neural-card,
    body.light-theme .stat-card,
    body.light-theme .issue-card,
    body.light-theme .opp-card,
    body.light-theme .funding-bar-container,
    body.light-theme .dimension-card {
        background: #ffffff !important;
        border: 1px solid rgba(0,0,0,0.08) !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02) !important;
    }
    
    body.light-theme .neural-card:hover,
    body.light-theme .stat-card:hover,
    body.light-theme .issue-card:hover,
    body.light-theme .opp-card:hover,
    body.light-theme .dimension-card:hover {
        background: #f8fafc !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important;
        border-color: #8b5cf6 !important;
    }
    
    body.light-theme .neural-card h4,
    body.light-theme .stat-card .stat-value,
    body.light-theme .intel-section-title h3,
    body.light-theme .issue-card .issue-title,
    body.light-theme .opp-card .opp-title,
    body.light-theme .dimension-name,
    body.light-theme .score-label,
    body.light-theme .funding-legend-pct,
    body.light-theme .mini-bar-value,
    body.light-theme .issue-metric-item .metric-value,
    body.light-theme .dropzone .title,
    body.light-theme .dropzone div[style*="color: white"] {
        color: #0f172a !important;
    }
    
    body.light-theme .neural-card p,
    body.light-theme .stat-card .stat-label,
    body.light-theme .stat-card .stat-sub,
    body.light-theme .issue-card .issue-desc,
    body.light-theme .opp-card .opp-desc,
    body.light-theme .funding-legend-label,
    body.light-theme .mini-bar-label,
    body.light-theme .issue-metric-item .metric-label,
    body.light-theme .dropzone .subtitle,
    body.light-theme .dropzone div,
    body.light-theme .dropzone div[style*="color: #94a3b8"] {
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
    body.light-theme .intel-hero > div:nth-child(1) > div:nth-child(2) > div:nth-child(2) {
        color: #0f172a !important;
    }
</style>
'''

if os.path.exists(f):
    with open(f, 'r') as file:
        content = file.read()
    
    if "LIGHT THEME EXCLUSIVE OVERRIDES FOR XOSID EMERGENCY AUTOLOAD" not in content:
        content = content.replace("</style>", light_css)
        with open(f, 'w') as file:
            file.write(content)
        print(f"Patched {f}")
    else:
        print(f"Already patched {f}")
