import urllib.request
import re
import json

url = "https://raw.githubusercontent.com/cahyadsn/wilayah/master/db/wilayah.sql"
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
try:
    with urllib.request.urlopen(req) as response:
        content = response.read().decode('utf-8')
except Exception as e:
    print(f"Failed to fetch: {e}")
    exit(1)

data = {"18": {"nama": "LAMPUNG (18)", "kabupaten": {}}}

# find all INSERT INTO `wilayah` VALUES ('18.01.10.2013','Desa Bumi Daya');
pattern = re.compile(r"\('([0-9\.]+)','(.*?)'\)")
matches = pattern.findall(content)

for kode_with_dots, nama in matches:
    if not kode_with_dots.startswith("18"):
        continue
        
    kode = kode_with_dots.replace(".", "")
    
    if len(kode) == 2:
        pass
    elif len(kode) == 4:
        data["18"]["kabupaten"][kode] = {"nama": f"{nama} ({kode})", "kecamatan": {}}
    elif len(kode) == 6:
        kab_kode = kode[:4]
        if kab_kode in data["18"]["kabupaten"]:
            data["18"]["kabupaten"][kab_kode]["kecamatan"][kode] = {"nama": f"{nama} ({kode})", "desa": {}}
    elif len(kode) == 10:
        kab_kode = kode[:4]
        kec_kode = kode[:6]
        if kab_kode in data["18"]["kabupaten"] and kec_kode in data["18"]["kabupaten"][kab_kode]["kecamatan"]:
            data["18"]["kabupaten"][kab_kode]["kecamatan"][kec_kode]["desa"][kode] = f"{nama} ({kode})"

# Check if Bumi Daya exists
bumi_daya_exists = "1801102013" in data["18"]["kabupaten"]["1801"]["kecamatan"]["180110"]["desa"]
print(f"Bumi Daya found: {bumi_daya_exists}")

# read the blade file
with open('resources/views/xosid/registrasi.blade.php', 'r') as f:
    blade_content = f.read()

pattern = re.compile(r'const dataWilayah = \{.*?\};\n', re.DOTALL)
json_str = json.dumps(data)
new_js = f"const dataWilayah = {json_str};\n"

new_blade = pattern.sub(new_js, blade_content)

with open('resources/views/xosid/registrasi.blade.php', 'w') as f:
    f.write(new_blade)

print("Injected Kemendagri codes successfully!")
