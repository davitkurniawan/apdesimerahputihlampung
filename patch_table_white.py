import os
import re

files = [
    '/Users/kampungjagoan/Downloads/XOSID/xosid_emergency_autoload.php',
    '/Users/kampungjagoan/Downloads/XOSID/resources/views/admin/status_desa/analisis_cerdas.blade.php',
    '/Users/kampungjagoan/Downloads/XOSID/storage/xosid_backups/resources/views/admin/status_desa/analisis_cerdas.blade.php'
]

new_rules = '''
    body.light-theme .rekom-table td[style*="color: white"],
    body.light-theme .rekom-table td div[style*="color: white"] {
        color: #0f172a !important;
    }
    body.light-theme .rekom-table td div[style*="color: #475569"],
    body.light-theme .rekom-table td[style*="color: #64748b"] {
        color: #475569 !important;
    }
'''

for f in files:
    if not os.path.exists(f):
        continue
    with open(f, 'r') as file:
        content = file.read()
    
    if "rekom-table td[style*=\"color: white\"]" not in content:
        content = content.replace("body.light-theme .rekom-table tr:hover td {", new_rules + "\n    body.light-theme .rekom-table tr:hover td {")
        with open(f, 'w') as file:
            file.write(content)
        print(f"Patched {f}")
    else:
        print(f"Already patched {f}")
