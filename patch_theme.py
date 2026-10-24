import os

# 1. Revert header.blade.php so it remains dark when light-theme is active
files = [
    '/Users/kampungjagoan/Downloads/XOSID/resources/views/admin/layouts/partials/header.blade.php',
    '/Users/kampungjagoan/Downloads/XOSID/storage/xosid_backups/resources/views/admin/layouts/partials/header.blade.php'
]

for f in files:
    if not os.path.exists(f):
        continue
    with open(f, 'r') as file:
        content = file.read()
    
    # We want to clear the body.light-theme block in header.blade.php so it doesn't change header colors
    # Actually, we can just replace the block.
    bad_block = '''    body.light-theme {
        --header-bg: #ffffff;
        --header-border: rgba(0,0,0,0.05);
        --text-main: #0f172a;
        --text-main2: #1e293b;
        --text-muted: #94a3b8;
        --text-muted2: #64748b;
        --dropdown-bg: #ffffff;
        --dropdown-border: rgba(0,0,0,0.1);
        --hover-bg: rgba(0,0,0,0.03);
        --avatar-bg: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    }'''
    good_block = '''    body.light-theme {
        /* Header remains dark intentionally */
    }'''
    content = content.replace(bad_block, good_block)
    with open(f, 'w') as file:
        file.write(content)

# 2. Patch index.blade.php to support light theme
dashboard_files = [
    '/Users/kampungjagoan/Downloads/XOSID/resources/views/admin/home/index.blade.php',
    '/Users/kampungjagoan/Downloads/XOSID/storage/xosid_backups/resources/views/admin/home/index.blade.php'
]

for df in dashboard_files:
    if not os.path.exists(df):
        continue
    with open(df, 'r') as file:
        content = file.read()
        
    # Inject CSS variables
    if '--text-primary' not in content:
        content = content.replace(
            "--neural-border: rgba(255, 255, 255, 0.1);",
            "--neural-border: rgba(255, 255, 255, 0.1);\n        --text-primary: white;\n        --text-secondary: #cbd5e1;\n        --text-muted: #94a3b8;"
        )
        
        # Add light theme rules inside <style>
        style_end = "</style>"
        light_css = '''
    body.light-theme {
        --neural-bg: #f8fafc;
        --neural-card: #ffffff;
        --neural-border: rgba(0, 0, 0, 0.1);
        --text-primary: #0f172a;
        --text-secondary: #334155;
        --text-muted: #64748b;
    }
    body.light-theme .glass-card {
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    body.light-theme .xosid-command-center {
        color: var(--text-primary);
    }
</style>'''
        content = content.replace(style_end, light_css)
        
        # Replace hardcoded colors with CSS variables
        content = content.replace('color: white;', 'color: var(--text-primary);')
        content = content.replace('color: #cbd5e1;', 'color: var(--text-secondary);')
        content = content.replace('color: #94a3b8;', 'color: var(--text-muted);')
        
        with open(df, 'w') as file:
            file.write(content)
        print(f"Patched {df}")
