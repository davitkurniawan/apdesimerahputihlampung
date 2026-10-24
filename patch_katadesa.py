import os

files = [
    '/Users/kampungjagoan/Downloads/XOSID/resources/views/admin/katadesa_ai/partial_neural.blade.php',
    '/Users/kampungjagoan/Downloads/XOSID/storage/xosid_backups/resources/views/admin/katadesa_ai/partial_neural.blade.php'
]

light_theme_css = '''
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
'''

for f in files:
    if not os.path.exists(f):
        continue
    with open(f, 'r') as file:
        content = file.read()
        
    if "LIGHT THEME SUPPORT FOR KATADESA AI" not in content:
        content = content.replace("</style>", light_theme_css + "\n    </style>")
        with open(f, 'w') as file:
            file.write(content)
        print(f"Patched {f}")
    else:
        print(f"Already patched {f}")
