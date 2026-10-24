import os

header_files = [
    '/Users/kampungjagoan/Downloads/XOSID/resources/views/admin/layouts/partials/header.blade.php',
    '/Users/kampungjagoan/Downloads/XOSID/storage/xosid_backups/resources/views/admin/layouts/partials/header.blade.php'
]

global_css = '''    body.light-theme {
        /* Generic Light Theme overrides for main content (AdminLTE / Custom Neural Theme) */
        --neural-bg: #f8fafc;
        --neural-card: #ffffff;
        --neural-border: rgba(0, 0, 0, 0.1);
        --text-primary: #0f172a;
        --text-secondary: #334155;
        --text-muted: #64748b;
    }

    body.light-theme .content-wrapper,
    body.light-theme .wrapper,
    body.light-theme .content,
    body.light-theme .main-footer {
        background-color: #f8fafc !important;
        background: #f8fafc !important;
        color: #0f172a !important;
    }

    body.light-theme .box,
    body.light-theme .card,
    body.light-theme .glass-card {
        background-color: #ffffff !important;
        background: #ffffff !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03) !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
        color: #0f172a !important;
    }

    body.light-theme .box-header,
    body.light-theme .card-header {
        color: #0f172a !important;
        border-bottom: 1px solid rgba(0,0,0,0.05) !important;
    }

    body.light-theme h1, body.light-theme h2, body.light-theme h3, 
    body.light-theme h4, body.light-theme h5, body.light-theme h6 {
        color: #0f172a !important;
    }

    body.light-theme p, body.light-theme label, body.light-theme span:not(.xosid-menu-label) {
        color: #334155;
    }

    /* Force tables to be readable in light mode */
    body.light-theme table.table {
        color: #0f172a !important;
    }
    body.light-theme table.table thead th {
        border-bottom: 2px solid rgba(0,0,0,0.1) !important;
        background: #f1f5f9 !important;
    }
    body.light-theme table.table tbody td {
        border-top: 1px solid rgba(0,0,0,0.05) !important;
    }
'''

for f in header_files:
    if not os.path.exists(f):
        continue
    with open(f, 'r') as file:
        content = file.read()
    
    if "/* Generic Light Theme overrides" not in content:
        # replace the empty body.light-theme block
        empty_block = '''    body.light-theme {
        /* Header remains dark intentionally */
    }'''
        content = content.replace(empty_block, empty_block + "\n" + global_css)
        with open(f, 'w') as file:
            file.write(content)
        print(f"Injected global CSS to {f}")
