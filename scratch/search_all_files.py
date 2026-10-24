import os

word = 'yurisdiksi'
root_dir = '/Users/kampungjagoan/Downloads/XOSID'

for root, dirs, files in os.walk(root_dir):
    if '.git' in root or 'node_modules' in root or 'vendor/bin' in root:
        continue
    for file in files:
        if file.endswith(('.php', '.html', '.js', '.css', '.json', '.yml', '.md', '.txt')):
            path = os.path.join(root, file)
            try:
                with open(path, 'r', encoding='utf-8', errors='ignore') as f:
                    content = f.read()
                    if word in content.lower():
                        print(f"Found in: {path}")
            except Exception as e:
                pass
