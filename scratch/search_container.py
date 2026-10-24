import os
import re

patterns = [
    r'->make\([^)]*\)',
    r'resolve\([^)]*\)',
    r'app\([^)]*\)'
]

for root, dirs, files in os.walk('/Users/kampungjagoan/Downloads/XOSID'):
    if 'vendor' in root or '.git' in root or 'node_modules' in root:
        continue
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            try:
                with open(filepath, 'r', encoding='utf-8') as f:
                    content = f.read()
                for pattern in patterns:
                    for match in re.finditer(pattern, content):
                        print(f"{filepath}: {match.group(0)}")
            except Exception:
                pass
