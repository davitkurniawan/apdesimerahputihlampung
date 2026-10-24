import os

files = [
    '/Users/kampungjagoan/Downloads/XOSID/resources/views/admin/katadesa_ai/index.blade.php',
    '/Users/kampungjagoan/Downloads/XOSID/storage/xosid_backups/resources/views/admin/katadesa_ai/index.blade.php'
]

light_theme_css = '''
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
'''

for f in files:
    if not os.path.exists(f):
        continue
    with open(f, 'r') as file:
        content = file.read()
        
    if "LIGHT THEME SUPPORT FOR KATADESA AI INDEX" not in content:
        content = content.replace("</style>", light_theme_css + "\n    </style>")
        with open(f, 'w') as file:
            file.write(content)
        print(f"Patched {f}")
    else:
        print(f"Already patched {f}")
