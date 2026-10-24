import os

header_files = [
    '/Users/kampungjagoan/Downloads/XOSID/resources/views/admin/layouts/partials/header.blade.php',
    '/Users/kampungjagoan/Downloads/XOSID/storage/xosid_backups/resources/views/admin/layouts/partials/header.blade.php'
]

for f in header_files:
    if not os.path.exists(f):
        continue
    with open(f, 'r') as file:
        content = file.read()
    
    # Restrict the typography overrides so they DO NOT affect header and sidebar
    bad_rule_1 = '''    body.light-theme h1, body.light-theme h2, body.light-theme h3, 
    body.light-theme h4, body.light-theme h5, body.light-theme h6 {'''
    
    good_rule_1 = '''    body.light-theme .content-wrapper h1, body.light-theme .content-wrapper h2, body.light-theme .content-wrapper h3, 
    body.light-theme .content-wrapper h4, body.light-theme .content-wrapper h5, body.light-theme .content-wrapper h6 {'''
    
    bad_rule_2 = '''    body.light-theme p, body.light-theme label, body.light-theme span:not(.xosid-menu-label) {'''
    good_rule_2 = '''    body.light-theme .content-wrapper p, body.light-theme .content-wrapper label, body.light-theme .content-wrapper span {'''
    
    content = content.replace(bad_rule_1, good_rule_1)
    content = content.replace(bad_rule_2, good_rule_2)
    
    with open(f, 'w') as file:
        file.write(content)
    print(f"Fixed sidebar excludes in {f}")
