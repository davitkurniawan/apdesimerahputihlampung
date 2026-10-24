import json
import re

with open('scratch/lampung_data.json', 'r') as f:
    data = f.read()

with open('resources/views/xosid/registrasi.blade.php', 'r') as f:
    content = f.read()

# find const dataWilayah = { ... };
pattern = re.compile(r'const dataWilayah = \{.*?\};\n', re.DOTALL)

# format the data to look nice but compact
json_str = data
new_js = f"const dataWilayah = {json_str};\n"

new_content = pattern.sub(new_js, content)

with open('resources/views/xosid/registrasi.blade.php', 'w') as f:
    f.write(new_content)

print("Injected successfully.")
